<?php
use \Bitrix\Main\Loader;

Loader::registerNamespace(
    "Custom\Validator",
    Loader::getDocumentRoot()."/local/php_interface/src/validator"
);