<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: index.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //
/*-----------引入檔案區--------------*/
include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/


//列出所有catalog資料
function list_catalog($the_cat_sn=""){
	global $xoopsDB;
	$catalog_form=catalog_form($the_cat_sn);
	
	$sql = "select * from ".$xoopsDB->prefix("tad_uploader")."";

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _AM_TADUP_DB_ERROR1);

  $jquery_path = get_jquery(true);

  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/treetable.php")){
   redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/treetable.php";
	$treetable=new treetable(false,"cat_sn","of_cat_sn","#tbl","save_drag.php",".folder","#save_msg",false,".sort","save_sort.php","#save_msg2");
	$treetable_code=$treetable->render();
	
	$data="
	$jquery_path
  $treetable_code
  <script type='text/javascript'>
	function delete_catalog_func(cat_sn){
		var sure = window.confirm('"._AM_TADUP_DEL_CONFIRM."');
		if (!sure)	return;
		location.href=\"".$_SERVER['PHP_SELF']."?op=delete_catalog&cat_sn=\" + cat_sn;
	}
	</script>

  <div id='save_msg' style='float:right;'></div>
  <div id='save_msg2' style='float:right;'></div>
  
	<table id='tbl' style='border:1px solid gray;padding:8px;'>
	<tr><td colspan=6>$catalog_form</td></tr>
	<tr>
	<th>"._AM_TADUP_FOLDER_NAME."</th>
	<th>"._AM_TADUP_AUTHOR."</th>
	<th>"._AM_TADUP_ENABLE."</th>
	<th>"._AM_TADUP_SHARE."</th>
	<th>"._AM_TADUP_FILE_COUNTER."</th>
	<th>"._AM_TADUP_FUNCTION."</th>
  </tr>
	<tbody style='background-color:white;' class='sort'>
	".get_cata_data()."
  </tbody>
	</table>
  ";
	
	$title=sprintf(_AM_TADUP_LIST_ALL_FILES,$total);
	
	//$data=div_3d($title,$data,"corners");
	
	return $data;
}


//取得所有資料夾列表
function get_cata_data($of_cat_sn=0,$tab=0,$show_function=1){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='$of_cat_sn' order by `cat_sort`";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _AM_TADUP_DB_ERROR1);
	$data="";
	$tab+="18";
	
	while(list($cat_sn,$cat_title,$cat_desc,$cat_enable,$uid,$of_cat_sn,$cat_share,$cat_sort,$cat_count)=$xoopsDB->fetchRow($result)){
		$cat_desc=nl2br($cat_desc);
		$uid_name=XoopsUser::getUnameFromId($uid,1);
        $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;
		$enable=($cat_enable=='1')?"<img src='../images/button_ok.png'>":"<img src='../images/button_cancel.png'>";
		$share=($cat_share=='1')?"<img src='../images/button_ok.png'>":"<img src='../images/encrypted.gif'>";
		$cat=get_catalog($of_cat_sn);


		$fun=($show_function)?"<td>
		<a href='{$_SERVER['PHP_SELF']}?op=catalog_form&cat_sn=$cat_sn'>"._AM_TADUP_EDIT."</a>
		<a href=\"javascript:delete_catalog_func($cat_sn);\">"._AM_TADUP_DEL."</a></td>":"";

		$class=(empty($of_cat_sn))?"":"class='child-of-node-_{$of_cat_sn}'";

		$data.="
    <tr id='node-_{$cat_sn}' $class style='letter-spacing: 0em;'>
		<td style='padding-left: {$tab}px'>
		<span class='folder'>({$cat_sort}) <a href='".XOOPS_URL."/modules/tad_uploader/index.php?of_cat_sn={$cat_sn}'>{$cat_title}</a>
    <div style='font-size:11px;color:gray;'>{$cat_desc}</div></span></td>
		<td>{$uid_name}</td>
		<td align='center'>{$enable}</td>
		<td align='center'>{$share}</td>
		<td>{$cat_count}</td>
		$fun
    </tr>";
		
		$data.=get_cata_data($cat_sn,$tab,$show_function);
	}

	return $data;
}

