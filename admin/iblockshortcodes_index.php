<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use \Bitrix\Main\Application;
use \Bitrix\Main\IO;
use Bitrix\Main\Grid\Options as GridOptions;

IncludeModuleLangFile(__FILE__);

$adminPage->Init();
$adminMenu->Init($adminPage->aModules);

if(empty($adminMenu->aGlobalMenu))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetAdditionalCSS("/bitrix/themes/".ADMIN_THEME_ID."/index.css");

$APPLICATION->SetTitle(GetMessage("IBLOCKSHORTCODES_MAIN_TITLE"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$moduleId = "maxknowbox.iblockshortcodes";
$dir = COption::GetOptionString($moduleId, "templates_dir", false, false, false);
$fullDir = Application::getDocumentRoot() . $dir.'/';

if (!empty($_POST))
{
    session_start();
    if($_POST['method'] == 'DELETE'){
        
        $isDeleted = unlink($_POST['filename']);

        if($isDeleted == true){
            $_SESSION['RESULT'] = 'success';
            $_SESSION['TEXT'] = GetMessage("IBLOCKSHORTCODES_DELETED_SUCCESSFULLY");
            
            LocalRedirect("iblockshortcodes_index.php?lang=ru");
        }
    }
}

if (file_exists($fullDir)) {
    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($fullDir, \RecursiveDirectoryIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS),
        \RecursiveIteratorIterator::SELF_FIRST
    );
    $i = 0;
    foreach($iterator as $item) {$i++;}
    if($i == 0){
        echo CAdminMessage::showMessage(GetMessage("IBLOCKSHORTCODES_NO_FILES"));
    }
}
else{
    echo CAdminMessage::showMessage(GetMessage("IBLOCKSHORTCODES_NO_FILES"));
}

if($_SESSION['RESULT'] && $_SESSION['RESULT'] == "success"){
    echo CAdminMessage::showNote($_SESSION['TEXT']);
    unset($_SESSION['RESULT']);
}

    ?>
    <div class="adm-list-table-wrap">
        <div class="adm-list-table-top">
            <a href="/bitrix/admin/iblockshortcodes_file_create.php?lang=ru" class="adm-btn adm-btn-save adm-btn-add" title="<?=GetMessage("FILE_CREATE")?>" hidefocus="true"><?=GetMessage("FILE_CREATE")?></a>
        </div>
        <table class="adm-list-table">
            <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell adm-list-table-cell-sort" title="<?=GetMessage("TABLE_FILE_NAME")?>">
                        <div class="adm-list-table-cell-inner"><?=GetMessage("TABLE_FILE_NAME")?></div>
                    </td>
                    <td style="width: 20%;" class="adm-list-table-cell adm-list-table-cell-sort" title="<?=GetMessage("TABLE_FILE_DATE")?>">
                        <div class="adm-list-table-cell-inner"><?=GetMessage("TABLE_FILE_DATE")?></div>
                    </td>
                    <td class="adm-list-table-cell adm-list-table-popup-block" title="<?=GetMessage("TABLE_ACTIONS")?>">
                        <div class="adm-list-table-cell-inner"></div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?
                if (file_exists($fullDir)) {
                    foreach($iterator as $item) {
                        if ($item->isFile()) {
                            
                            $file = new IO\File($item->getPathname());

                            $fileMTime = filemtime($file->getPath());
                            
                            $filename = str_replace($fullDir, '', $file->getPath());
                            $format = explode('.', $filename)[1];

                            if($format == 'php'){
                            ?>
                            <tr class="adm-list-table-row">
                                <td class="adm-list-table-cell">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td align="left">
                                                    <a href="iblockshortcodes_file_edit.php?lang=ru&filename=<?=$filename?>"><?=$file->getName()?></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="adm-list-table-cell">
                                    <?=date("d.m.Y H:i:s", $fileMTime)?>
                                </td>
                                <td class="adm-list-table-cell">
                                    <div class="adm-list-table-cell-inner">
                                        <form action="/bitrix/admin/iblockshortcodes_index.php?lang=ru" method="POST">
                                            <input type="hidden" name="method" value="DELETE">
                                            <input type="hidden" name="filename" value="<?=$file->getPath()?>">
                                            <input type="submit" class="adm-btn" value="<?=GetMessage("FILE_DELETE")?>" hidefocus="true">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
<?


require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>