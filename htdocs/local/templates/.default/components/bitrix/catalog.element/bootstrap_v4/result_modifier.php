<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

use Custom\Labels\LabelsManager;
$curLabels = $arResult['PROPERTIES']['LABELS']['VALUE'];
$labelsInfo = [];
if ($curLabels) {
    foreach ($curLabels as $curLabel) {
        $labelInfo = LabelsManager::getLabel($curLabel);
        $labelsInfo[] = $labelInfo;
    }
}
$arResult['LABELS'] = $labelsInfo;
$this->__component->SetResultCacheKeys(array("LABELS"));



