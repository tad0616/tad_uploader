<?php
/*-----------引入檔案區--------------*/
include __DIR__ . '/header.php';

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$cfsn = system_CleanVars($_REQUEST, 'cfsn', 0, 'int');
$cat_sn = system_CleanVars($_REQUEST, 'cat_sn', 0, 'int');
$of_cat_sn = system_CleanVars($_REQUEST, 'of_cat_sn', 0, 'int');
$new_of_cat_sn = system_CleanVars($_REQUEST, 'new_of_cat_sn', 0, 'int');
$new_cat_sn = system_CleanVars($_REQUEST, 'new_cat_sn', 0, 'int');

switch ($op) {
    case 'list_mode':
        $_SESSION['list_mode'] = $_GET['list_mode'];
        header("location: index.php?of_cat_sn={$of_cat_sn}");
        exit;
        break;
}
