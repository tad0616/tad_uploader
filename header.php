<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: header.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //

include_once "../../mainfile.php";
include_once "function.php";

$to_cat_sn=(empty($_REQUEST['of_cat_sn']))?0:intval($_REQUEST['of_cat_sn']);
$and_sn=(empty($to_cat_sn))?"":"?cat_sn=$to_cat_sn";

$interface_menu[_MD_HOMEPAGE]="index.php";
$isAdmin=false;
if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
}

$upload_powers=chk_cate_power("catalog_up");

if(sizeof($upload_powers)>0 and $xoopsUser){
	$interface_menu[_MA_TADUP_UPLOAD]="uploads.php{$and_sn}";
}

if($isAdmin){
  $interface_menu[_TO_ADMIN_PAGE]="admin/index.php";
}

?>
