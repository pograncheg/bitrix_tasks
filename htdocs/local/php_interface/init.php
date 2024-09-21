<?php
use \Bitrix\Main\Loader;
use Bitrix\Main\Engine\Router;

Loader::includeModule('main');

Loader::registerNamespace(
    "Filters",
    Loader::getDocumentRoot()."/local/php_interface/src/filters"
);