<?php
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

class bx_triggmine extends CModule 
{
    var $MODULE_ID = "bx.triggmine";
    var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
    var $PARTNER_NAME = 'TriggMine';
    var $PARTNER_URI = 'http://triggmine.com/';

    function __construct()
    {
        $arModuleVersion = array();
        
        include("version.php");
        
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = Loc::getMessage('SHARE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SHARE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('SHARE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SHARE_PARTNER_URL');
    }


    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);
        //MetrikaHandler::register($this->MODULE_ID);
    }

    function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);
        //MetrikaHandler::unRegister($this->MODULE_ID);
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}



