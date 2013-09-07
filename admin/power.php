<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tad_uploader_adm_power.html";
include_once "header.php";
include_once "../function.php";
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.php";
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

/*-----------function區--------------*/
$module_id = $xoopsModule->getVar('mid');

$main="";

//抓取所有資料夾

$sql = "select cat_sn,cat_title from ".$xoopsDB->prefix("tad_uploader");
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADUP_DB_ERROR1);
while(list($cat_sn,$cat_title)=$xoopsDB->fetchRow($result)){
	$item_list[$cat_sn]=$cat_title;
}


$title_of_form = _MA_TADUP_SET_ACCESS_POWER;
$perm_name = 'catalog';
$formi = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name,$perm_desc);
foreach ($item_list as $item_id => $item_name) {
	$formi->addItem($item_id, $item_name);
}

$main.=$formi->render();

$title_of_form = _MA_TADUP_SET_UPLOAD_POWER;
$perm_name = 'catalog_up';
$formi = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name,$perm_desc);
foreach ($item_list as $item_id => $item_name) {
	$formi->addItem($item_id, $item_name);
}

$main.=$formi->render();

/*-----------秀出結果區--------------*/
echo $main;
include_once 'footer.php';
?>
