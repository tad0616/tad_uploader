<?php
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_uploader_main.html";
if(empty($_SESSION['list_mode'])) $_SESSION['list_mode']=$xoopsModuleConfig['show_mode'];

include XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/


//列出所有資料
function list_all_data($the_cat_sn=0){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$isAdmin,$xoopsTpl,$TadUpFiles,$interface_menu;

  $interface_menu["<i class='icon-th-large'></i>"]="op.php?op=list_mode&list_mode=icon&of_cat_sn={$the_cat_sn}";
  $interface_menu["<i class='icon-list'></i>"]="op.php?op=list_mode&list_mode=more&of_cat_sn={$the_cat_sn}";

  $sort_code=$up_tool=$del_js=$FooTableJS=$path="";

  //目前路徑
  $arr=get_tad_uploader_cate_path($the_cat_sn);
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php";
  $jBreadCrumb=new jBreadCrumb($arr);
  $path=$jBreadCrumb->render();

  //新增人氣值
  if(!empty($the_cat_sn)){
    update_catalog_count($the_cat_sn);
  }

  //權限檢查
  $check_power=check_up_power("catalog",$the_cat_sn);
  $check_up_power=check_up_power("catalog_up",$the_cat_sn);

  //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
  if(!$check_power) {
    return _MD_TADUP_NO_ACCESS_POWER;
  }


  //底下目錄
  $folder_list=get_folder_list($the_cat_sn,$check_up_power);

  //抓取該目錄底下的檔案
  $files_list=get_files_list($the_cat_sn,$check_up_power);

  //若有權限則可排序
  $jquery=get_jquery(true);

  $upform=$move_option=$menu_option="";
  if($check_up_power){

    //搬移的選單
    $disbale[]=$the_cat_sn;
    $move_option=get_cata_select($disbale);
    //$menu_option=get_cata_select("",$the_cat_sn);
    $menu_option=get_tad_uploader_cate_option(0,0,$the_cat_sn,1,false);
    $upform=$TadUpFiles->upform(true,'upfile',null,false);
  }

  $sql = "select cat_desc from ".$xoopsDB->prefix("tad_uploader")." where cat_sn='{$the_cat_sn}'";
  $result = $xoopsDB->query($sql);
  list($cat_desc)=$xoopsDB->fetchRow($result);


  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/FooTable.php";
    $FooTable = new FooTable();
    $FooTableJS=$FooTable->render(false);
  }

  //該資料夾屬性
  $main=get_catalog_attribute($the_cat_sn,$check_power,$check_up_power);
  //die($main);

  $xoopsTpl->assign('upform',$upform);
  $xoopsTpl->assign('move_option',$move_option);
  $xoopsTpl->assign('menu_option',$menu_option);
  $xoopsTpl->assign( "memory_limit" ,ini_get('memory_limit') ) ;
  $xoopsTpl->assign( "post_max_size" ,ini_get('post_max_size') ) ;
  $xoopsTpl->assign( "upload_max_filesize" ,ini_get('upload_max_filesize') ) ;
  $xoopsTpl->assign( "max_execution_time" ,ini_get('max_execution_time') ) ;
  $xoopsTpl->assign( "path" , $path) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
  $xoopsTpl->assign( "FooTableJS" , $FooTableJS) ;
  $xoopsTpl->assign( "cat_sn" , $the_cat_sn) ;
  $xoopsTpl->assign( "cat_desc" , $cat_desc) ;
  $xoopsTpl->assign( "folder_list" , $folder_list) ;
  $xoopsTpl->assign( "files_list" , $files_list) ;
  $xoopsTpl->assign( "jqueryui" , $jquery) ;
  $xoopsTpl->assign( "up_power" , $check_up_power) ;
  $xoopsTpl->assign( "list_mode" , $_SESSION['list_mode']) ;
  $xoopsTpl->assign( "only_show_desc" , $xoopsModuleConfig['only_show_desc']) ;
  $xoopsTpl->assign( "icon_width" , '130px') ;

}


//抓取底下目錄
function get_folder_list($the_cat_sn="",$check_up_power=""){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$isAdmin;

  $sql = "select cat_sn,cat_title,cat_desc,cat_enable,uid,of_cat_sn,cat_share,cat_sort,cat_count from ".$xoopsDB->prefix("tad_uploader")." where of_cat_sn='{$the_cat_sn}' and cat_enable='1' order by cat_sort";
  //die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);

  $main=$all="";
  $i=0;
  while(list($cat_sn,$cat_title,$cat_desc,$cat_enable,$uid,$of_cat_sn,$cat_share,$cat_sort,$cat_count)=$xoopsDB->fetchRow($result)){

    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
    if(!check_up_power("catalog",$cat_sn)) {
      continue;
    }

    //底下檔案數
    $file_num=get_catfile_num($cat_sn);
    $lock=($cat_share=='1')?"":"_lock";

    $all[$i]['the_cat_sn']=$the_cat_sn;
    $all[$i]['cat_sn']=$cat_sn;
    $all[$i]['lock']=$lock;
    $all[$i]['cat_title']=$cat_title;
    $all[$i]['file_num']=$file_num;
    $all[$i]['cat_count']=$cat_count;
    $all[$i]['cat_desc']=$cat_desc;
    $i++;

  }


  return $all;
}


