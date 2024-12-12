<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$updateRecordsArray = Request::getVar('tr', [], null, 'array', 4);
$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $recordIDValue = (int) $recordIDValue;
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader') . '` SET `cat_sort`=? WHERE `cat_sn`=?';
    Utility::query($sql, 'ii', [$sort, $recordIDValue]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
