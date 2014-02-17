<?php
//引入TadTools的函式庫
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";

include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
$TadUpFiles=new TadUpFiles("tad_uploader");


$uid_dir=0;
if($xoopsUser){
  $uid_dir=$xoopsUser->getVar('uid');
}
define("_TAD_UPLOADER_DIR",XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid_dir}");
define("_TAD_UPLOADER_BATCH_DIR",XOOPS_ROOT_PATH."/uploads/tad_uploader_batch/user_{$uid_dir}");
define("_TAD_UPLOADER_URL",XOOPS_URL."/uploads/tad_uploader");
mk_dir(_TAD_UPLOADER_BATCH_DIR);



//新增資料到tad_uploader中
function add_tad_uploader(){
  global $xoopsDB,$xoopsUser,$TadUpFiles;

  $myts =& MyTextSanitizer::getInstance();
  $file_url=isset($_POST['file_url'])?$myts->addSlashes($_POST['file_url']):"";
  $myts->addSlashes();

  if(!empty($_POST['creat_new_cat'])){
    $cat_sn=add_catalog("",$_POST['creat_new_cat'],"","1",$_POST['cat_sn'],$_POST['add_to_cat']);
  }else{
    $cat_sn=$_POST['add_to_cat'];
  }

  if(empty($_FILES['upfile']['name'][0]) and empty($file_url)){
    return $cat_sn;
  }

  $uid=$xoopsUser->uid();
  $sort=1;

  $cf_desc=$myts->addSlashes($_POST['cf_desc']);
  $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));


  if(!empty($file_url)){
    $size=remote_file_size($file_url);
    if(empty($cf_desc)){
      $cf_desc=get_basename($file_url);
    }
    $sql = "insert into ".$xoopsDB->prefix("tad_uploader_file")." (cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,up_date,file_url,cf_sort)
    values('{$cat_sn}','{$uid}','{$name}','{$cf_desc}','{$type}','{$size}','{$now}','{$file_url}','{$cf_sort}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR5."<p>$sql</p>");

  }else{
    foreach($_FILES['upfile']['name'] as $i=>$name){

      if(empty($cf_desc)){
        $cf_desc=$name;
      }

      $sql = "insert into ".$xoopsDB->prefix("tad_uploader_file")." (cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,up_date,cf_sort)
      values('{$cat_sn}','{$uid}','{$name}','{$cf_desc}','{$_FILES['upfile']['type'][$i]}','{$_FILES['upfile']['size'][$i]}','{$now}','{$cf_sort}')";

      $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR5."<p>$sql</p>");

      //取得最後新增資料的流水編號
      $cfsn=$xoopsDB->getInsertId();

      $TadUpFiles->set_dir('subdir',"/user_{$uid}");
      $TadUpFiles->set_col("cfsn",$cfsn);
      $TadUpFiles->upload_one_file($name,$_FILES['upfile']['tmp_name'][$i],$_FILES['upfile']['type'][$i],$_FILES['upfile']['size'][$i],NULL,NULL,"",$_POST['desc'],true,true);
      $sort++;
    }
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

//找出目前資料夾應設順序
function get_cat_max_sort($of_cat_sn=""){
  global $xoopsDB;
  $sql = "select max(cat_sort) from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='{$of_cat_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);
  list($max_sort)=$xoopsDB->fetchRow($result);
  return $max_sort+1;
}


//找出目前資料夾應設順序
function get_file_max_sort($cat_sn=""){
  global $xoopsDB;
  $sql = "select max(cf_sort) from ".$xoopsDB->prefix("tad_uploader_file")." where cat_sn='{$cat_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);
  list($max_sort)=$xoopsDB->fetchRow($result);
  return $max_sort+1;
}


//取得類別下拉選單
function get_cata_select($disable_cat_sn=array(),$dbv=0,$of_cat_sn=0,$tab=""){
  global $xoopsDB;
  $sql = "select cat_sn,cat_title from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='$of_cat_sn' and cat_enable='1'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);
  $option="";
  $tab.="&nbsp;&nbsp;";

  $disabled="";
  while(list($cat_sn,$cat_title)=$xoopsDB->fetchRow($result)){
    if(is_array($disable_cat_sn)){
      $disabled=(in_array($cat_sn,$disable_cat_sn))?"disabled":"";
    }
    if(!check_up_power("catalog_up",$cat_sn))continue;
    $option.="<option value='$cat_sn' ".chk($cat_sn,$dbv,"","selected")." $disabled>{$tab}{$cat_title}</option>\n";
    $option.=get_cata_select($disable_cat_sn,$dbv,$cat_sn,$tab);
  }

  return $option;
}


//取得目錄下拉選單
function get_tad_uploader_cate_option($of_cat_sn=0,$level=0,$v="",$show_dot='1',$optgroup=true,$chk_view='1'){
  global $xoopsDB;
  $dot=($show_dot=='1')?str_repeat("--",$level):"";
  $level+=1;

  $sql = "select count(*),cat_sn from ".$xoopsDB->prefix("tad_uploader_file")." group by cat_sn";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while(list($count,$cat_sn)=$xoopsDB->fetchRow($result)){
    $cate_count[$cat_sn]=$count;
  }

  $option=($of_cat_sn)?"":"";
  $sql = "select cat_sn,cat_title from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='{$of_cat_sn}' order by cat_sort";
  //die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


  if($chk_view)$ok_cat=chk_cate_power('catalog_up');

  while(list($cat_sn,$cat_title)=$xoopsDB->fetchRow($result)){

    $selected=($v==$cat_sn)?"selected":"";
    if(empty($cate_count[$cat_sn]) and $optgroup){
      $option.=($chk_view and !in_array($cat_sn,$ok_cat))?"":"<optgroup label='{$cat_title}' style='font-style: normal;color:black;'>".get_tad_uploader_cate_option($cat_sn,$level,$v,"0")."</optgroup>";
    }else{
      $counter=(empty($cate_count[$cat_sn]))?0:$cate_count[$cat_sn];
      $option.=($chk_view and !in_array($cat_sn,$ok_cat))?"":"<option value='{$cat_sn}' $selected >{$dot}{$cat_title} ($counter)</option>";
      $option.=get_tad_uploader_cate_option($cat_sn,$level,$v,$show_dot,$optgroup,$chk_view);
    }

  }
  return $option;
}


//檢查有無權限
function check_up_power($kind="catalog",$cat_sn=""){
    global $xoopsUser,$xoopsModule,$isAdmin;

    //取得目前使用者的群組編號
    if($xoopsUser) {
      $uid=$xoopsUser->getVar('uid');
      $groups=$xoopsUser->getGroups();
    }else{
      $uid=0;
      $groups = XOOPS_GROUP_ANONYMOUS;
    }

    //若沒分享，則看看是否是自己的資料夾即可。
    $catalog=get_catalog($cat_sn);
    if(($catalog['cat_share']=='0' and $catalog['uid']!=$uid) and !$isAdmin ) return false;

    //取得模組編號
    $module_id = $xoopsModule->getVar('mid');

    //取得群組權限功能
    $gperm_handler =& xoops_gethandler('groupperm');

    //權限項目編號
    $perm_itemid = intval($cat_sn);
    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理

    if(empty($cat_sn)){
      if($kind=="catalog"){
        return true;
      }else{
        if($isAdmin) return true;
      }
    }else{
      if($gperm_handler->checkRight($kind, $cat_sn, $groups, $module_id) or $isAdmin) return true;
    }

    return false;
}

//判斷某人在哪些類別中有觀看或發表(upload)的權利
function chk_cate_power($kind=""){
  global $xoopsDB,$xoopsUser,$xoopsModule,$isAdmin;
  $module_id = $xoopsModule->getVar('mid');
  if(!empty($xoopsUser)){
    if($isAdmin){
      $ok_cat[]="0";
    }
    $user_array=$xoopsUser->getGroups();
    $gsn_arr=implode(",",$user_array);
  }else{
    $user_array=array(3);
    $isAdmin=0;
    $gsn_arr=3;
  }


  $sql = "select gperm_itemid from ".$xoopsDB->prefix("group_permission")." where gperm_modid='$module_id' and gperm_name='$kind' and gperm_groupid in ($gsn_arr)";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  while(list($gperm_itemid)=$xoopsDB->fetchRow($result)){
    $ok_cat[]=$gperm_itemid;
  }

  return $ok_cat;
}



//取得路徑
function get_tad_uploader_cate_path($csn="",$sub=false){
  global $xoopsDB;

  if(!$sub){
    $home[_TAD_TO_MOD]=XOOPS_URL."/modules/tad_uploader/index.php";
  }else{
    $home=array();
  }

  $sql = "select cat_title,of_cat_sn from ".$xoopsDB->prefix("tad_uploader")." where cat_sn='{$csn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($title,$of_csn)=$xoopsDB->fetchRow($result);

  $opt_sub=(!empty($of_csn))?get_tad_uploader_cate_path($of_csn,true):"";

  $opt=$path="";

  if(!empty($title)){
    $opt[$title]=XOOPS_URL."/modules/tad_uploader/index.php?of_cat_sn=$csn";
  }
  if(is_array($opt_sub)){
      $path=array_merge($home,$opt_sub,$opt);
  }elseif(is_array($opt)){
      $path=array_merge($home,$opt);
  }else{
      $path=$home;
  }
  return $path;
}


//取代/新增catalog現有資料
function add_catalog($the_cat_sn="",$cat_title="",$cat_desc="",$cat_enable="1",$of_cat_sn="0",$cat_add_form=0,$cat_share="auto",$cat_sort="0",$cat_count="0",$catalog=array(1,2,3),$catalog_up=array(1)){
  global $xoopsDB,$xoopsUser,$xoopsModule;
  if($xoopsUser){
    $uid=$xoopsUser->getVar('uid');
  }

  //$of_cat_sn=0;
  if(!empty($cat_add_form)){
    $of_cat_sn=$cat_add_form;
  }

  if($cat_share=="auto"){
    if(empty($of_cat_sn)){
      $cat_share=1;
    }else{
      $cat=get_catalog($of_cat_sn);
      $cat_share=$cat['cat_share'];
    }
  }

  $sql = "replace into ".$xoopsDB->prefix("tad_uploader")." (cat_sn,cat_title,cat_desc,cat_enable,uid,of_cat_sn,cat_share,cat_sort,cat_count)
  values('{$the_cat_sn}','{$cat_title}','{$cat_desc}','{$cat_enable}','{$uid}','{$of_cat_sn}','{$cat_share}','{$cat_sort}','{$cat_count}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MA_TADUP_DB_ERROR2);

  //取得最後新增資料的流水編號
  $cat_sn=$xoopsDB->getInsertId();
  if(empty($cat_sn))$cat_sn=$the_cat_sn;

  //寫入權限
  saveItem_Permissions($catalog, $cat_sn, 'catalog');
  saveItem_Permissions($catalog_up, $cat_sn, 'catalog_up');

  return $cat_sn;
}



//儲存權限
function saveItem_Permissions($groups, $itemid, $perm_name) {
  global $xoopsModule;
  $module_id = $xoopsModule->getVar('mid');
  $gperm_handler =& xoops_gethandler('groupperm');

  // First, if the permissions are already there, delete them
  $gperm_handler->deleteByModule($module_id, $perm_name, $itemid);

  // Save the new permissions
  if (count($groups) > 0) {
      foreach ($groups as $group_id) {
          $gperm_handler->addRight($perm_name, $itemid, $group_id, $module_id);
      }
  }
}

/********************檔案處理*******************/

//取得上傳檔名
function get_file_name($real_file_name="",$cfsn="") {
  $f=explode(".",$real_file_name);
  $ln=sizeof($f)-1;
  $sub=$f[$ln];
  if($sub=="php")$real_file_name.="s";
  $uploadfile="{$cfsn}_{$real_file_name}";
  //die($uploadfile);
  return $uploadfile;
}



//判別格式圖檔
function file_pic($file){
  $extarr=explode('.',$file);
  foreach($extarr as $val){
    $ext=strtolower($val);
  }
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tad_uploader/images/mime/{$ext}.png"))return "mime.png";
  return "{$ext}.png";
}

//以流水號取得某目錄資料
function get_catalog($cat_sn=""){
  global $xoopsDB;
  if(empty($cat_sn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_uploader")." where cat_sn='$cat_sn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);
  $data=$xoopsDB->fetchArray($result);
  return $data;
}


//以流水號取得某筆檔案資料
function get_file($cfsn=""){
  global $xoopsDB;
  if(empty($cfsn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_uploader_file")." where cfsn='$cfsn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);
  $data=$xoopsDB->fetchArray($result);
  return $data;
}

//刪除檔案
function delfile($select_files){
  global $xoopsDB,$TadUpFiles;
  if(empty($select_files)){
    redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_NO_SELECTED_FILE);
  }
  foreach($select_files as $cfsn=>$cf_name){
    del_file($cfsn);
  }
}

//刪除一個檔案
function del_file($cfsn="",$del_sql=true){
  global $xoopsDB,$TadUpFiles;
  if(empty($cfsn))return;
  $file=get_file($cfsn);

  if($del_sql){
    $sql = "delete from ".$xoopsDB->prefix("tad_uploader_file")." where cfsn='$cfsn'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR7);
  }
  $TadUpFiles->set_dir('subdir',"/user_{$file['uid']}");
  $TadUpFiles->set_col('cfsn',$cfsn); //若要整個刪除
  $TadUpFiles->del_files();


}

//下載檔案
function dlfile($cfsn=""){
  global $xoopsUser,$xoopsDB,$TadUpFiles;

  $cf=get_catalog_file($cfsn);
  if(!check_up_power("catalog",$cf['cat_sn'])){
    redirect_header("index.php",3, _MD_TADUP_NO_ACCESS_POWER);
  }

  if($xoopsUser){
    $uid=$xoopsUser->getVar("uid");
    add_dl_log($cfsn,$uid);
  }

  //更新人氣值
  update_catalog_file_count($cfsn);

  if(!empty($cf['file_url'])){
    header("location:{$cf['file_url']}");
  }else{
    $sql="select uid from ".$xoopsDB->prefix("tad_uploader_file")." where `cfsn`='$cfsn'";
    $result = $xoopsDB->queryF($sql) or die($sql);
    list($uid)=$xoopsDB->fetchRow($result);
    $TadUpFiles->set_dir('subdir',"/user_{$uid}");

    $sql="select files_sn from ".$xoopsDB->prefix("tad_uploader_files_center")." where  `col_name`='cfsn' and `col_sn`='{$cfsn}'";
    $result = $xoopsDB->queryF($sql) or die($sql);
    list($files_sn)=$xoopsDB->fetchRow($result);

    return $files_sn;
  }
  exit;
}

function get_file_by_cfsn($cfsn=""){
  global $xoopsUser,$xoopsDB,$TadUpFiles;
  $sql="select * from ".$xoopsDB->prefix("tad_uploader_files_center")." where  `col_name`='cfsn' and `col_sn`='{$cfsn}'";
  $result = $xoopsDB->queryF($sql) or die($sql);
  $file=$xoopsDB->fetchArray($result);
  return $file;
}

//下載紀錄
function add_dl_log($cfsn="",$uid=""){
  global $xoopsDB;
  if(empty($cfsn))return;
  if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $myip = $_SERVER['REMOTE_ADDR'];
  } else {
      $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $myip = $myip[0];
  }

  $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
  $sql = "insert into ".$xoopsDB->prefix("tad_uploader_dl_log")." (`uid`,`dl_time`,`from_ip`,`cfsn`) values('{$uid}','{$now}','{$myip}','{$cfsn}')";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, "add log error");

}

//以流水號取得某筆catalog_file資料
function get_catalog_file($cfsn=""){
  global $xoopsDB;
  if(empty($cfsn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_uploader_file")." where cfsn='$cfsn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);
  $data=$xoopsDB->fetchArray($result);
  return $data;
}

//更新檔案人氣值
function update_catalog_file_count($cfsn=""){
  global $xoopsDB;
  $sql = "update ".$xoopsDB->prefix("tad_uploader_file")." set  cf_count = cf_count+1 where cfsn='$cfsn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR6);
  return $cfsn;
}

//更新檔案資料
function update_catalog_file($cfsn="",$col_name="",$col_value=""){
  global $xoopsDB,$xoopsUser;

  $sql = "update ".$xoopsDB->prefix("tad_uploader_file")." set {$col_name}='{$col_value}' where cfsn='{$cfsn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR6);

  return $cfsn;
}


//刪除目錄
function delete_catalog($cat_sn=""){
  global $xoopsDB,$xoopsUser,$xoopsModule,$isAdmin;

  $power=check_up_power("catalog_up",$cat_sn);
  if(!$power)redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_NO_POWER);

  if($xoopsUser) {
    $uid=$xoopsUser->getVar('uid');
    $where=($isAdmin)?"":" and uid='{$uid}'";
  }else{
    $groups = XOOPS_GROUP_ANONYMOUS;
    redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_NO_LOGIN);
  }



  //找出所屬資料夾
  $sql = "select cat_sn from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='{$cat_sn}' $where";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);
  while(list($sub_cat_sn)=$xoopsDB->fetchRow($result)){
    delete_catalog($sub_cat_sn);
  }

  //找出所屬檔案
  $sql = "select cfsn,cf_name from ".$xoopsDB->prefix("tad_uploader_file")." where cat_sn='{$cat_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);
  while(list($cfsn,$cf_name)=$xoopsDB->fetchRow($result)){
    $flies[$cfsn]=$cf_name;
  }

  //刪除所屬檔案
  if(!empty($flies)) delfile($flies);

  //取得某資料夾檔案數
  $get_catfile_num=get_catfile_num($cat_sn);
  //取得某資料夾檔案數
  $get_subcat_num=get_subcat_num($cat_sn);
  $total=$get_catfile_num+$get_subcat_num;

  if($total>0){
    redirect_header($_SERVER['PHP_SELF'],3, sprintf(_MD_TADUP_CANT_DELETE1,$get_subcat_num,$get_catfile_num));
  }else{
    //刪掉指定資料夾
    $sql = "delete from ".$xoopsDB->prefix("tad_uploader")." where cat_sn='$cat_sn' $where";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR8);
  }
}


//取得某資料夾檔案數
function get_catfile_num($cat_sn=0){
  global $xoopsDB;
  if(empty($cat_sn))return;
  $sql = "select count(*) from ".$xoopsDB->prefix("tad_uploader_file")." as a,".$xoopsDB->prefix("tad_uploader")." as b where a.cat_sn=b.cat_sn and (a.cat_sn='{$cat_sn}' or b.of_cat_sn='{$cat_sn}')";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);
  $total=0;
  while(list($data)=$xoopsDB->fetchRow($result)){
    $total+=$data;
  }
  return $total;
}


//取得某資料夾目錄數
function get_subcat_num($cat_sn=0){
  global $xoopsDB;

  $sql = "select count(*) from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='{$cat_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);
  list($data)=$xoopsDB->fetchRow($result);
  return $data;
}

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}

?>