//catalog編輯表單
function catalog_form($cat_sn=""){
	global $xoopsDB,$xoopsModule;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	
	//抓取預設值
	if(!empty($cat_sn)){
		$DBV=get_catalog($cat_sn);
	}else{
		$DBV=array();
	}

	//預設值設定
	$cat_sn=(!isset($DBV['cat_sn']))?"":$DBV['cat_sn'];
	$cat_title=(!isset($DBV['cat_title']))?"":$DBV['cat_title'];
	$cat_desc=(!isset($DBV['cat_desc']))?"":$DBV['cat_desc'];
	$cat_enable=(!isset($DBV['cat_enable']))?"1":$DBV['cat_enable'];
	$uid=(!isset($DBV['uid']))?"":$DBV['uid'];
	$of_cat_sn=(!isset($DBV['of_cat_sn']))?"":$DBV['of_cat_sn'];
	$cata_select=get_cata_select(array(),$of_cat_sn);
	$cat_share=(!isset($DBV['cat_share']))?"1":$DBV['cat_share'];
	$cat_count=(!isset($DBV['cat_count']))?"":$DBV['cat_count'];

	$cat_max_sort=get_cat_max_sort();
	$cat_sort=(!isset($DBV['cat_sort']))?$cat_max_sort:$DBV['cat_sort'];

  $mod_id=$xoopsModule->getVar('mid');
  $moduleperm_handler =& xoops_gethandler('groupperm');
  $read_group=$moduleperm_handler->getGroupIds("catalog", $cat_sn, $mod_id);
  $post_group=$moduleperm_handler->getGroupIds("catalog_up", $cat_sn, $mod_id);
  
  if(empty($read_group))$read_group=array(1,2,3);
  if(empty($post_group))$post_group=array(1);

  //可見群組
	$SelectGroup_name = new XoopsFormSelectGroup("", "catalog", true,$read_group, 6, true);
	$enable_group = $SelectGroup_name->render();

  //可上傳群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "catalog_up", true,$post_group, 6, true);
  $enable_upload_group = $SelectGroup_name->render();

	$main="
  <FORM action='{$_SERVER['PHP_SELF']}' method='POST' enctype='multipart/form-data'>
    <table class='form_tbl'>
		<tbody>
		<tr>
			<th>"._AM_TADUP_FATHER_FOLDER."</th><td>
			<select name='of_cat_sn'>
			<option></option>
			$cata_select
			</select></td>
			<th>"._MD_TADUP_CAN_ACCESS_GROUPS2."</th>
			<th>"._MD_TADUP_CAN_UPLOADS_GROUPS."</th>
			<th>"._AM_TADUP_FOLDER_DESC."</th>
			</tr>
			<tr>
		<tr><th>"._AM_TADUP_FOLDER_NAME."</th><td>
			<INPUT type='text' name='cat_title' value='{$cat_title}' style='width:98%;'></td>
      <td rowspan=5>$enable_group</td>
      <td rowspan=5>$enable_upload_group</td>
			<td rowspan=5><textarea name='cat_desc' style='width:120px;height:70px;font-size:12px'>{$cat_desc}</textarea>
			<div align='center'>
      <INPUT type='hidden' name='cat_sn' value='{$cat_sn}'>
  		<INPUT type='hidden' name='cat_count' value='{$cat_count}'>
  		<INPUT type='hidden' name='op' value='add_catalog'>
      <INPUT type='submit' value='"._AM_TADUP_SAVE."' style='background-color:#cfcfcf;font-size:16px;'>
      </center>
    </td>
			</tr>
		<th>"._AM_TADUP_FOLDER_SORT."</th><td>
				<INPUT type='text' name='cat_sort' value='{$cat_sort}' size='".strlen($cat_sort)."'></td></tr>
		<tr><th>"._AM_TADUP_ENABLE."</th><td>
			<INPUT type='radio' name='cat_enable' value='1' ".chk($cat_enable,"1","1").">"._YES."
			<INPUT type='radio' name='cat_enable' value='0' ".chk($cat_enable,"0").">"._NO."</td></tr>
		<tr><th>"._AM_TADUP_SHARE."</th><td>
			<INPUT type='radio' name='cat_share' value='1' ".chk($cat_share,"1","1").">"._YES."
			<INPUT type='radio' name='cat_share' value='0' ".chk($cat_share,"0").">"._NO."</td></tr>
		</tbody>
		</table>
		
	</FORM>";

	//$main=div_3d(_AM_TADUP_CREATE_FOLDER,$main,"raised","display:inline;float:left;");
	
	return $main;
}




//取得catalog所有資料陣列
function get_catalog_all(){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix("tad_uploader");
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _AM_TADUP_DB_ERROR1);
	$data=$xoopsDB->fetchArray($result);
	return $data;
}





/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$cat_sn=(empty($_REQUEST['cat_sn']))?"":intval($_REQUEST['cat_sn']);

switch($op){
	case "add_catalog":
	add_catalog($cat_sn,$_POST['cat_title'],$_POST['cat_desc'],$_POST['cat_enable'],$_POST['of_cat_sn'],$_POST['cat_add_form'],$_POST['cat_share'],$_POST['cat_sort'],$_POST['cat_count'],$_POST['catalog'],$_POST['catalog_up']);
	header("location: ".$_SERVER['PHP_SELF']);
	break;

	//刪除資料
	case "delete_catalog";
	delete_catalog($cat_sn);
	header("location: ".$_SERVER['PHP_SELF']);
	break;


	default:
	$main=list_catalog($cat_sn);
	break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('content',$main) ;
include_once 'footer.php';
?>
