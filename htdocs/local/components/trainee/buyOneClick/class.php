<?php

namespace Trainee\MyComponents;

use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;
use \Bitrix\Main\Context;
use Bitrix\Sale\Order;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Bitrix\Main\Loader;
use Filters\ValidatePhoneFilter;
use Bitrix\Main\Engine\CurrentUser;
use \Bitrix\Iblock\Iblock;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\PropertyTable;

class BuyOneClickComponent extends CBitrixComponent implements Controllerable, Errorable
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
			'saveOrder' => [
				'prefilters' => [
					new ValidatePhoneFilter(),
				],
			],
		];
	}

	public function saveOrderAction($phone, $parentComponent)
	{
		$request = Context::getCurrent()->getRequest();
		$productId = htmlspecialcharsEx(trim($request->get('productId')));
		$productCount = htmlspecialcharsEx(trim($request->get('productCount')));
		$productColorId = htmlspecialcharsEx(trim($request->get('productColorId')));
		$productSizeId = htmlspecialcharsEx(trim($request->get('productSizeId')));
		$productTypeSize = htmlspecialcharsEx(trim($request->get('productTypeSize')));

		$iblockId = 3;
		$hlBlockId = 1;

		Loader::includeModule('iblock');
		Loader::includeModule('catalog');
		Loader::includeModule('sale');
		Loader::includeModule("highloadblock");

		$arHlblock = HighloadBlockTable::getById($hlBlockId)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHlblock);
        $strEntityDataClass = $obEntity->getDataClass();   
        $colorData = $strEntityDataClass::getList(array(
            'select' => array('ID','UF_XML_ID','UF_NAME'),
            'filter' => array('=ID' => $productColorId)
        ))->fetch();
		$colorXmlId = $colorData['UF_XML_ID'];
		$offersIblockClass = Iblock::wakeUp($iblockId)->getEntityDataClass();
		$filter = [
			'IBLOCK_ELEMENTS_ELEMENT_CLOTHES_OFFERS_COLOR_REF_VALUE' => $colorXmlId,
			'IBLOCK_ELEMENTS_ELEMENT_CLOTHES_OFFERS_CML2_LINK_VALUE' => $productId,
		];
		if ($productTypeSize == 23) {
			$filter['IBLOCK_ELEMENTS_ELEMENT_CLOTHES_OFFERS_SIZES_CLOTHES_VALUE'] = $productSizeId;
		} elseif ($productTypeSize == 22) {
			$filter['IBLOCK_ELEMENTS_ELEMENT_CLOTHES_OFFERS_SIZES_SHOES_VALUE'] = $productSizeId;
		}

		$offerProp = $offersIblockClass::getList([
			'select' => ['ID', 'NAME', 'COLOR_REF', 'SIZES_CLOTHES', 'CML2_LINK', 'SIZES_SHOES', 'ARTNUMBER'],
			'filter' => $filter
		])->fetch();

		$offerId = $offerProp['ID'];
		
		$userId = CurrentUser::get()->getId();
		if (!$userId) {
			$userId = 6;
		} else {
			$email = CurrentUser::get()->getEmail();
			$fullName = CurrentUser::get()->getFullName();
		}
		if (!Loader::includeModule('sale')) {
			throw new \Exception('Модуль "Интернет-магазин" не загружен.');
		}

		$order = Order::create(SITE_ID, $userId);
		$order->setPersonTypeId(1); // ID типа плательщика (например, 1 - Физическое лицо)

		$propertyCollection = $order->getPropertyCollection();
		$propertyCollection->getPhone()->setValue($phone);
		$propertyCollection->getItemByOrderPropertyCode('EMAIL')->setValue($email);
		$propertyCollection->getItemByOrderPropertyCode('FIO')->setValue($fullName);
		$propertyCollection->getItemByOrderPropertyCode('BUY_ONE_CLICK')->setValue('Y');

		if ($parentComponent === 'bitrix:sale.basket.basket') {

			$basket = Basket::loadItemsForFUser(Fuser::getId(), SITE_ID);

		} elseif ($parentComponent === 'bitrix:catalog.element') {
			$basket = Basket::create(SITE_ID);
			$basket->setFUserId($userId);
			$currency = \Bitrix\Currency\CurrencyManager::getBaseCurrency(); // Базовая валюта  "RUB","USD",...
			$item = $basket->createItem("catalog", $offerId); // "catalog" - тип, 1 - ID товара
			$item->setFields(array(
				'QUANTITY' => $productCount,
				'CURRENCY' => $currency,
				'LID' => SITE_ID,
				'PRODUCT_PROVIDER_CLASS' =>\Bitrix\Catalog\Product\Basket::getDefaultProviderName(),
			));

		}
		$order->setBasket($basket);

		$result = $order->save();

		if (!$result->isSuccess()) {
			return [
				'status' => false,
				'error' => "Ошибка при создании заказа: " . implode(", ", $result->getErrorMessages()),
			];
		} else {
			return [
				'status' => true,
				'mess' => "Заказ успешно создан. ID заказа: " . $order->getId(),
			];
		}

	}

	public function executeComponent()
	{
		$parentComponent = $this->getParent();
		if ($parentComponent) {
			$this->arResult['PARENT_NAME'] = $parentComponent->getName();
		}
		$this->includeComponentTemplate();
	}

}

?>
