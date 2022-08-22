<?php
use Bitrix\Main\Localization\Loc;

$moduleId = "maxknowbox.iblockshortcodes";

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);

$adminPage->Init();
$adminMenu->Init($adminPage->aModules);

if(empty($adminMenu->aGlobalMenu))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetAdditionalCSS("/bitrix/themes/".ADMIN_THEME_ID."/index.css");

$APPLICATION->SetTitle(GetMessage("IBLOCKSHORTCODES_EDIT_TITLE"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($_REQUEST['save']) > 0 && check_bitrix_sessid())
{

    session_start();
    if($_POST['FILENAME'] !== '' && $_POST['FILENAME'] !== '.php'){
        $isSaved = false;

        $dir = $_SERVER["DOCUMENT_ROOT"].$_POST['FILEDIR'];
        if(!is_dir($dir)){
            mkdir($dir, 0755, true);
        }
        
        $text = htmlspecialchars_decode($_POST['FILECONTENT']);
        $file = $dir.'/'.$_POST['FILENAME'];
        $isSaved = file_put_contents($file, $text);

        if($isSaved !== false){
            $_SESSION['RESULT'] = 'success';
            $_SESSION['TEXT'] = GetMessage("IBLOCKSHORTCODES_SAVED_SUCCESSFULLY");
            
            LocalRedirect("iblockshortcodes_index.php?lang=ru");
        }
    }
    else{
        echo CAdminMessage::showMessage(GetMessage("IBLOCKSHORTCODES_ENTER_FILENAME"));
    }

    
}

?>
<style>
    .edit-content{
        border-radius: 5px;
        border: 1px solid;
        border-color: #87919c #959ea9 #9ea7b1 #959ea9;
        background-color: #FFFFFF !important;
        height: 480px;
        outline: none;
        padding: 10px;
    }
</style>
<div class="adm-detail-toolbar">
    <span style="position:absolute;"></span>
	<a href="iblockshortcodes_index.php?lang=ru" class="adm-detail-toolbar-btn" title="" id="btn_list" style="display: flex; text-decoration: none;">
        <span class="adm-detail-toolbar-btn-l"></span>
        <span class="adm-detail-toolbar-btn-text"><?=Loc::getMessage('IBLOCKSHORTCODES_EDIT_BACK')?></span>
        <span class="adm-detail-toolbar-btn-r"></span>
    </a>
</div>
<?

$dir = COption::GetOptionString($moduleId, "templates_dir", false, false, false);
$filename = $_GET['filename'];
$content = htmlspecialchars(file_get_contents($_SERVER['DOCUMENT_ROOT'].$dir."/".$_GET['filename']));

$aTabs = array(
    array(
        'DIV' => 'my_options',
        'TAB' => Loc::getMessage('IBLOCKSHORTCODES_EDIT_TAB'),
        'TITLE' => Loc::getMessage('IBLOCKSHORTCODES_EDIT_TAB_TITLE'),
        'CONTENT' => '
        <tr>
            <td width="40%">'.Loc::getMessage("IBLOCKSHORTCODES_FILE_NAME").' <span class="required">*</span>:</td>
            <td width="60%"><input type="text" class="filename" name="FILENAME" value="'.$filename.'" size="30" maxlength="100"></td>
        </tr>
        <tr>
            <td colspan="2" width="100%">
                <pre class="edit-content" contenteditable="true">'.$content.'</pre>
            </td>
        </tr>',
    )
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>
    <form method='post' action='' name='bootstrap'>
        <input type="hidden" name="FILEDIR" value="<?=$dir?>">
        <input type="hidden" name="moduleId" value="maxknowbox.iblockshortcodes">
        <input type="hidden" name="FILECONTENT" value="<?=$content?>" id="fileContent">
        <? $tabControl->Begin();

        foreach ($aTabs as $aTab)
        {
            $tabControl->BeginNextTab();
        }

        $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false)); ?>

        <?= bitrix_sessid_post(); ?>
        <? $tabControl->End(); ?>
    </form>
    <script>
        const contentInput = document.querySelector(".edit-content");
        const fileNameInput = document.querySelector('.filename');

        
        document.onclick = function (e) {
            if (e.target.className != "edit-content") {
                BX("fileContent").value = contentInput.innerHTML;
            };

            if (e.target.className != "filename") {
                const filename = fileNameInput.value;
                
                if(filename.includes('.php') !== true){
                    document.querySelector('.filename').value = filename+'.php';
                }
            };
        };
    </script>
<?

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>