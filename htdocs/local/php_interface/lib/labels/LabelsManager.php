<?php
namespace Custom\Labels;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

/**
 * Class LabelsManager
 * 
 * @package Custom\Labels
 **/

class LabelsManager
{
    private const HL_BLOCK_ID = 7;
    private static $labelsData = [];

    private static function getLabelByXmlId($xmlId)
    {
        \CModule::IncludeModule('highloadblock'); 
        $arHlblock = HLBT::getById(self::HL_BLOCK_ID)->fetch();
        $obEntity = HLBT::compileEntity($arHlblock);
        $strEntityDataClass = $obEntity->getDataClass();   
        $rsData = $strEntityDataClass::getList(array(
            'select' => array('ID','UF_XML_ID','UF_NAME','UF_LABEL_COLOR','UF_LINK'),
            'filter' => array('=UF_XML_ID' => $xmlId)
        ));
        return $rsData->fetch();
    }

    public static function getLabel($xmlId)
    {     
        if (!array_key_exists($xmlId, self::$labelsData)) {
            self::$labelsData[$xmlId] = self::getLabelByXmlId($xmlId);
        }
        return self::$labelsData[$xmlId];
    }

}