//抓取該目錄底下的檔案
function get_files_list($the_cat_sn="",$check_up_power=""){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsModuleConfig,$isAdmin;

  //排序
  $sql = "select cfsn,cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,cf_count,up_date,file_url from ".$xoopsDB->prefix("tad_uploader_file")."  where cat_sn='{$the_cat_sn}' order by cf_sort";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR2);


  $all="";
  $i=0;
  while(list($cfsn,$cat_sn,$uid,$cf_name,$cf_desc,$cf_type,$cf_size,$cf_count,$up_date,$file_url)=$xoopsDB->fetchRow($result)){
    $ff=get_file_by_cfsn($cfsn);
    if($ff['kind']=="img"){
      list($width, $height, $type, $attr) = getimagesize(XOOPS_ROOT_PATH."/uploads/tad_uploader/user_{$uid}/image/.thumbs/{$ff['hash_filename']}");
      $pic=XOOPS_URL."/uploads/tad_uploader/user_{$uid}/image/.thumbs/{$ff['hash_filename']}";
    }else{
      $pic=XOOPS_URL."/modules/tad_uploader/images/mime/".file_pic($cf_name);
    }
    //die($pic);
    //取得該檔案其他資料的值
    if($cf_size>1048576){
      $size=round(($cf_size/1048576),1)."M";
    }elseif($cf_size>1024){
      $size=round(($cf_size/1024),1)."K";
    }else{
      $size=$cf_size."bytes";
    }
    $cf_desc=nl2br($cf_desc);
    $cf_desc=(empty($cf_desc))?$cf_name:$cf_desc;

    $fname=strtolower($cf_name);
    if($xoopsModuleConfig['only_show_desc']=='1'){
      if(!empty($file_url)){
        $fname=basename($file_url);
      }
    }

    $up_date=date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($up_date)));

    if($ff['kind']=="img"){
      $all[$i]['thumb_style']=($height > $width)?"width:85px;":"height:64px;max-width:85px;";
    }else{
      $all[$i]['thumb_style']="";
    }
    $all[$i]['pic']=$pic;
    $all[$i]['fname']=($ff['kind']=="img")?"":$fname;
    $all[$i]['cfsn']=$cfsn;
    $all[$i]['cf_name']=$cf_name;
    $all[$i]['up_date']=$up_date;
    $all[$i]['size']=$size;
    $all[$i]['cf_count']=$cf_count;
    $all[$i]['cf_desc']=$cf_desc;
    $all[$i]['cat_sn']=$cat_sn;
    $i++;

  }
  //if($_SESSION['list_mode']!="more")$main.="<div style='clear:both;'></div>";


  //$all=($main)?"<tbody id='sort'>{$main}</tbody>":"";
  return $all;
}


