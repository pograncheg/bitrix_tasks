<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="row">
	<div class="col-12">
		<?if (IsModuleInstalled("advertising")):?>
			<div class="mb-3">
				<?$APPLICATION->IncludeComponent(
					"bitrix:advertising.banner",
					"parallax",
					array(
						"COMPONENT_TEMPLATE" => "parallax",
						"TYPE" => "PARALLAX",
						"NOINDEX" => "Y",
						"QUANTITY" => "1",
						"BS_EFFECT" => "fade",
						"BS_CYCLING" => "N",
						"BS_WRAP" => "Y",
						"BS_PAUSE" => "Y",
						"BS_KEYBOARD" => "Y",
						"BS_ARROW_NAV" => "Y",
						"BS_BULLET_NAV" => "Y",
						"BS_HIDE_FOR_TABLETS" => "N",
						"BS_HIDE_FOR_PHONES" => "Y",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"SCALE" => "N",
						"CYCLING" => "N",
						"EFFECTS" => "",
						"ANIMATION_DURATION" => "500",
						"WRAP" => "1",
						"ARROW_NAV" => "1",
						"BULLET_NAV" => "2",
						"KEYBOARD" => "N",
						"EFFECT" => "random",
						"SPEED" => "500",
						"JQUERY" => "Y",
						"DIRECTION_NAV" => "Y",
						"CONTROL_NAV" => "Y",
						"PARALL_HEIGHT" => "400",
						"HEIGHT" => "400"
					),
					false
				);?>
			</div>
		<?endif?>

		<h2>Новости</h2>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "task1_template", Array(
	"IBLOCK_TYPE" => "news",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "1",	// Код информационного блока
		"NEWS_COUNT" => "3",	// Количество новостей на странице
		"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
		"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
		"FILTER_NAME" => "",	// Фильтр
		"FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(	// Свойства
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"PREVIEW_TRUNCATE_LEN" => "120",	// Максимальная длина анонса для вывода (только для типа текст)
		"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
		"DISPLAY_PANEL" => "N",
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"PARENT_SECTION" => "",	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"DISPLAY_NAME" => "Y",	// Выводить название элемента
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"COMPONENT_TEMPLATE" => "flat",
		"SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
		"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
		"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"DISPLAY_DATE" => "Y",	// Выводить дату элемента
		"DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
		"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
		"MEDIA_PROPERTY" => "",	// Свойство для отображения медиа
		"SEARCH_PAGE" => "/search/",	// Путь к странице поиска
		"USE_RATING" => "N",	// Разрешить голосование
		"USE_SHARE" => "N",	// Отображать панель соц. закладок
		"PAGER_TITLE" => "Новости",	// Название категорий
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"SHOW_404" => "N",	// Показ специальной страницы
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
		"TEMPLATE_THEME" => "site",	// Цветовая тема
	),
	false
);
		?>
	</div>
</div>
