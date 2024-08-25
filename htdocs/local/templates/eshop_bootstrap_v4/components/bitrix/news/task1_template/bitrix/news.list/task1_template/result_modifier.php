<?php
if ($arParams["SPECIALDATE"] == 'Y') {
	$arResult["NEED_DATE"] = $arResult['ITEMS'][0]['TIMESTAMP_X'];
	$this->__component->SetResultCacheKeys(array("NEED_DATE"));
}