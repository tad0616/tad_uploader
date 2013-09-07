<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: main.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //

define("_MA_TADUP_UPLOAD","Upload");

//index.php
define("_MD_TADUP_NO_ACCESS_POWER","You have no permission to read this folder.");
define("_MD_TADUP_FILE_DESC","Description");
define("_MD_TADUP_DEL_CONFIRM","Delete this folder? This operation will delete all files in thie folder!");
define("_MD_TADUP_LIST_ALL_FILES","Display all files");
define("_MD_TADUP_NOW_PATH","Path：");
define("_MD_TADUP_ROOT","Root");
define("_MD_TADUP_PRE_FOLDER","Back");
define("_MD_TADUP_SWITCH_LIST_MODE","Display Mode：");
define("_MD_TADUP_DB_ERROR1","Failed to get tad_uploader data.");
define("_MD_TADUP_FILE","File");
define("_MD_TADUP_DB_ERROR2","Failed to get tad_uploader_file data.");
define("_MD_TADUP_PAGE_BAR1","(Total %s) Page %s ：");

define("_MD_TADUP_SELECTED_DEL","Delete selected file");
define("_MD_TADUP_SELECTED_EDIT","Edit selected file");
define("_MD_TADUP_SELECTED_MOVETO","Move selected file to：");
define("_MD_TADUP_MOVE","Move");
define("_MD_TADUP_FILE_NAME","Filename");
define("_MD_TADUP_FILE_DATE","Date");
define("_MD_TADUP_FILE_TYPE","Type");
define("_MD_TADUP_FILE_SIZE","Size");
define("_MD_TADUP_FILE_COUNTER","Count");
define("_MD_TADUP_ADMIN","Control Panel");
define("_MD_TADUP_CREATE_FOLDER","Create a new folder：");
define("_MD_TADUP_NEW_FOLDER","new_folder");
define("_MD_TADUP_SUBMIT","Submit");
define("_MD_TADUP_FOLDER_MOVE","Move this folder to：");
define("_MD_TADUP_FOLDER_RENAME","Rename this folder：");
define("_MD_TADUP_FOLDER_DEL","Delete \"%s\" folder");
define("_MD_TADUP_OPEN_UPLOADER","Display uploader");
define("_MD_TADUP_DESC_EMPTY","No description, just key in now.");
define("_MD_TADUP_SAVE","Save");
define("_MD_TADUP_ROOT_DESC","No rename or move for Root; Only administrator is authorized to create folders and upload files.");
define("_MD_TADUP_EMPTY_DESC","No description.");
define("_MD_TADUP_CAN_ACCESS_GROUPS","■ Visible to groups：");
define("_MD_TADUP_CAN_UPLOAD_GROUPS","■ Uploadable to groups：");
define("_MD_TADUP_NO_SHARE_FOLDER","■ Private folder");
define("_MD_TADUP_SUBFOLDER_COUNT","■ Subfolder：");
define("_MD_TADUP_FILES_COUNT","■ File：");
define("_MD_TADUP_CREATER","■ Creater：");
define("_MD_TADUP_DB_ERROR3","Failed to update tad_uploader data");
define("_MD_TADUP_CANT_GET_FILE","Unable to download %s file to %s!");
define("_MD_TADUP_CANT_ADD_FILE","Failed to add new file （%s） data!");
define("_MD_TADUP_NO_FILE","File doesn't exist!");
define("_MD_TADUP_NO_FILE_DATA","No file data!");
define("_MD_TADUP_NO_SELECTED_FILE","No selected file(s)");
define("_MD_TADUP_MODIFY_INTERFACE","Edit file in folder - %s");
define("_MD_TADUP_DB_ERROR4","Failed to replace tad_uploader data");
define("_MD_TADUP_SET_FOLDER_POWER","Folder permission");
define("_MD_TADUP_CAN_ACCESS_GROUPS2","<b>Visible</b> to groups");
define("_MD_TADUP_CAN_UPLOADS_GROUPS","<b>Uploadable</b> to groups");
define("_MD_TADUP_IS_SHARE","Share（No means a private folder visible and uploadable only for creater.）");
define("_MD_TADUP_FILE_SORT","▲");
define("_MD_TADUP_FILE_SORT_DESC","▼");

//function
define("_MD_TADUP_CANT_FIND","No Found");
define("_MD_TADUP_MENU","Menu");
define("_MD_TADUP_PREV","Previous");
define("_MD_TADUP_NEXT","Next");
define("_MD_TADUP_FIRST","First");
define("_MD_TADUP_LAST","End");
define("_MD_TADUP_PREV_PAGE","Previous %s Page(s)");
define("_MD_TADUP_NEXT_PAGE","Next %s Page(s)");
define("_MD_TADUP_NO_FOLDER_NAME","No Folder Name");
define("_MD_TADUP_CANT_CREATE_FOLDER","Failed to create new folder");
define("_MD_TADUP_CANT_FIND_FILE","No Found File(s)");
define("_MD_TADUP_NO_FILE_NAME","No Filename!");
define("_MD_TADUP_DB_ERROR5","Failed to replace tad_uploader_file data");
define("_MD_TADUP_DB_ERROR6","Failed to update tad_uploader_file data");
define("_MD_TADUP_DB_ERROR7","Failed to delete tad_uploader_file data");
define("_MD_TADUP_UPLOAD_FROM_HD","Upload from Hard Drive：");
define("_MD_TADUP_GET_FROM_URL","Get file from URL：");
define("_MD_TADUP_GET_FROM_URL_DESC","（It will download file to your host instead of links.）");
define("_MD_TADUP_PHP_VERSION","■ PHP Version：");
define("_MD_TADUP_ALLOW_URL_FOPEN","■ allow_url_fopen setting：");
define("_MD_TADUP_MAX_FILESIZE","■ Maximum file size：");
define("_MD_TADUP_POST_MAX_SIZE","■ Miximum post size：");
define("_MD_TADUP_MAX_EXECUTION_TIME","■ Maximum execution time：");
define("_MD_TADUP_PRE_SET","（Original: %s）");
define("_MD_TADUP_SECOND","Second(s)");
define("_MD_TADUP_README1","■ Just choose one of 「Upload from Hard Drive」 and 「Get file from URL」 two options.");
define("_MD_TADUP_NO_EDIT_POWER","You have NO permission to edit or upload");
define("_MD_TADUP_UPLOAD_INTERFACE","Uploader of 『%s』");
define("_MD_TADUP_NO_POWER","You have NO permission for this operation.");
define("_MD_TADUP_NO_LOGIN","You have to login first for this operation.");
define("_MD_TADUP_CANT_DELETE1","Unable to delete it because of %s subfolder(s) and %s file(s) left.");
define("_MD_TADUP_DB_ERROR8","Failed to delete tad_uploader data");

define("_MA_TADUP_ALL_OK","All Groups");

define("_MA_TADUP_UPLOAD_ONE","Upload File");
define("_MI_TADUP_JAVA_UPLOAD","java Upload");
define("_MI_TADUP_BATCH_UPLOAD","Batch Upload");
define("_MA_TADUP_OF_TADUP_SN","Select Folder");
define("_MA_TADUP_NEW_TADUP_SN","Create A New Folder:");
define("_MA_TADUP_UPLOAD","Upload");
define("_MA_TADUP_LINK","Link to");

define("_MD_TADUP_UPDATE_DATE","Modify Date");
define("_MD_TADUP_DONT_UPDATE_DATE","NO");
define("_MD_TADUP_UPDATE_TO_NEW_DATE","YES");
define("_MD_TADUP_ROOT","--");
?>
