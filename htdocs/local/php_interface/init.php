<?php
use \Bitrix\Main\Loader;

Loader::registerNamespace(
    "Custom\Labels",
    Loader::getDocumentRoot()."/local/php_interface/lib/labels"
);