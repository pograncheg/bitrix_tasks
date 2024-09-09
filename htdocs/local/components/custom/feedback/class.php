<?php

namespace Custom\Components;

use Bitrix\Main\Error;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;
use \Bitrix\Main\Context;
use Bitrix\Main\Mail\Event;
// use Custom\Validator\FeedbackValidator;
use Custom\Filters\ValidateFormFilter;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class FeedbackComponent extends CBitrixComponent implements Controllerable, Errorable
{
	protected ErrorCollection $errorCollection;

	public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();
        return $arParams;
    }

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }

	public function configureActions()
	{
		return [
			'sendMessage' => [ 	
				'prefilters' => [
					new ValidateFormFilter(),
				],
			],
		];
	}
	
	public function sendMessageAction(): array
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		define('IBLOCK_ID', 4);

		try {
			$request = Context::getCurrent()->getRequest();
			$name = htmlspecialcharsEx(trim($request->get('name')));
			$surname = htmlspecialcharsEx(trim($request->get('surname')));
			$company = htmlspecialcharsEx(trim($request->get('company')));
			$department = htmlspecialcharsEx(trim($request->get('department')));
			$message = htmlspecialcharsEx(trim($request->get('message')));
			if (!empty($request->getFile('messFile')) && $request->getFile('messFile')['error'] === 0) {
				$messFile = $request->getFile('messFile');
				$tmpPath = $messFile['tmp_name'];
				$fileName = $messFile['name'];
				$type = $messFile['type'];
			}

			if ($tmpPath) {
				$fileID = \CFile::SaveFile(array(
					'name' => $fileName,
					'type' => $type,
					'tmp_name' => $tmpPath,
					'MODULE_ID' => 'iblock'
				), 'iblock');

				if (!$fileID) {

					$this->errorCollection[] = new Error(
						"Ошибка загрузки файла. Попробуйте еще раз.",
						"ERROR_DOWNLOAD_FILE",
						['field' => 'messFile']
					);

				}
			}

			// Запись в инфоблок
			\Bitrix\Main\Loader::includeModule('iblock');
			$iblock = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID);
			$newElement = $iblock->getEntityDataClass()::createObject();
			$newElement->setName("Сообщение " . date("Y-m-d H:i:s"));
			$newElement->setUserName($name);
			$newElement->setUserSurname($surname);
			$newElement->setUserCompany($company);
			$newElement->setDepartment($department);
			$newElement->setUserMessage($message);
			$newElement->setUserFile($fileID);
			$newElement->save();
			// -------------------

			// Отправка письма
			$sendFields = [
				"EMAIL" => $department,
				"USER_NAME" => $name,
				"USER_SURNAME" => $surname,
				"USER_COMPANY" => $company,
				"MESSAGE" => $message,
			];

			Event::send(array(
				"EVENT_NAME" => "FEEDBACK_LETTER",
				"LID" => "s1",
				"C_FIELDS" => $sendFields,
				"FILE" => [$fileID]
			));
			// -------------------
		} catch (ArgumentException $e) {
			$this->errorCollection[] = new Error($e->getMessage());
			return [
				"result" => 'Произошла ошибка',
			];
		}
		if ($errors = $this->getErrors()) {
			return [
				"result" => false,
				'errors' => $errors
			];
		} else {
			return [
				"result" => true,
				"message" => 'Сообщение успешно отправлено'
			];
		}
	
	}
	
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}

}
