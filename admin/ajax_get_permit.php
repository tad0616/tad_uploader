<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/

//上層權限
$of_cat_sn = intval($_GET['of_cat_sn']) ;
if ($of_cat_sn ) {
  $data['catalog'] = getItem_Permissions($of_cat_sn , 'catalog' ) ;
  $data['catalog_up'] = getItem_Permissions($of_cat_sn , 'catalog_up' ) ;
  //var_dump ($data) ;
  echo json_encode($data,JSON_FORCE_OBJECT);
}