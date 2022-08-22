<?php
//подключаем класс и файлы локализации
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
//добавляем пункт меню для нашего модуля
$menu = array(
    array(
        'parent_menu' => 'global_menu_content',//определяем место меню, в данном случае оно находится в главном меню
        'sort' => 400,//сортировка, в каком месте будет находится наш пункт
        'text' => Loc::getMessage('IBLOCKSHORTCODES_MENU_TITLE'),//описание из файла локализации
        'title' => Loc::getMessage('IBLOCKSHORTCODES_MENU_TITLE'),//название из файла локализации
        'url' => 'iblockshortcodes_index.php?lang=ru',//ссылка на страницу из меню
        'more_url' => array('iblockshortcodes_file_edit.php?lang=ru', "iblockshortcodes_file_create.php?lang=ru"),
        'items_id' => 'menu_references',//описание подпункта, то же, что и ранее, либо другое, можно вставить сколько угодно пунктов меню
    ),
);

return $menu;