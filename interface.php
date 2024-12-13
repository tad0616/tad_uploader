<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

$to_cat_sn = Request::getInt('of_cat_sn');

$and_sn = (empty($to_cat_sn)) ? '' : "?cat_sn=$to_cat_sn";

$interface_menu[_MD_TADUP_INDEX] = 'index.php';
$interface_icon[_MD_TADUP_INDEX] = "fa-file";

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tad_upload_adm'])) {
    $_SESSION['tad_upload_adm'] = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$upload_powers = Utility::get_gperm_cate_arr('catalog_up', 'tad_uploader');

if ((count($upload_powers) > 0 && isset($xoopsUser) && \is_object($xoopsUser)) or $_SERVER['PHP_SELF'] == '/admin.php') {
    $interface_menu[_MD_TADUP_UPLOAD] = "uploads.php{$and_sn}";
    $interface_icon[_MD_TADUP_UPLOAD] = "fa-upload";
}

$interface_menu[_MD_TADUP_ICON] = "op.php?op=list_mode&list_mode=icon&of_cat_sn={$the_cat_sn}";
$interface_icon[_MD_TADUP_ICON] = "fa-folder-tree";

$interface_menu[_MD_TADUP_LIST] = "op.php?op=list_mode&list_mode=more&of_cat_sn={$the_cat_sn}";
$interface_icon[_MD_TADUP_LIST] = "fa-rectangle-list";
