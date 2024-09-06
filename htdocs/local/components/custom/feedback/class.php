<?php

namespace Custom\Components;

use Bitrix\Main\Error;
use Bitrix\Main\Exceptions;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;
use \Bitrix\Main\Context;
use Bitrix\Main\Mail\Event;
use Custom\Validator\FeedbackValidator;

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
					new ActionFilter\HttpMethod([
						ActionFilter\HttpMethod::METHOD_POST
					])
				],
			],
		];
	}
	
	public function sendMessageAction()	: array
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		define('IBLOCK_ID', 4);
		$messFileFormats = ['jpeg', 'jpg', 'png', 'webp'];
		$validator = new FeedbackValidator;
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
				// $messFilePath = __DIR__ . '/images/' . str_replace('/tmp/', '', $messFile['tmp_name']) . '_' . $messFile['name'];
				$validator->validate('messFile', $messFile['name'], ['format' => $messFileFormats]);
			}
			$validator->validate('name', $name, ['required' => true, 'min' => 2]);
			$validator->validate('surname', $surname, ['required' => true, 'min' => 2]);
			$validator->validate('department', $department, ['required' => true]);
			$validator->validate('message', $message, ['required' => true, 'min' => 10]);

			$errors = $validator->getErrors();
			if ($errors) {
				return [
					"result" => false,
					"errors" => $errors
				];
			} 

			if ($tmpPath) {
				$fileID = \CFile::SaveFile(array(
					'name' => $fileName,
					'type' => $type,
					'tmp_name' => $tmpPath,
					'MODULE_ID' => 'iblock'
				), 'iblock');

				if (!$fileID) {
					return [
						"result" => 'Ошибка загрузки файла'
					];
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

			return [
				"result" => true,
				"message" => "Ваше сообщение отправлено!"
			];
		} catch (\Exception $e) {
			$this->errorCollection[] = new Error($e->getMessage());
			return [
				"result" => 'Произошла ошибка',
			];
		}
	
	}
	
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}

}
