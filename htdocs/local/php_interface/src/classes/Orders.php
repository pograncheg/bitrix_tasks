<?php

namespace MyClasses;

use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use \Bitrix\Main\Config\Option;

class Orders
{

    public static function getOrdersInXml()
    {

        if (!Loader::includeModule('sale')) {
            die('Модуль sale не загружен.');
        }


        $lastId = Option::get('getOrdersInXml', 'last_result') ?? 0; //получаем ID последнего выгруженного заказа
        var_dump($lastId);
        $orderList = Order::getList([
            // 'select' => ['ID', 'DATE_INSERT', 'PRICE', 'STATUS_ID', 'USER_ID'], 
            'select' => ['*'],
            'filter' => ['>ID' => $lastId],
            'order' => ['DATE_INSERT' => 'DESC'],
        ]);

        $xml = new \XMLWriter();
        $xml->openMemory(); // Мы будем хранить XML в памяти
        $xml->setIndent(true); // Включаем отступы
        $xml->setIndentString("    "); // Задаем отступы в 4 пробела

        // Начинаем создание XML-документа
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('orders');

        // Добавляем элементы с переносами строк
        while ($order = $orderList->fetch()) {
            if ($order['ID'] > $lastId) {
                $lastId = $order['ID'];
            }
            // echo '<pre>'; print_r($order); echo '</pre>';
            $odjOrder = Order::load($order['ID']);
            $xml->startElement('order');
            $xml->writeAttribute('id', $order['ID']);
            $xml->writeElement('dateInsert', $order['DATE_INSERT']->format('Y-m-d H:i:s'));
            $xml->writeElement('dateUpdate', $order['DATE_UPDATE']->format('Y-m-d H:i:s'));
            $xml->writeElement('personTypeId', $order['PERSON_TYPE_ID']);
            $xml->writeElement('statusId', $order['STATUS_ID']);
            $xml->writeElement('price', $order['PRICE']);
            $xml->writeElement('discountValue', $order['DISCOUNT_VALUE']);
            $xml->writeElement('userId', $order['USER_ID']);
            $xml->writeElement('accountnumber', $order['ACCOUNT_NUMBER']);
            $xml->writeElement('payed', $order['PAYED']);
            $xml->startElement('properties');
            $xml->startElement('userProperties');
            $propertyCollection = $odjOrder->getPropertyCollection();
            foreach ($propertyCollection as $property) {
                if ($property->getValue()) {
                    $xml->writeElement($property->getField('CODE'), $property->getValue());
                }
            }
            $xml->endElement(); // Закрываем элемент userProperties
            $xml->startElement('orderProperties');

            $xml->endElement(); // Закрываем элемент orderProperties
            $xml->endElement(); // Закрываем элемент properties
            $xml->startElement('basketItems');
            

            $basket = $odjOrder->getBasket();
            // echo '<pre>'; print_r($basket); echo '</pre>';
            foreach ($basket as $basketItem) {
                $xml->startElement('basketItem');
                // echo '<pre>'; print_r($basketItem); echo '</pre>';
                // Получение информации о каждом товаре
                $xml->writeElement('productId', $basketItem->getProductId());
                $xml->writeElement('name', $basketItem->getField('NAME'));
                $xml->writeElement('price', $basketItem->getPrice());
                $xml->writeElement('basePrice', $basketItem->getBasePrice());
                $xml->writeElement('quantity', $basketItem->getQuantity());
                $xml->writeElement('discountPrice', $basketItem->getDiscountPrice());

                $xml->endElement(); // Закрываем элемент basketItem
            }
   
            $xml->endElement(); // Закрываем элемент basketItems

            $xml->endElement(); // Закрываем элемент order
        }

        $xml->endElement(); // Закрываем элемент orders
        $xml->endDocument(); // Завершаем документ

        // Получаем строку с XML
        $xmlString = $xml->outputMemory(true);
        Option::Set('getOrdersInXml', 'last_result', $lastId);//сохраняем ID последнего выгруженного заказа
        return $xmlString;
    }
}