//該資料夾屬性
function get_catalog_attribute($cat_sn="",$check_power=false,$check_up_power=false){
  global $xoopsUser,$xoopsModule,$col_intf,$isAdmin;

  //以流水號取得某筆catalog資料
  $cat=get_catalog($cat_sn);
  //取得某資料夾檔案數
  //$get_catfile_num=get_catfile_num($cat_sn);
  //取得某資料夾檔案數
  //$get_subcat_num=get_subcat_num($cat_sn);

  //管理工具
  $tool=$move_tool="";
  //判斷是否對該模組有管理權限
  if ($check_up_power) {
      $disbale[]=$cat_sn;
      $option=get_cata_select($disbale);
      $move_tool="
      <td>
      <FORM action='{$_SERVER['PHP_SELF']}' method='POST'>
      <img src='images/folder_new.png' alt='"._MD_TADUP_CREATE_FOLDER."' title='"._MD_TADUP_CREATE_FOLDER."' border='0' height='16' width='16' hspace=4 align='absmiddle'>"._MD_TADUP_CREATE_FOLDER."
      <input type='text' name='cat_title' size=12 value='"._MD_TADUP_NEW_FOLDER."'>
      <INPUT type='hidden' name='of_cat_sn' value='{$cat_sn}'>
      <INPUT type='hidden' name='op' value='create_folder'>
      <INPUT type='submit' value='"._TAD_SUBMIT."'>
      </FORM>
      </td>
      ";

      if(!empty($cat_sn)){
        $tool="
       <tr>
        <td>
        <FORM action='{$_SERVER['PHP_SELF']}' method='POST'>
        <img src='images/folder_move.png' alt='"._MD_TADUP_FOLDER_MOVE."' title='"._MD_TADUP_FOLDER_MOVE."' border='0' height='16' width='16' hspace=4 align='absmiddle'>"._MD_TADUP_FOLDER_MOVE."
        <select name='new_of_cat_sn' style='width:120px;'>
        <option value=0>"._MD_TADUP_ROOT."</option>
        $option
        </select>
        <INPUT type='hidden' name='cat_sn' value='{$cat_sn}'>
        <INPUT type='hidden' name='op' value='new_of_cat_sn'>
        <INPUT type='submit' value='"._MD_TADUP_MOVE."'>
        </FORM>
        </td><td>
        <FORM action='{$_SERVER['PHP_SELF']}' method='POST'>
        <img src='images/folder_rename.png' alt='"._MD_TADUP_FOLDER_RENAME."' title='"._MD_TADUP_FOLDER_RENAME."' border='0' height='16' width='16' hspace=4 align='absmiddle'>"._MD_TADUP_FOLDER_RENAME."
        <input type='text' name='new_cat_title' size=12 value='{$cat['cat_title']}'>
        <INPUT type='hidden' name='cat_sn' value='{$cat_sn}'>
        <INPUT type='hidden' name='op' value='new_cat_title'>
        <INPUT type='submit' value='"._TAD_SUBMIT."'>
        </FORM>
        </td>
         </tr>
        ";
      }
    }


  //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
  $cat_title=(empty($cat_sn))?_MD_TADUP_ROOT:$cat['cat_title'];

  $admin=($isAdmin)?"<img src='images/stop.png' alt='".sprintf(_MD_TADUP_FOLDER_DEL,$cat_title)."' title='".sprintf(_MD_TADUP_FOLDER_DEL,$cat_title)."' border='0' height='16' width='16' hspace=4 align='absmiddle'><a href=\"javascript:delete_catalog_func({$cat_sn},{$cat['of_cat_sn']});\">".sprintf(_MD_TADUP_FOLDER_DEL,$cat_title)."</a>":"";

  $main="
  <table style='width:auto' id='t'>
  <tr>
  <td valign='top'>
  {$admin}
  </td>
  $move_tool
  </tr>
  $tool
  </table>";
  return $main;
}



//更新catalog某一筆資料
function update_catalog($col_name="",$col_val="",$cat_sn=""){
  global $xoopsDB;
  $sql = "update ".$xoopsDB->prefix("tad_uploader")." set  $col_name = '{$col_val}' where cat_sn='$cat_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR3);
  return $cat_sn;
}



//更新catalog_data現有資料
function update_data($cat_sn=""){
  global $xoopsDB;

  foreach($_POST['cf_desc'] as $cfsn => $cf_desc){
    $cfsn=update_catalog_file($cfsn,"cf_desc",$cf_desc);
  }

  return $cdsn;
}


//找出路徑
function find_path($cat_sn=""){
  global $xoopsDB;
  if(empty($cat_sn))return;
  $sql = "select cat_sn,cat_title,of_cat_sn from ".$xoopsDB->prefix("tad_uploader")." where cat_sn='$cat_sn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR1);

  while(list($cat_sn,$cat_title,$of_cat_sn)=$xoopsDB->fetchRow($result)){
    $cat_sn_array=$cat_sn."'>".$cat_title;
    if(!empty($of_cat_sn)){
      $cat_sn_array.="||".find_path($of_cat_sn);
    }
  }

  return $cat_sn_array;
}

//取得目前所在路徑
function get_path($cat_sn=""){
  $cat_sn_str=find_path($cat_sn);
  $cat_sn_array=explode("||",$cat_sn_str);
  $path="";
  for($i=sizeof($cat_sn_array);$i>=0;$i--){
    if(empty($cat_sn_array[$i]))continue;
    $path.="/ <a href='{$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn_array[$i]}</a>";
  }
  return $path;
}



//搬移檔案
function movefile($select_files=array(),$new_cat_sn=""){
  global $col_intf;
  if(empty($select_files)){
    redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_NO_SELECTED_FILE);
  }

  foreach($select_files as $cfsn=>$cf_name){
    update_catalog_file($cfsn,"cat_sn",$new_cat_sn);
  }
}


