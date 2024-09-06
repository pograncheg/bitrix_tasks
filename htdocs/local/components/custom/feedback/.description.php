<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
  "NAME" => GetMessage("CUSTOM_FEEDBACK_NAME"),
  "DESCRIPTION" => GetMessage("CUSTOM_FEEDBACK_DESCRIPTION"),
  "PATH" => array(
    "ID" => "custom_components",
    "NAME" => GetMessage("CUSTOM_FEEDBACK_GROUP_NAME"),
    "CHILD" => array(
      "ID" => "custom_forms",
      "NAME" => GetMessage("CUSTOM_FEEDBACK_CHILD_NAME")
    )
  ),
);