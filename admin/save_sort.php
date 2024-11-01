<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
// 關閉除錯訊息
$xoopsLogger->activated = false;
$cat_sn = (int) $_POST['cat_sn'];
$sort = (int) $_POST['sort'];
$sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader') . '` SET `cat_sort`=? WHERE `cat_sn`=?';
Utility::query($sql, 'ii', [$sort, $cat_sn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
