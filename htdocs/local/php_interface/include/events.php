<?php
// файл /bitrix/php_interface/init.php
// регистрируем обработчик
// IncludeModuleLangFile(__FILE__);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));
class MyClass
{
	public static function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{

		if($arFields["IBLOCK_ID"] == IBLOCK_CATALOG) {

			if ($arFields["ACTIVE"] == 'N') {
				$arSelect = Array("ID", "IBLOCK_ID", "NAME", "SHOW_COUNTER");
				$arFilter = Array("IBLOCK_ID" => IBLOCK_CATALOG, "ID" => $arFields["ID"]);
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				$arItems = $res->Fetch();
				// echo "<pre>"; print_r($arItems); echo "</pre>";
				if ($arItems["SHOW_COUNTER"] > MAX_COUNT) {
					global $APPLICATION;
					$sText = GetMessage("DEACTIVE", array('#COUNT#' => $arItems["SHOW_COUNTER"]));
					$APPLICATION->throwException($sText);
					// $APPLICATION->throwException("Товар невозможно деактивировать, у него {$arItems["SHOW_COUNTER"]} просмотров");
					return false;
				}

			}

		}
	}
}