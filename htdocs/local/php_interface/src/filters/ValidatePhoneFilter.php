<?php
namespace Filters;

use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Application;
// use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Error;
use Bitrix\Main\EventResult;
use Bitrix\Main\Event;
use \Bitrix\Main\Context;

class ValidatePhoneFilter extends Base
{
    public function onBeforeAction(Event $event)
    {
        // $request = Application::getInstance()->getContext()->getRequest();
        $request = Context::getCurrent()->getRequest();

        // $name = htmlspecialcharsEx(trim($request->getPost('name')));
        $phone = htmlspecialcharsEx(trim($request->get('phone')));
        
        // Валидация
        if (empty($phone)) {
            $this->addError(new Error(
                "Заполните поле",
                "EMPTY_NAME",
                // ['field' => 'phone']
            ));
        } elseif (!preg_match('(^(\+375|80)(29|25|44|33)(\d{3})(\d{2})(\d{2})$)', $phone)) {
            $this->addError(new Error(
                "Номер телефона не соответствует формату",
                "NO_PHONE_NAME",
                // ['field' => 'phone']
            ));
        }

        if ($this->getErrors()) {
            return new EventResult(EventResult::ERROR, null, null, $this);
        }       

        return null;
    }
}