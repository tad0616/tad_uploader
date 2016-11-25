<?php
/*-----------引入檔案區--------------*/
include_once 'header.php';
include_once '../function.php';
 
/*-----------function區--------------*/
 $catalog = array(1,2,3) ;
$catalog_up= array(1) ;
 
//上層權限
$of_cat_sn = (int)$_GET['of_cat_sn'];
 if ($of_cat_sn) {
     $catalog = getItem_Permissions($of_cat_sn, 'catalog') ;
     $catalog_up = getItem_Permissions($of_cat_sn, 'catalog_up') ;
 }
 
$data['catalog'] = implode(',', $catalog) ;
$data['catalog_up'] = implode(',', $catalog_up) ;
/*
$data['catalog'] =  $catalog  ;
$data['catalog_up']  = $catalog_up  ;
*/
echo json_encode($data, JSON_FORCE_OBJECT);
