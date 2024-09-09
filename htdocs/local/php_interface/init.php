<?php
use \Bitrix\Main\Loader;
use Bitrix\Main\Engine\Router;

Loader::includeModule('main');

Loader::registerNamespace(
    "Custom\Validator",
    Loader::getDocumentRoot()."/local/php_interface/src/validator"
);

Loader::registerNamespace(
    "Custom\Filters",
    Loader::getDocumentRoot()."/local/php_interface/src/filters"
);