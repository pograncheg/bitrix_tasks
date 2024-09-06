<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.forms");
?>
<div class="ui-ctl ui-ctl-textbox">
    <form method="post" id="feedback" enctype='multipart/form-data'>
        <label for="name"><?=GetMessage("CUSTOM_FEEDBACK_NAME")?></label>
        <div class="ui-ctl ui-ctl-textbox">
            <input type="text" class="ui-ctl-element" placeholder="Имя" name="name" id="name">
            <div id="name-error" style="color:red;" class="fieldError"></div>
        </div>
        <label for="surname"><?=GetMessage("CUSTOM_FEEDBACK_SURNAME")?></label>
        <div class="ui-ctl ui-ctl-textbox">
            <input type="text" class="ui-ctl-element" placeholder="Фамилия" name="surname" id="surname">
            <div id="surname-error" style="color:red;" class="fieldError"></div>
        </div>
        <label for="company"><?=GetMessage("CUSTOM_FEEDBACK_COMPANY")?></label>
        <div class="ui-ctl ui-ctl-textbox">
            <input type="text" class="ui-ctl-element" placeholder="Компания и должность" name="company" id="company">
        </div>
        <label for="department"><?=GetMessage("CUSTOM_FEEDBACK_DEPARTMENT")?></label>
        <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
	        <div class="ui-ctl-after ui-ctl-icon-angle"></div>
	        <select class="ui-ctl-element" name="department" id="department">
                <option value="sale@company.com">Отдел продаж</option>
                <option value="support@company.com">Отдел поддержки</option>
                <option value="training@company.com">Отдел обучения</option>
                <option value="provision@company.com">Отдел обеспечения</option>
	        </select>
            <div id="department-error" style="color:red;" class="fieldError"></div>
        </div>
        <label for="message"><?=GetMessage("CUSTOM_FEEDBACK_MESSAGE")?>
        <div class="ui-ctl ui-ctl-textarea" required>
	        <textarea class="ui-ctl-element" name="message" id="message"></textarea>
            <div id="message-error" style="color:red;" class="fieldError"></div>
        </div>
        </label>
        <!-- <label class="ui-ctl ui-ctl-file-btn">
	        <input type="file" class="ui-ctl-element">
	        <div class="ui-ctl-label-text"><?=GetMessage("CUSTOM_FEEDBACK_MESSAGE_FILE")?></div>
            <div id="messFile-error" style="color:red;" class="fieldError"></div>
        </label>  -->
        <label class="ui-ctl ui-ctl-file-drop">
            <div class="ui-ctl-label-text">
                <span><?=GetMessage("CUSTOM_FEEDBACK_MESSAGE_FILE")?></span>
                <small><?=GetMessage("CUSTOM_FEEDBACK_MESSAGE_FILE_DRAG_AND_DROP")?></small>
            </div>
	        <input type="file" class="ui-ctl-element" name="messFile">
            <div id="messFile-error" style="color:red;" class="fieldError"></div>
        </label>

        <div class="ui-ctl">
            <input type="submit" name="submit"  value="<?=GetMessage("CUSTOM_FEEDBACK_SUBMIT")?>" class="ui-btn">
        </div>
    </form>
</div>

<script>


</script>

