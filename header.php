<?php
include_once __DIR__ . '/../../mainfile.php';
include_once __DIR__ . '/function.php';

$to_cat_sn = (empty($_REQUEST['of_cat_sn'])) ? 0 : (int)$_REQUEST['of_cat_sn'];
$and_sn    = (empty($to_cat_sn)) ? '' : "?cat_sn=$to_cat_sn";

$interface_menu[_TAD_TO_MOD] = 'index.php';
$isAdmin                     = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin   = $xoopsUser->isAdmin($module_id);
}

$upload_powers = chk_cate_power('catalog_up');

if (count($upload_powers) > 0 && $xoopsUser) {
    $interface_menu[_MD_TADUP_UPLOAD] = "uploads.php{$and_sn}";
}

if ($isAdmin) {
    $interface_menu[_TAD_TO_ADMIN] = 'admin/main.php';
}
