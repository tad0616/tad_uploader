<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: admin.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //


include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";

//power.php
define("_AM_TADUP_DB_ERROR1","無法取得tad_uploader資料");
define("_AM_TADUP_SET_ACCESS_POWER","請設定各個群組可讀取使用的資料夾");
define("_AM_TADUP_SET_UPLOAD_POWER","請設定各個群組可上傳的資料夾");

//index.php
define("_AM_TADUP_CREATE_FOLDER","建立新的資料夾");
define("_AM_TADUP_FOLDER_NAME","資料夾名稱");
define("_AM_TADUP_FOLDER_DESC","資料夾描述");
define("_AM_TADUP_FATHER_FOLDER","所屬資料夾");
define("_AM_TADUP_FOLDER_SORT","資料夾排序");
define("_AM_TADUP_ENABLE","是否啟用");
define("_AM_TADUP_SHARE","是否共享");
define("_AM_TADUP_SAVE","儲存");
define("_AM_TADUP_DB_ERROR2","無法取代tad_uploader中的資料");
define("_AM_TADUP_DB_ERROR3","無法取得分頁資料");
define("_AM_TADUP_PAGE_BAR1","共 %s 頁，目前在第 %s 頁：");
define("_AM_TADUP_FUNCTION","功能");
define("_AM_TADUP_DEL_CONFIRM","確定要刪除此資料夾？底下的所有資料夾及檔案都會被刪除喔！");
define("_AM_TADUP_LIST_ALL_FILES","列出所有資料夾中文件","共 %s 筆資料");
define("_AM_TADUP_AUTHOR","建立者");
define("_AM_TADUP_FILE_COUNTER","人氣值");
define("_AM_TADUP_EDIT","編輯");
define("_AM_TADUP_DEL","刪除");

define("_MD_TADUP_MENU","選單");
define("_MD_TADUP_PREV","上一頁");
define("_MD_TADUP_NEXT","下一頁");
define("_MD_TADUP_FIRST","第一頁");
define("_MD_TADUP_LAST","最後頁");
define("_MD_TO_MOD","回使用者介面");


define("_MI_TADUP_ADMENU1", "資料夾設定");
define("_MI_TADUP_ADMENU2", "細部權限設定");

define("_MD_TADUP_CAN_ACCESS_GROUPS2","可<b>讀取</b>群組");
define("_MD_TADUP_CAN_UPLOADS_GROUPS","可<b>上傳</b>群組");
define("_MA_TADUP_ALL_OK","所有群組");
?>
