<?php
/*-----------引入檔案區--------------*/
include __DIR__ . '/../../../include/cp_header.php';
$updateRecordsArray = $_POST['node-'];

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_uploader') . " set `cat_sort`='{$sort}' where `cat_sn`='{$recordIDValue}'";
    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s', xoops_getUserTimestamp(time())) . ')';
