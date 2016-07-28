<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

$moduleId = "bx.triggmine";

if (!$USER->IsAdmin()) {
    return;
}

CUtil::InitJSCore(array('jquery'));

Loc::loadMessages(__FILE__);

$sort = 'DEF';
$order = 'desc';
$resList = CSite::GetList($sort, $order);
$arSites = array();
while ($arSite = $resList->Fetch()) {
    $arSites[$arSite['LID']] = $arSite['NAME'];
}

$siteId = $_REQUEST['site_id'] ? $_REQUEST['site_id'] : current(array_keys($arSites)); 

$arAllOptions = array(
    
    array("active", Loc::getMessage('MODULE_TRIGGMINE_ACTIVE'), "Y", array("checkbox")),
    array("id", Loc::getMessage('MODULE_TRIGGMINE_KEY'), "TRIGGMINE-DEMO-KEY", array("text", 32)),
    array("ignore_admin", Loc::getMessage('MODULE_TRIGGMINE_IGNORE_ADMIN'), "Y", array("checkbox")),
    array("note" => Loc::getMessage('MODULE_TRIGGMINE_GET_KEY'))
);

$aTabs = array(
    array(
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "ICON" => "ib_settings",
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET")
    ),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update) > 0 && check_bitrix_sessid() && $_REQUEST['change_site'] == 0) {
    foreach ($arAllOptions as $arOption) {
        $name = $arOption[0];
        $val = $_REQUEST[$name];
        if ($arOption[2][0] == "checkbox" && $val != "Y") {
            $val = "N";
        }
        Option::set($moduleId, $name, $val, $siteId);
    }
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $tabControl->ActiveTabParam() . '&site_id=' . $siteId);
}
?>

<? $tabControl->Begin(); ?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>">
    
    <? $tabControl->BeginNextTab(); ?>
    
    <tr>
        <td width="40%" nowrap>
            <label for="site_id"><?= Loc::getMessage('MODULE_TRIGGMINE_SITE') ?>:</label>
        </td>
        <td width="60%">
            <input type="hidden" name="change_site" id="change_site" value="0"/>
            <select name="site_id" onchange="$('#change_site').val(1); $(this).parents('form').submit();">
                <? foreach ($arSites as $value => $label): ?>
                
                    <option value="<?= $value ?>"
                            <? if ($siteId == $value): ?>selected="selected" <? endif ?>><?= $label ?></option>
                <? endforeach ?>
            </select>
        </td>
    </tr>
    
    <? foreach ($arAllOptions as $arOption): ?>

    <? if (count($arOption) === 1): ?>
        <tr>
            <td colspan="2" align="center">
                <div class="adm-info-message-wrap" align="center">
	                <div class="adm-info-message">
	                    
                        <?=$arOption["note"]?>
                        
                    </div>
                </div>
            </td>
        </tr>
    
    <? else: ?>
    <?
        $val = Option::get($moduleId, $arOption[0], $arOption[2], $siteId);
        $type = $arOption[3];
    ?>
        <tr>
            <td width="40%" nowrap <?= $type[0] == "textarea" ? 'class="adm-detail-valign-top"' : '' ?>>
                
                <label for="<?= htmlspecialcharsbx($arOption[0]) ?>"><?= $arOption[1] ?>:</label>
            </td>
            <td width="60%">
                <? if ($type[0] == "checkbox"): ?>
                    <input type="checkbox"
                           id="<?= htmlspecialcharsbx($arOption[0]) ?>" 
                           name="<?= htmlspecialcharsbx($arOption[0]) ?>" 
                           value="Y"<? if ($val == "Y"): ?> checked <? endif ?> >
                    
                <? elseif ($type[0] == "text"): ?>
                    <input type="text" size="<?= $type[1] ?>" maxlength="255"
                           value="<?= htmlspecialcharsbx($val) ?>"
                           name="<?= htmlspecialcharsbx($arOption[0]) ?>">
                           
                <? elseif ($type[0] == "textarea"): ?>
                    <textarea rows="<?= $type[1] ?>" cols="<? echo $type[2] ?>"
                              name="<?= htmlspecialcharsbx($arOption[0]) ?>">
                        <?= htmlspecialcharsbx($val) ?>
                    </textarea>
                <? endif ?>
            </td>
        </tr>
        
    <? endif ?>
    <? endforeach ?>
    
    <? $tabControl->Buttons(); ?>
    
    <input type="submit" name="Update" 
           value="<?= Loc::getMessage("MAIN_SAVE") ?>" 
           title="<?= Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>" class="adm-btn-save">
           
    <?= bitrix_sessid_post(); ?>
    
</form>

<? $tabControl->End(); ?>





