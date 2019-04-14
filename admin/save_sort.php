<?php
/*-----------引入檔案區--------------*/
include '../../../include/cp_header.php';

$cat_sn = (int)$_POST['cat_sn'];
$sort = (int)$_POST['sort'];
$sql = 'update ' . $xoopsDB->prefix('tad_uploader') . " set `cat_sort`='{$sort}' where cat_sn='{$cat_sn}'";
$xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ') ';
