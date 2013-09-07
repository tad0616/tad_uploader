<?php
include_once "header.php";
include_once XOOPS_ROOT_PATH."/modules/tadtools/jupload/jupload.php";
if(sizeof($upload_powers)<=0 or !$xoopsUser){
  die(_MD_TADUP_NO_EDIT_POWER);
}
/*-----------function區--------------*/
//$video_ext=implode($ok_video_ext,"/");
//$image_ext=implode($ok_image_ext,"/");

$appletParameters = array(
  'maxFileSize' => '2G',
  'postURL' => XOOPS_URL.'/modules/tad_uploader/jupload.php',
  'archive' => XOOPS_URL.'/modules/tadtools/jupload/wjhk.jupload.jar',
  'afterUploadURL' => XOOPS_URL.'/modules/tad_uploader/uploads.php?op=to_batch_upload',
  //'allowedFileExtensions' => $video_ext."/".$image_ext,
  'sendMD5Sum' => 'true',
  'showLogWindow' => 'false',
  'debugLevel' => 99
);

$classParameters = array(
  'demo_mode' => false,
  'allow_subdirs' => true,
  'destdir' => _TAD_UPLOADER_BATCH_DIR
);
$juploadPhpSupportClass = new JUpload($appletParameters, $classParameters);
?>

<!--JUPLOAD_APPLET-->
