<?php
/*-----------引入檔案區--------------*/
include_once __DIR__ . '/header.php';

$updateRecordsArray = $_POST['tr'];
$sort               = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $recordIDValue = (int) $recordIDValue;
    $sql           = 'update ' . $xoopsDB->prefix('tad_uploader') . " set `cat_sort`='{$sort}' where `cat_sn`='{$recordIDValue}'";
    $xoopsDB->queryF($sql) || die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
