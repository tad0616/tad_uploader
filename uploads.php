<?php
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_uploader_uploads.html";

if(sizeof($upload_powers) <= 0 or empty($xoopsUser)){
  redirect_header(XOOPS_URL."/user.php",3, _MD_TADUP_NO_EDIT_POWER);
}
include XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/


function uploads_tabs($cat_sn="",$cfsn=""){
  global $xoopsDB,$xoopsModuleConfig,$xoopsModule,$xoopsTpl,$interface_menu,$TadUpFiles;
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

  $jquery_path=get_jquery(true);
  $randStr=randStr();

  if($_REQUEST['op']=='to_batch_upload'){
    $to_batch_upload='$tabs.tabs("select", last_tab);';
  }

  //抓取預設值
  if(!empty($cfsn)){
    $DBV=get_tad_uploader_file($cfsn);
  }else{
    $DBV=array();
  }

  //預設值設定

  $cfsn=(!isset($DBV['cfsn']))?$cfsn:$DBV['cfsn'];
  $cat_sn=(!isset($DBV['cat_sn']))?$cat_sn:$DBV['cat_sn'];
  $cf_desc=(!isset($DBV['cf_desc']))?"":$DBV['cf_desc'];
  $file_url=(!isset($DBV['file_url']))?"":$DBV['file_url'];
  $cf_sort=(!isset($DBV['cf_sort']))?"":$DBV['cf_sort'];
  //get_tad_uploader_cate_option($of_cat_sn=0,$level=0,$v="",$show_dot='1',$optgroup=true,$chk_view='1')
  $cate_select=get_tad_uploader_cate_option(0,0,$cat_sn,1,false);

  $op=(empty($cfsn))?"insert_tad_uploader":"update_tad_uploader";
  //$op="replace_tad_uploader";
  $up_time_col=empty($cfsn)?"":"<div>"._MD_TADUP_UPDATE_DATE."
  <input type='checkbox' name='new_date' value='1'>"._MD_TADUP_UPDATE_TO_NEW_DATE."</div>";

  if(empty($file_url)){
    $hide="$('#file_link').hide();";
    $selected_up="selected";
  }else{
    $hide="$('#file_up').hide();";
    $selected_link="selected";
  }



  $upform=$TadUpFiles->upform(true,'upfile',null,false);
  $xoopsTpl->assign('upform',$upform);



  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
  $xoopsTpl->assign( "randStr" , $randStr) ;
  $xoopsTpl->assign( "jquery" , $jquery_path) ;
  $xoopsTpl->assign( "to_batch_upload" , $to_batch_upload) ;

  $xoopsTpl->assign( "hide" , $hide) ;
  $xoopsTpl->assign( "cate_select" , $cate_select) ;
  $xoopsTpl->assign( "file_url" , $file_url) ;
  $xoopsTpl->assign( "selected_up" , $selected_up) ;
  $xoopsTpl->assign( "selected_link" , $selected_link) ;
  $xoopsTpl->assign( "cf_desc" , $cf_desc) ;
  $xoopsTpl->assign( "up_time_col" , $up_time_col) ;
  $xoopsTpl->assign( "op" , $op) ;
  $xoopsTpl->assign( "cfsn" , $cfsn) ;
}



