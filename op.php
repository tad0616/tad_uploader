<?php
/*-----------引入檔案區--------------*/
include "header.php";

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$cat_sn=(empty($_REQUEST['cat_sn']))?0:intval($_REQUEST['cat_sn']);
$of_cat_sn=(empty($_REQUEST['of_cat_sn']))?0:intval($_REQUEST['of_cat_sn']);
$cfsn=(empty($_REQUEST['cfsn']))?0:intval($_REQUEST['cfsn']);
$new_of_cat_sn=(empty($_REQUEST['new_of_cat_sn']))?0:intval($_REQUEST['new_of_cat_sn']);
$new_cat_sn=(empty($_REQUEST['new_cat_sn']))?0:intval($_REQUEST['new_cat_sn']);

switch($op){

  case "list_mode":
  $_SESSION['list_mode']=$_GET['list_mode'];
	header("location: index.php?of_cat_sn={$of_cat_sn}");
  break;

}

?>