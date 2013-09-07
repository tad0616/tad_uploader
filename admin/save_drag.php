<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-02-28
// $Id: cate.php,v 1.4 2008/05/05 03:21:31 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";

$of_cat_sn=intval(str_replace("node-_","",$_POST['of_cat_sn']));
$cat_sn=intval(str_replace("node-_","",$_POST['cat_sn']));

$sql="update ".$xoopsDB->prefix("tad_uploader")." set `of_cat_sn`='{$of_cat_sn}' where `cat_sn`='{$cat_sn}'";
$xoopsDB->queryF($sql) or die("Reset Fail! (".date("Y-m-d H:i:s").")");


echo "Reset OK! (".date("Y-m-d H:i:s").")";
?>
