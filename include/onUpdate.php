<?php
function xoops_module_update_tad_uploader(&$module, $old_version) {
    GLOBAL $xoopsDB;

    if(chk_chk1()) go_update1();
    if(!chk_chk2()) go_update2();
    if(!chk_chk3()) go_update3();
    if(!chk_chk4()) go_update4();
    if(!chk_chk5()) go_update5();
    if(chk_chk6()) go_update6();
    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_uploader_batch");

    return true;
}


//檢查是否需要更新
function chk_chk1(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_uploader_dl_log");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return true;
  return false;
}

//執行更新到 1.2
function go_update1(){
  global $xoopsDB;
  $sql="CREATE TABLE IF NOT EXISTS ".$xoopsDB->prefix("tad_uploader_dl_log")." (
  `log_sn` smallint(5) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned NOT NULL,
  `dl_time` datetime NOT NULL,
  `from_ip` varchar(15) NOT NULL,
  `cfsn` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`log_sn`)
  )";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());

  return true;
}


//新增連結欄位
function chk_chk2(){
  global $xoopsDB;
  $sql="select count(`file_url`) from ".$xoopsDB->prefix("tad_uploader_file");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update2(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_uploader_file")." ADD `file_url` varchar(255) NOT NULL  default '' after `up_date`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}


//新增排序欄位
function chk_chk3(){
  global $xoopsDB;
  $sql="select count(`cf_sort`) from ".$xoopsDB->prefix("tad_uploader_file");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update3(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_uploader_file")." ADD `cf_sort` smallint(5) unsigned NOT NULL default '0'";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
  return true;
}



function chk_chk4(){
  if(is_dir(XOOPS_ROOT_PATH."/uploads/tad_uploader_batch")){
    return true;
  }
  return false;
}

function go_update4(){
  global $xoopsDB;
  $dir=XOOPS_ROOT_PATH."/uploads/tad_uploader";
  mk_dir($dir."_batch");

  $sql = "select cfsn,uid,cf_name from ".$xoopsDB->prefix("tad_uploader_file")." where file_url=''";
  $result = $xoopsDB->query($sql) or die($sql);

  while(list($cfsn,$uid,$cf_name)=$xoopsDB->fetchRow($result)){
    //搬移影片檔
    if(!is_dir($dir."/user_{$uid}")){
      mk_dir($dir."/user_{$uid}");
    }
    rename_win("{$dir}/{$cfsn}_{$cf_name}","{$dir}/user_{$uid}/{$cfsn}_{$cf_name}");
  }
  return true;
}


//檢查是否有不目錄的檔案
function chk_chk5(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_uploader_file")." where cat_sn=0";
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update5(){
  global $xoopsDB,$xoopsUser;
  $uid=$xoopsUser->uid();
  $sql="insert into ".$xoopsDB->prefix("tad_uploader")." (`cat_title`, `cat_desc`, `cat_enable`, `uid`, `of_cat_sn`, `cat_share`, `cat_sort`, `cat_count`) VALUES ('"._MD_TADUP_ROOT."' , '' , '1' , '$uid' , 0 , 1 , 0 , 0)";
  $xoopsDB->queryF($sql);
  $cat_sn=$xoopsDB->getInsertId();

  $sql="update ".$xoopsDB->prefix("tad_uploader_file")." set `cat_sn`='$cat_sn' where `cat_sn`= 0 ";
  $xoopsDB->queryF($sql);
  return true;
}

//檢查是否需要建立tad_uploader_files_center
function chk_chk6(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_uploader_files_center");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return true;
  return false;
}

//建立tad_uploader_files_center
function go_update6(){
  global $xoopsDB;

  //取消上傳時間限制
  set_time_limit(0);

  $sql="CREATE TABLE IF NOT EXISTS `".$xoopsDB->prefix("tad_uploader_files_center")."` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(255) NOT NULL default '',
  `col_sn` smallint(5) unsigned NOT NULL default '0',
  `sort` smallint(5) unsigned NOT NULL default '1',
  `kind` enum('img','file') NOT NULL default 'img',
  `file_name` varchar(255) NOT NULL default '',
  `file_type` varchar(255) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  `counter` mediumint(8) unsigned NOT NULL default '0',
  `original_filename` varchar(255) NOT NULL default '',
  `hash_filename` varchar(255) NOT NULL default '',
  `sub_dir` varchar(255) NOT NULL default '',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM ";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());

  $os=(PATH_SEPARATOR==':')?"linux":"win";

  $sql="select * from ".$xoopsDB->prefix("tad_uploader_file")." where `cf_name`!=''";
  $result=$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
  while(list($cfsn ,$cat_sn ,$uid ,$cf_name ,$cf_desc ,$cf_type ,$cf_size ,$cf_count ,$up_date ,$file_url ,$cf_sort)=$xoopsDB->fetchRow($result)){

    if(empty($cf_name))continue;

    $type=explode("/",$cf_type);
    $kind=($type[0]=='image')?"img":"file";
    $kind_dir=($kind=='img')?"image":"file";
    $extarr=explode('.',$cf_name);
    foreach($extarr as $val){
      $ext=strtolower($val);
    }

    $safe_file_name="cfsn_{$cfsn}_1.{$ext}";
    $new_file_name=md5(rand(0,1000).$cf_name);
    $from=XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$cfsn}_{$cf_name}";
    $to=XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$kind_dir}/{$new_file_name}.{$ext}";
    $readme=XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$kind_dir}/{$new_file_name}_info.txt";


    mk_dir(XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$kind_dir}");
    if($kind=='img'){
      mk_dir(XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs");
      $to_thumb=XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs/{$new_file_name}.{$ext}";
    }

    if($os=="win" and _CHARSET=="UTF-8"){
      $from=iconv(_CHARSET,"Big5",$from);
      $to=iconv(_CHARSET,"Big5",$to);
    }elseif($os=="linux" and _CHARSET=="Big5"){
      $from=iconv(_CHARSET,"UTF-8",$from);
      $to=iconv(_CHARSET,"UTF-8",$to);
    }


    if(file_exists($from)){
      if(rename($from,$to)){
        $sql2="insert into ".$xoopsDB->prefix("tad_uploader_files_center")." (`col_name`, `col_sn`, `sort`, `kind`, `file_name`, `file_type`, `file_size`, `description`, `counter`, `original_filename` , `hash_filename` , `sub_dir`) values('cfsn' ,'{$cfsn}' ,'1' ,'{$kind}' ,'{$safe_file_name}' ,'{$cf_type}' ,'{$cf_size}' ,'{$cf_desc}' ,'{$cf_count}' ,'{$cf_name}' ,'{$new_file_name}.{$ext}' ,'/user_{$uid}')";
        $xoopsDB->queryF($sql2) or redirect_header(XOOPS_URL,3,  mysql_error());
        $fp = fopen($readme, 'w');
        fwrite($fp, $cf_name);
        fclose($fp);
      }
    }
  }

  return true;
}



//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
  if ( is_dir( $source ) ){
    @mkdir( $target );
    $d = dir( $source );
    while ( FALSE !== ( $entry = $d->read() ) ){
      if ( $entry == '.' || $entry == '..' ){
        continue;
      }

      $Entry = $source . '/' . $entry;
      if ( is_dir( $Entry ) ) {
        full_copy( $Entry, $target . '/' . $entry );
        continue;
      }
      copy( $Entry, $target . '/' . $entry );
    }
    $d->close();
  }else{
    copy( $source, $target );
  }
}


function rename_win($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
      if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
      }
      return FALSE;
   }
   return TRUE;
}


//做縮圖
function thumbnail($filename="",$thumb_name="",$type="image/jpeg",$width="120"){

  ini_set('memory_limit', '50M');
  // Get new sizes
  list($old_width, $old_height) = getimagesize($filename);

  $percent=($old_width>$old_height)?round($width/$old_width,2):round($width/$old_height,2);

  $newwidth = ($old_width>$old_height)?$width:$old_width * $percent;
  $newheight = ($old_width>$old_height)?$old_height * $percent:$width;

  // Load
  $thumb = imagecreatetruecolor($newwidth, $newheight);
  if($type=="image/jpeg" or $type=="image/jpg" or $type=="image/pjpg" or $type=="image/pjpeg"){
    $source = imagecreatefromjpeg($filename);
    $type="image/jpeg";
  }elseif($type=="image/png"){
    $source = imagecreatefrompng($filename);
    $type="image/png";
  }elseif($type=="image/gif"){
    $source = imagecreatefromgif($filename);
    $type="image/gif";
  }

  // Resize
  imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);

  header("Content-type: image/png");
  imagepng($thumb,$thumb_name);

  return;
  exit;
}
?>
