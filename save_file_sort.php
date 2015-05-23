<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-02-28
// $Id: cate.php,v 1.4 2008/05/05 03:21:31 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "header.php";
<<<<<<< HEAD
$updateRecordsArray 	= $_POST['tr'];
=======
$updateRecordsArray   = $_POST['tr'];
>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
  $sql="update ".$xoopsDB->prefix("tad_uploader_file")." set `cf_sort`='{$sort}' where `cfsn`='{$recordIDValue}'";
<<<<<<< HEAD
  $xoopsDB->queryF($sql) or die("Save Sort Fail! (".date("Y-m-d H:i:s").")");
  $sort++;
}

echo "Save Sort OK! (".date("Y-m-d H:i:s").")";
=======
  $xoopsDB->queryF($sql) or die("Save Sort Fail! (".date("Y-m-d H:i:s",xoops_getUserTimestamp(time())).")");
  $sort++;
}

echo "Save Sort OK! (".date("Y-m-d H:i:s",xoops_getUserTimestamp(time())).")";
>>>>>>> 1aaca4ddf96329f2477c5a3f1d61a4fe462bb717
?>
