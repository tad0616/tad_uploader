<?php
/*-----------引入檔案區--------------*/
include_once __DIR__ . '/header.php';

$updateRecordsArray = $_POST['tr'];
$sort               = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_uploader_file') . " set `cf_sort`='{$sort}' where `cfsn`='{$recordIDValue}'";

    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
