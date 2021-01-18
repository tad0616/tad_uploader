<?php
use Xmf\Request;
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

$to_cat_sn = Request::getInt('of_cat_sn');

$and_sn = (empty($to_cat_sn)) ? '' : "?cat_sn=$to_cat_sn";

$interface_menu[_TAD_TO_MOD] = 'index.php';

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_upload_adm'])) {
    $_SESSION['tad_upload_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$upload_powers = chk_cate_power('catalog_up');

if (count($upload_powers) > 0 && $xoopsUser) {
    $interface_menu[_MD_TADUP_UPLOAD] = "uploads.php{$and_sn}";
}

if ($_SESSION['tad_upload_adm']) {
    $interface_menu[_TAD_TO_ADMIN] = 'admin/main.php';
}
