<?php
include_once "header.php";
include_once "language/{$xoopsConfig['language']}/batch.php";

$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$cat_sn=(isset($_REQUEST['cat_sn']))?intval($_REQUEST['cat_sn']) : 0;

switch($op){
<<<<<<< HEAD
	case "import":
	$cat_sn=tad_uploader_batch_import();
	header("location:index.php?of_cat_sn=$cat_sn");
	break;

	default:
  echo tad_uploader_batch_upload_form();
	break;
=======
  case "import":
  $cat_sn=tad_uploader_batch_import();
  header("location:index.php?of_cat_sn=$cat_sn");
  break;

  default:
  echo tad_uploader_batch_upload_form($cat_sn);
  break;
>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717
}



//批次上傳表單
<<<<<<< HEAD
function tad_uploader_batch_upload_form(){
	global $xoopsDB,$xoopsModuleConfig,$ok_video_ext,$ok_image_ext;
  //$cate_select=get_cata_select("",$cat_sn);
  $cate_select=get_tad_uploader_cate_option(0,0,$cat_sn,1,false);

	$i=0;

  if ($dh = opendir(_TAD_UPLOADER_BATCH_DIR)) {
    while (($file = readdir($dh)) !== false) {
      if(strlen($file)<=2)continue;
      $f=explode(".",$file);
			$filename=$f[0];
      $ext=strtolower($f[1]);
			$tr.="<tr>
			<td>
      <input type='checkbox' name='files[$filename]' value='{$file}' checked>{$file}</td>
			<td class='col'><input type='text' name='cf_desc[$filename]' value='{$file}' size=40></td>
			</tr>\n";
=======
function tad_uploader_batch_upload_form($cat_sn=""){
  global $xoopsDB,$xoopsModuleConfig,$ok_video_ext,$ok_image_ext,$isAdmin;

  $cate_select=get_tad_uploader_cate_option(0,0,$cat_sn,1,false);

  $i=0;
  $tr="";
  if ($dh = opendir(_TAD_UPLOADER_BATCH_DIR)) {
    while (($file = readdir($dh)) !== false) {
      if(strlen($file)<=2)continue;

      $file=auto_charset($file,'web');

      $f=explode(".",$file);
      foreach($f as $part){
        $ext=strtolower($part);
      }
      $len=(strlen($ext) + 1) * -1;
      $filename=substr($file,0,$len);

      $tr.="
      <tr>
        <td><label class='checkbox'><input type='checkbox' name='files[$filename]' value='{$file}' checked>{$file}</label></td>
        <td><input type='text' name='cf_desc[$filename]' value='{$filename}' class='span12'></td>
      </tr>\n";
>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717
    }
    closedir($dh);
  }

<<<<<<< HEAD
  $cate_select=to_utf8($cate_select);

	$main="
	<p>"._MA_TADUP_BATCH_UPLOAD_TO."<span style='color:red;'>"._TAD_UPLOADER_BATCH_DIR."</span></p>
  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <table class='form_tbl' style='width:100%;'>
	<tr><td class='title'>"._MD_TADUP_SELECT_FOLDER."</td>
	<td class='col'><select name='cat_sn' size=1>
	$cate_select
	</select>
	"._MD_TADUP_CREATE_NEW_FOLDER."<input type='text' name='new_cat_sn' size='10'></td></tr>
  </table>
  <table class='form_tbl'>
  <tr>
  <th>"._MD_TADUP_FILE_NAME."</th>
	<th>"._MD_TADUP_FILE_DESC."</th>
  </tr>
	$tr
  <input type='hidden' name='op' value='import'>
  <tr><td colspan=3 class='bar'><input type='submit' value='"._MA_BATCH_SAVE."'></td></tr>
  </table>
  </form>";

	//$main=div_3d(_MA_INPUT_FORM,$main);

	return $main;
=======
  $root=$isAdmin?"<option value=''>"._MD_TADUP_ROOT."</div>":"";


  $main="
  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <div class='alert alert-success'>
    "._MA_TADUP_BATCH_UPLOAD_TO."<span style='color:red;'>"._TAD_UPLOADER_BATCH_DIR."</span>
  </div>

  <div class='row-fluid'>
    <label class='span2'>"._MD_TADUP_SELECT_FOLDER."</label>
    <div class='span4'>
      <select name='cat_sn' size=1 class='span12'>
        $root
        $cate_select
      </select>
    </div>

    <label class='span2'>"._MD_TADUP_CREATE_NEW_FOLDER."</label>
    <div class='span4'>
      <input type='text' name='new_cat_sn' class='span12'>
    </div>
  </div>


  <table class='table table-striped table-hover'>
  <tr>
  <th>"._MD_TADUP_FILE_NAME."</th>
  <th>"._MD_TADUP_FILE_DESC."</th>
  </tr>
  $tr
  </table>

  <div class='row-fluid text-center'>
  <input type='hidden' name='op' value='import'>
    <button type='submit' class='btn btn-primary'>"._MA_BATCH_SAVE."</button>
  </div>
  </form>";

  return $main;
>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717
}


//批次匯入
function tad_uploader_batch_import(){
<<<<<<< HEAD
	global $xoopsDB,$xoopsUser,$xoopsModuleConfig;
	
	if(!empty($_POST['new_cat_sn'])){
    $cat_sn=add_catalog("",$_POST['new_cat_sn'],"","1",$_POST['cat_sn'],$_POST['cat_add_form']);
	}else{
		$cat_sn=$_POST['cat_sn'];
	}
	
	$uid=$xoopsUser->getVar('uid');
  $uid_name=XoopsUser::getUnameFromId($uid,1);
  //$now=xoops_getUserTimestamp(time());

	set_time_limit(0);
	foreach($_POST['files'] as $filename => $file_path){
	  if(empty($file_path))continue;
	  $file_src=_TAD_UPLOADER_BATCH_DIR."/{$file_path}";
	  $type=mime_content_type($file_src);
	  $cf_sort=get_file_max_sort($cat_sn);
	  $size=filesize($file_src);

    $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
		$sql = "insert into ".$xoopsDB->prefix("tad_uploader_file")." (cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,up_date,cf_sort)
  	values('{$cat_sn}','{$uid}','{$file_path}','{$_POST['cf_desc'][$filename]}','{$type}','{$size}','{$now}','{$cf_sort}')";
		$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		//取得最後新增資料的流水編號
		$cfsn=$xoopsDB->getInsertId();

		if(rename($file_src,_TAD_UPLOADER_DIR."/{$cfsn}_{$file_path}")){
		  unlink($file_src);
		}
	}
	
	//刪除其他多餘檔案
	rrmdir(_TAD_UPLOADER_BATCH_DIR);
	
	return $cat_sn;
}


//輸出為UTF8
function to_utf8($buffer=""){
	if(_CHARSET=="UTF-8"){
		return $buffer;
	}else{
  	$buffer=(!function_exists("mb_convert_encoding"))?iconv("Big5","UTF-8",$buffer):mb_convert_encoding($buffer,"UTF-8","Big5");
  	return $buffer;
	}
}

=======
  global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$TadUpFiles;

  if(!empty($_POST['new_cat_sn'])){
    $cat_sn=add_catalog("",$_POST['new_cat_sn'],"","1",$_POST['cat_sn'],$_POST['cat_add_form']);
  }else{
    $cat_sn=$_POST['cat_sn'];
  }

  $uid=$xoopsUser->getVar('uid');
  $uid_name=XoopsUser::getUnameFromId($uid,1);
  //$now=xoops_getUserTimestamp(time());

  set_time_limit(0);
  foreach($_POST['files'] as $filename => $file_path){
    if(empty($file_path))continue;
    $file_src=_TAD_UPLOADER_BATCH_DIR."/{$file_path}";

    $file_src=auto_charset($file_src,'os');

    $type=mime_content_type($file_src);
    $cf_sort=get_file_max_sort($cat_sn);
    $size=filesize($file_src);

    $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $sql = "insert into ".$xoopsDB->prefix("tad_uploader_file")." (cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,up_date,cf_sort)
    values('{$cat_sn}','{$uid}','{$file_path}','{$_POST['cf_desc'][$filename]}','{$type}','{$size}','{$now}','{$cf_sort}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    //取得最後新增資料的流水編號
    $cfsn=$xoopsDB->getInsertId();

    $new_filename=_TAD_UPLOADER_DIR."/{$cfsn}_{$file_path}";

    //$file_src=auto_charset($file_src,'web');
    //$new_filename=auto_charset($new_filename,'web');

    //複製匯入單一檔案：
    $TadUpFiles->set_dir('subdir',"/user_{$uid}");
    $TadUpFiles->set_col("cfsn",$cfsn);
    $TadUpFiles->import_one_file($file_src,$new_filename,NULL,NULL,NULL,$_POST['cf_desc'][$filename] ,true ,true);

    unlink($file_src);

  }

  //刪除其他多餘檔案
  rrmdir(_TAD_UPLOADER_BATCH_DIR);

  return $cat_sn;
}



>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717
?>
