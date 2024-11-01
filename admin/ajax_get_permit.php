<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

/*-----------function區--------------*/
$tad_uploader = [1, 2, 3];
$tad_uploader_up = [1];

//上層權限
$of_cat_sn = (int) $_GET['of_cat_sn'];
if ($of_cat_sn) {
    $tad_uploader = Utility::get_perm($of_cat_sn, 'catalog');
    $tad_uploader_up = Utility::get_perm($of_cat_sn, 'catalog_up');
}

$data['tad_uploader'] = implode(',', $tad_uploader);
$data['tad_uploader_up'] = implode(',', $tad_uploader_up);

echo json_encode($data, JSON_FORCE_OBJECT);