//建立資料夾
function create_folder($cat_title="",$of_cat_sn=""){
  global $xoopsDB,$xoopsUser;
  if($xoopsUser){
    $uid=$xoopsUser->getVar('uid');
  }
  //$cat_max_sort=get_cat_max_sort($of_cat_sn);

  $sql = "insert into ".$xoopsDB->prefix("tad_uploader")." (cat_title,cat_enable,uid,of_cat_sn,cat_share,cat_sort)
  values('{$cat_title}','1','{$uid}','{$of_cat_sn}','1','0')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR4);

  //取得最後新增資料的流水編號
  $cat_sn=$xoopsDB->getInsertId();

  return $cat_sn;
}



function set_group_power(){
  include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
  $form = new XoopsThemeForm(_MD_TADUP_SET_FOLDER_POWER, 'form', $SERVER['PHP_SELF'], 'post', true);
  $form->addElement(new XoopsFormSelectGroup(_MD_TADUP_CAN_ACCESS_GROUPS2, "catalog", true,"", 5, true));
  $form->addElement(new XoopsFormSelectGroup(_MD_TADUP_CAN_UPLOADS_GROUPS, "catalog_up", true,"", 5, true));
  $form->addElement(new XoopsFormRadioYN(_MD_TADUP_IS_SHARE, 'cat_share', true));
  $form->addElement(new XoopsFormHidden('op', 'save_power'));
  $form->addElement(new XoopsFormHidden('cat_sn', $_GET['of_cat_sn']));
  $form->addElement(new XoopsFormButton('', '', _TAD_SUBMIT, 'submit'));
  $main=$form->render();
  return $main;
}



function save_power(){
  global $xoopsModule;
  $gperm_modid=$xoopsModule->getVar('mid');
  $groupperm_handler =& xoops_gethandler('groupperm');
  foreach($_POST['catalog'] as $gperm_groupid){
    $groupperm_handler->addRight('catalog', $_POST['cat_sn'], $gperm_groupid, $gperm_modid);
  }
  foreach($_POST['catalog_up'] as $gperm_groupid){
    $groupperm_handler->addRight('catalog_up', $_POST['cat_sn'], $gperm_groupid, $gperm_modid);
  }
  update_catalog("cat_share",$_POST['cat_share'],$_POST['cat_sn']);
}

//更新目錄人氣值
function update_catalog_count($cat_sn=""){
  global $xoopsDB;
  $sql = "update ".$xoopsDB->prefix("tad_uploader")." set  cat_count = cat_count+1 where cat_sn='{$cat_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADUP_DB_ERROR3);
  return $cat_sn;
}



/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$cat_sn=(empty($_REQUEST['cat_sn']))?0:intval($_REQUEST['cat_sn']);
$of_cat_sn=(empty($_REQUEST['of_cat_sn']))?0:intval($_REQUEST['of_cat_sn']);
$cfsn=(empty($_REQUEST['cfsn']))?0:intval($_REQUEST['cfsn']);
$new_of_cat_sn=(empty($_REQUEST['new_of_cat_sn']))?0:intval($_REQUEST['new_of_cat_sn']);
$new_cat_sn=(empty($_REQUEST['new_cat_sn']))?0:intval($_REQUEST['new_cat_sn']);

switch($op){

  case "update_data":
  update_data($cat_sn);
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
  break;

  case "new_of_cat_sn":
  update_catalog("of_cat_sn",$new_of_cat_sn,$cat_sn);
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$new_of_cat_sn}");
  break;

  case "new_cat_title":
  update_catalog("cat_title",$_POST['new_cat_title'],$cat_sn);
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
  break;

  case "dlfile":
  $files_sn=dlfile($cfsn);
  $TadUpFiles->add_file_counter($files_sn,true);
  exit;
  break;


  case"del_file":
  del_file($cfsn);
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$of_cat_sn}");
  break;

  case "save_files":
    $uid=$xoopsUser->uid();
    $cat_sn=add_tad_uploader();

    if($_POST['all_selected']=='all_del'){
      delfile($_POST['select_files']);
    }elseif($_POST['all_selected']=='all_move'){
      movefile($_POST['select_files'],$_POST['new_cat_sn']);
      $cat_sn=$_POST['new_cat_sn'];
    }
    header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
  break;

  case "create_folder":
  $cat_sn=create_folder($_POST['cat_title'],$of_cat_sn);
  header("location: {$_SERVER['PHP_SELF']}?op=set_group_power&of_cat_sn={$cat_sn}");
  break;


  case "set_group_power":
  $main=set_group_power();
  break;

  case "save_power":
  save_power();
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
  break;

  case "save_cat_desc":
  update_catalog("cat_desc",$_POST['cat_desc'],$cat_sn);
  header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
  break;


  //刪除資料
  case "delete_catalog";
  delete_catalog($cat_sn);
  header("location:{$_SERVER['PHP_SELF']}?of_cat_sn={$of_cat_sn}");
  break;


  default:
  list_all_data($of_cat_sn);
  break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
