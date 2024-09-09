<?php

use CBitrixComponent;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class FeedbackMessage extends CBitrixComponent
{

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

}