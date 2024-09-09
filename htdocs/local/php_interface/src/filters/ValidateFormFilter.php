<?php
namespace Custom\Filters;

use Bitrix\Main\Engine\ActionFilter\Base;
// use Bitrix\Main\Engine\Action;
use Bitrix\Main\Application;
// use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Error;
use Bitrix\Main\EventResult;
use Bitrix\Main\Event;

class ValidateFormFilter extends Base
{
    public function onBeforeAction(Event $event)
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $messFileFormats = ['jpeg', 'jpg', 'png', 'webp'];
        $name = htmlspecialcharsEx(trim($request->getPost('name')));
        $surname = htmlspecialcharsEx(trim($request->getPost('surname')));
        $department = htmlspecialcharsEx(trim($request->getPost('department')));
        $message = htmlspecialcharsEx(trim($request->getPost('message')));
        $surname = htmlspecialcharsEx(trim($request->getPost('surname')));
        $messFile = $request->getFile('messFile');
        
        // Валидация
        if (empty($name)) {
            $this->addError(new Error(
                "Заполните поле",
                "EMPTY_NAME",
                ['field' => 'name']
            ));
        } elseif (mb_strlen($name, 'UTF-8') < 2) {
            $this->addError(new Error(
                "Длина должна быть не менее 2 символов",
                "MIN_LENGTH_NAME",
                ['field' => 'name']
            ));
        }
        if (empty($surname)) {
            $this->addError(new Error(
                "Заполните поле",
                "EMPTY_SURNAME",
                ['field' => 'surname']
            ));
        } elseif (mb_strlen($surname, 'UTF-8') < 2) {
            $this->addError(new Error(
                "Длина должна быть не менее 2 символов",
                "MIN_LENGTH_SURNAME",
                ['field' => 'surname']
            ));
        }
        
        if (empty($department)) {
            $this->addError(new Error(
                "Заполните поле",
                "EMPTY_DEPARTMENT",
                ['field' => 'department']
            ));
        }

        if (empty($message)) {
            $this->addError(new Error(
                    "Заполните поле",
                    "EMPTY_MESSAGE",
                    ['field' => 'message']
                ));
        } elseif (mb_strlen($message, 'UTF-8') < 10) {
            $this->addError(new Error(
                "Длина должна быть не менее 10 символов",
                "MIN_LENGTH_MESSAGE",
                ['field' => 'message']
            ));
        }

        if (!empty($request->getFile('messFile')) && $request->getFile('messFile')['error'] === 0) {
            if (!in_array(pathinfo($messFile['name'])['extension'], $messFileFormats)) {
                $formats = implode('/', $messFileFormats);
                $this->addError(new Error(
                    "Разрешены только форматы $formats",
                    "FORMAT_MESSFILE",
                    ['field' => 'messFile']
                ));
            }
        }

        if ($this->getErrors()) {
            return new EventResult(EventResult::ERROR, null, null, $this);
        }       

        return null;
    }
}