<?php
use Xmf\Request;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$of_cat_sn = Request::getInt('of_cat_sn');

switch ($op) {
    case 'list_mode':
        $_SESSION['list_mode'] = $_GET['list_mode'];
        header("location: index.php?of_cat_sn={$of_cat_sn}");
        exit;
}
