<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.forms");
Bitrix\Main\Page\Asset::getInstance()->addCss($this->getFolder() . '/styles.css');
?>

<button type="submit" class="ui-btn ui-btn-success-light" id="buyOneClickBtn" data-id="<?= $arParams['PRODUCT_ID'] ?>" data-parent="<?= $arResult['PARENT_NAME'];?>"><?= GetMessage("BUY_ONE_CLICK_BUTTON"); ?></button>



