<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: admin.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //

include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";

//power.php
define("_AM_TADUP_DB_ERROR1","Failed to get tad_uploader data");
define("_AM_TADUP_SET_ACCESS_POWER","Visible folders for each group");
define("_AM_TADUP_SET_UPLOAD_POWER","Uploadable folders for each group");

//index.php
define("_AM_TADUP_CREATE_FOLDER","Create A New Folder");
define("_AM_TADUP_FOLDER_NAME","Folder Name");
define("_AM_TADUP_FOLDER_DESC","Description");
define("_AM_TADUP_FATHER_FOLDER","Father Folder");
define("_AM_TADUP_FOLDER_SORT","Priority");
define("_AM_TADUP_ENABLE","Status");
define("_AM_TADUP_SHARE","Share");
define("_AM_TADUP_SAVE","Save");
define("_AM_TADUP_DB_ERROR2","Failed to replace tad_uploader data");
define("_AM_TADUP_DB_ERROR3","Failed to get page data");
define("_AM_TADUP_PAGE_BAR1","(Total %s) Page %s：");
define("_AM_TADUP_FUNCTION","Function");
define("_AM_TADUP_DEL_CONFIRM","Delete this folder? This operation will delete all files in thie folder!");
define("_AM_TADUP_LIST_ALL_FILES","Display all files","%s file(s) in total");
define("_AM_TADUP_AUTHOR","Creater");
define("_AM_TADUP_FILE_COUNTER","Count");
define("_AM_TADUP_EDIT","Edit");
define("_AM_TADUP_DEL","Delete");

define("_MD_TADUP_MENU","Menu");
define("_MD_TADUP_PREV","Previous");
define("_MD_TADUP_NEXT","Next");
define("_MD_TADUP_FIRST","First");
define("_MD_TADUP_LAST","End");
define("_MD_TO_MOD","Back to Module");

define("_MI_TADUP_ADMENU1", "Folder");
define("_MI_TADUP_ADMENU2", "Permission");

define("_MD_TADUP_CAN_ACCESS_GROUPS2","<b>Visible</b> to groups");
define("_MD_TADUP_CAN_UPLOADS_GROUPS","<b>Uploadable</b> to groups");
define("_MA_TADUP_ALL_OK","All Groups");
?>
