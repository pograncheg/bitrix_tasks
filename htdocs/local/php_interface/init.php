<?php
use \Bitrix\Main\Loader;

Loader::registerNamespace(
    "MyClasses",
    Loader::getDocumentRoot()."/local/php_interface/src/classes"
);

function ordersUploadAgent()
{
    $xmlString = \MyClasses\Orders::getOrdersInXml();
    $dir = $_SERVER["DOCUMENT_ROOT"] . "/upload/orders/";
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    header('Content-Type: application/xml; charset=utf-8');
    file_put_contents($dir . "/export.xml", $xmlString);
    return "ordersUploadAgent()";
}

