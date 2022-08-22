<?php
use Bitrix\Main\Localization\Loc;

$moduleId = "maxknowbox.iblockshortcodes";

$aTabs = array(
    array(
        'DIV' => 'my_options',
        'TAB' => Loc::getMessage('IBLOCKSHORTCODES_SECTION_TAB'),
        'OPTIONS' => array(
            Loc::getMessage('IBLOCKSHORTCODES_SECTION_COMMON'),
            array('is_enabled',
                Loc::getMessage('IBLOCKSHORTCODES_ENABLED'),
                null,
                array('checkbox'),
            ),
            array('templates_dir',
                Loc::getMessage('IBLOCKSHORTCODES_TEMPLATES_DIR').' <span class="required">*</span>',
                null,
                array('text', 52),
            ),
        )
    )
);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($_REQUEST['save']) > 0 && check_bitrix_sessid())
{
    session_start();
    if($_POST['templates_dir'] !== ''){
        foreach ($aTabs as $aTab)
        {
            __AdmSettingsSaveOptions($moduleId, $aTab['OPTIONS']);
        }
        $_SESSION['RESULT'] = 'success';
        LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANGUAGE_ID . '&mid_menu=1&mid=' . urlencode($moduleId) .
            '&tabControl_active_tab=' . urlencode($_REQUEST['tabControl_active_tab']) . '&sid=' . urlencode($siteId));
    }
    else{
        $_SESSION['RESULT'] = 'error';
        if($_POST['templates_dir'] == ''){
            $_SESSION['MESSAGE'] = Loc::getMessage('IBLOCKSHORTCODES_CANT_USE_ROOT');
        }
    }
}

if($_SESSION['RESULT'] && $_SESSION['RESULT'] == "success"){
    echo CAdminMessage::showNote(GetMessage("IBLOCKSHORTCODES_SAVED_SUCCESSFULLY"));
    unset($_SESSION['RESULT']);
}
else if($_SESSION['RESULT'] && $_SESSION['RESULT'] == "error" && $_SESSION['MESSAGE']){
    echo CAdminMessage::showMessage($_SESSION['MESSAGE']);
    unset($_SESSION['RESULT']);
    unset($_SESSION['MESSAGE']);
}


$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>
    <form method='post' action='' name='bootstrap'>
        <input type="hidden" name="moduleId" value="maxknowbox.iblockshortcodes">
        <? $tabControl->Begin();

        foreach ($aTabs as $aTab)
        {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($moduleId, $aTab['OPTIONS']);
        }

        $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false)); ?>

        <?= bitrix_sessid_post(); ?>
        <? $tabControl->End(); ?>
    </form>
<?