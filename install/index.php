<?php

//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class maxknowbox_iblockshortcodes extends CModule{
    public function __construct(){

        if(file_exists(__DIR__."/version.php")){
      
            $arModuleVersion = array();
      
            include_once(__DIR__."/version.php");
      
            $this->MODULE_ID            = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION       = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE  = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME          = Loc::getMessage("MAXKNOWBOX_IBLOCKSHORTCODES_NAME");
            $this->MODULE_DESCRIPTION   = Loc::getMessage("MAXKNOWBOX_IBLOCKSHORTCODES_DESCRIPTION");
            $this->PARTNER_NAME         = Loc::getMessage("MAXKNOWBOX_IBLOCKSHORTCODES_PARTNER_NAME");
            $this->PARTNER_URI          = Loc::getMessage("MAXKNOWBOX_IBLOCKSHORTCODES_PARTNER_URI");
       }
      
         return false;
    }

    public function InstallEvents(){
        $moduleID = $this->MODULE_ID;
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnEndBufferContent",
            $moduleID,
            "MaxKnowBox\IBlockShortcodes\Parser",
            "parseContent",
            "",
            array(&$content)
        );

        return false;
    }
    public function UnInstallEvents(){
        $moduleID = $this->MODULE_ID;
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnEndBufferContent",
            $moduleID,
            "MaxKnowBox\IBlockShortcodes\Parser",
            "parseContent",
            "",
            array(&$content)
        );

        return false;
    }


    function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/maxknowbox.iblockshortcodes/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/maxknowbox.iblockshortcodes/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        return true;
    }

    //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->InstallEvents();
    }
		//вызываем метод удаления таблицы и удаляем модуль из регистра
    public function doUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $this->UnInstallFiles();
        $this->UnInstallEvents();
    }
}