//取得單一檔案資料
function get_tad_uploader_file($cfsn=""){
  global $xoopsDB;
  if(empty($cfsn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_uploader_file")." where cfsn='$cfsn'";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}




//更新資料到tad_uploader中
function update_tad_uploader($cfsn=""){
  global $xoopsDB,$xoopsUser,$TadUpFiles;

  $myts = & MyTextSanitizer::getInstance();

  if(!empty($_POST['new_cat_sn'])){
    $cat_sn=add_catalog("",$_POST['new_cat_sn'],"","1",$_POST['cat_sn'],$_POST['cat_add_form']);
  }else{
    $cat_sn=$_POST['cat_sn'];
  }

  $uid=$xoopsUser->getVar('uid');

  //$now=xoops_getUserTimestamp(time());

  if(!empty($_POST['file_url'])){
    $file_url=$myts->addSlashes($_POST['file_url']);
  }else{
    $file_url="";
  }


  if(!empty($_POST['cf_desc'])){
    $cf_desc=$myts->addSlashes($_POST['cf_desc']);
  }else{
    $cf_desc="";
  }

  if($_POST['new_date']=='1'){
    $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $uptime=",up_date='{$now}'";
  }else{
    $uptime="";
  }


  if(!empty($_FILES['upfile']['name'])){
    //先刪掉原有檔案
    $TadUpFiles->set_dir('subdir',"/user_{$uid}");
    $TadUpFiles->set_col("cfsn",$cfsn);
    $TadUpFiles->del_files();


    $name=$_FILES['upfile']['name'];

    $sql = "update ".$xoopsDB->prefix("tad_uploader_file")." set cat_sn='{$cat_sn}',cf_name='{$name}',cf_desc='{$cf_desc}',cf_type='{$_FILES['upfile']['type']}',cf_size='{$_FILES['upfile']['size']}' {$uptime} where cfsn='$cfsn'";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR5);

    $TadUpFiles->upload_one_file($name,$_FILES['upfile']['tmp_name'][$i],$_FILES['upfile']['type'][$i],$_FILES['upfile']['size'][$i],NULL,NULL,"",$cf_desc,true,true);

  }elseif(!empty($file_url)){
    $size=remote_file_size($file_url);

    $sql = "update ".$xoopsDB->prefix("tad_uploader_file")." set cat_sn='{$cat_sn}',cf_name='{$name}',cf_desc='{$cf_desc}',cf_size='{$size}' {$uptime},file_url='{$file_url}' where cfsn='$cfsn'";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR5);
  }else{

    $sql = "update ".$xoopsDB->prefix("tad_uploader_file")." set cat_sn='{$cat_sn}',cf_desc='{$cf_desc}' {$uptime} where cfsn='$cfsn'";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR5);
  }

  return $cat_sn;
}

//取得遠端檔案的大小
function remote_file_size ($url){
  $head = "";
  $url_p = parse_url($url);
  $host = $url_p["host"];
  if(!preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/",$host)){
    // a domain name was given, not an IP
    $ip=gethostbyname($host);
    if(!preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/",$ip)){
      //domain could not be resolved
      return -1;
    }
  }
  $port = intval($url_p["port"]);
  if(!$port) $port=80;
  $path = $url_p["path"];
  //echo "Getting " . $host . ":" . $port . $path . " ...";

  $fp = fsockopen($host, $port, $errno, $errstr, 20);
  if(!$fp) {
    return false;
    } else {
    fputs($fp, "HEAD "  . $url  . " HTTP/1.1\r\n");
    fputs($fp, "HOST: " . $host . "\r\n");
    fputs($fp, "User-Agent: http://www.example.com/my_application\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    $headers = "";
    while (!feof($fp)) {
      $headers .= fgets ($fp, 128);
      }
    }
  fclose ($fp);
  //echo $errno .": " . $errstr . "<br />";
  $return = -2;
  $arr_headers = explode("\n", $headers);
  // echo "HTTP headers for <a href='" . $url . "'>..." . substr($url,strlen($url)-20). "</a>:";
  // echo "<div class='http_headers'>";
  foreach($arr_headers as $header) {
    // if (trim($header)) echo trim($header) . "<br />";
    $s1 = "HTTP/1.1";
    $s2 = "Content-Length: ";
    $s3 = "Location: ";
    if(substr(strtolower ($header), 0, strlen($s1)) == strtolower($s1)) $status = substr($header, strlen($s1));
    if(substr(strtolower ($header), 0, strlen($s2)) == strtolower($s2)) $size   = substr($header, strlen($s2));
    if(substr(strtolower ($header), 0, strlen($s3)) == strtolower($s3)) $newurl = substr($header, strlen($s3));
    }
  // echo "</div>";
  if(intval($size) > 0) {
    $return=strval($size);
  } else {
    $return=$status;
  }
  // echo intval($status) .": [" . $newurl . "]<br />";
  if (intval($status)==302 && strlen($newurl) > 0) {
    // 302 redirect: get HTTP HEAD of new URL
    $return=remote_file_size($newurl);
  }
  return $return;
}
/*-----------執行動作判斷區----------*/
$op = (empty($_REQUEST['op']))? "":$_REQUEST['op'];
$cfsn = (empty($_REQUEST['cfsn']))? "":$_REQUEST['cfsn'];
$cat_sn = (empty($_REQUEST['cat_sn']))? "":$_REQUEST['cat_sn'];

switch($op){

  //新增資料
  case "insert_tad_uploader":
  $cat_sn=add_tad_uploader();
  header("location: index.php?of_cat_sn={$cat_sn}");
  break;

  //更新資料
  case "update_tad_uploader";
  $cat_sn=update_tad_uploader($cfsn);
  header("location: index.php?of_cat_sn={$cat_sn}");
  break;


  default:
  uploads_tabs($cat_sn,$cfsn);
  break;

}

/*-----------秀出結果區--------------*/


include_once XOOPS_ROOT_PATH.'/footer.php';


?>
