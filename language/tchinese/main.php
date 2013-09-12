<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: main.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //

define("_MA_TADUP_UPLOAD","檔案上傳");

//index.php
define("_MD_TADUP_NO_ACCESS_POWER","您沒有讀取此資料夾之權限");
define("_MD_TADUP_FILE_DESC","檔案說明");
define("_MD_TADUP_DEL_CONFIRM","確定要刪除此目錄？連同底下的檔案及目錄都會全部刪除喔！");
define("_MD_TADUP_LIST_ALL_FILES","列出所有資料夾中文件");
define("_MD_TADUP_NOW_PATH","目前路徑：");
define("_MD_TADUP_ROOT","根目錄");
define("_MD_TADUP_PRE_FOLDER","回上一層");
define("_MD_TADUP_SWITCH_LIST_MODE","顯示切換：");
define("_MD_TADUP_DB_ERROR1","無法取得tad_uploader資料");
define("_MD_TADUP_FILE","檔案");
define("_MD_TADUP_DB_ERROR2","無法取得tad_uploader_file資料");
define("_MD_TADUP_PAGE_BAR1","共 %s 頁，目前在第 %s 頁：");

define("_MD_TADUP_SELECTED_DEL","將勾選的檔案刪除");
define("_MD_TADUP_SELECTED_EDIT","編輯勾選的檔案");
define("_MD_TADUP_SELECTED_MOVETO","將勾選的檔案搬移到：");
define("_MD_TADUP_MOVE","搬移");
define("_MD_TADUP_FILE_NAME","檔案名稱");
define("_MD_TADUP_FILE_DATE","日期");
define("_MD_TADUP_FILE_TYPE","檔案類型");
define("_MD_TADUP_FILE_SIZE","大小");
define("_MD_TADUP_FILE_COUNTER","人氣");
define("_MD_TADUP_ADMIN","後台管理介面");
define("_MD_TADUP_CREATE_FOLDER","建立新資料夾：");
define("_MD_TADUP_NEW_FOLDER","新資料夾");
define("_MD_TADUP_SUBMIT","送出");
define("_MD_TADUP_FOLDER_MOVE","將此資料夾搬到：");
define("_MD_TADUP_FOLDER_RENAME","資料夾更名為：");
define("_MD_TADUP_FOLDER_DEL","刪除「%s」資料夾");
define("_MD_TADUP_OPEN_UPLOADER","開啟上傳介面");
define("_MD_TADUP_DESC_EMPTY","目前沒有任何說明，請輸入之。");
define("_MD_TADUP_SAVE","儲存");
define("_MD_TADUP_ROOT_DESC","根目錄無法更名及搬移，僅管理員可開設資料夾及上傳。");
define("_MD_TADUP_EMPTY_DESC","目前沒有任何說明");
define("_MD_TADUP_CAN_ACCESS_GROUPS","■ 可存取群組：");
define("_MD_TADUP_CAN_UPLOAD_GROUPS","■ 可上傳群組：");
define("_MD_TADUP_NO_SHARE_FOLDER","■ 不分享之資料夾，僅自己能存取。");
define("_MD_TADUP_SUBFOLDER_COUNT","■ 子資料夾數：");
define("_MD_TADUP_FILES_COUNT","■ 檔案數：");
define("_MD_TADUP_CREATER","■ 建立者：");
define("_MD_TADUP_DB_ERROR3","無法更新tad_uploader中的資料");
define("_MD_TADUP_CANT_GET_FILE","無法下載 %s 檔案到 %s！");
define("_MD_TADUP_CANT_ADD_FILE","無法新增檔案（%s）資料！");
define("_MD_TADUP_NO_FILE","檔案不存在！");
define("_MD_TADUP_NO_FILE_DATA","無檔案資料！");
define("_MD_TADUP_NO_SELECTED_FILE","沒有選擇的檔案");
define("_MD_TADUP_MODIFY_INTERFACE","『%s』資料夾修改介面");
define("_MD_TADUP_DB_ERROR4","無法取代tad_uploader中的資料");
define("_MD_TADUP_SET_FOLDER_POWER","設定資料夾權限");
define("_MD_TADUP_CAN_ACCESS_GROUPS2","可<b>讀取</b>此資料夾的群組");
define("_MD_TADUP_CAN_UPLOADS_GROUPS","可<b>上傳</b>此資料夾的群組");
define("_MD_TADUP_IS_SHARE","是否分享（若選「否」則該資料夾僅自己可見、可上傳）");
define("_MD_TADUP_FILE_SORT","▲");
define("_MD_TADUP_FILE_SORT_DESC","▼");
define("_MD_TADUP_ROOT","在根目錄下");
define("_MD_TADUP_CREAT_NEW_CATE","在左邊目錄下建立新目錄");


//function
define("_MD_TADUP_CANT_FIND","找不到");
define("_MD_TADUP_MENU","選單");
define("_MD_TADUP_PREV","上一頁");
define("_MD_TADUP_NEXT","下一頁");
define("_MD_TADUP_FIRST","第一頁");
define("_MD_TADUP_LAST","最後頁");
define("_MD_TADUP_PREV_PAGE","前 %s 頁");
define("_MD_TADUP_NEXT_PAGE","後 %s 頁");
define("_MD_TADUP_NO_FOLDER_NAME","無目錄名稱");
define("_MD_TADUP_CANT_CREATE_FOLDER","無法建立目錄");
define("_MD_TADUP_CANT_FIND_FILE","找不到檔案");
define("_MD_TADUP_NO_FILE_NAME","無檔名！");
define("_MD_TADUP_DB_ERROR5","無法取代tad_uploader_file中的資料");
define("_MD_TADUP_DB_ERROR6","無法更新tad_uploader_file中的資料");
define("_MD_TADUP_DB_ERROR7","無法刪除tad_uploader_file中的資料");
define("_MD_TADUP_UPLOAD_FROM_HD","從硬碟上傳檔案：");
define("_MD_TADUP_GET_FROM_URL","直接從網路取得：");
define("_MD_TADUP_GET_FROM_URL_DESC","（會直接從網路將該檔下載到您的主機中，非僅連結）");
define("_MD_TADUP_PHP_VERSION","■ PHP版本：");
define("_MD_TADUP_ALLOW_URL_FOPEN","■ 有無開放 allow_url_fopen：");
define("_MD_TADUP_MAX_FILESIZE","■ 上傳大小上限：");
define("_MD_TADUP_POST_MAX_SIZE","■ 表單大小上限：");
define("_MD_TADUP_MAX_EXECUTION_TIME","■ 傳輸時間上限：");
define("_MD_TADUP_PRE_SET","（原為 %s）");
define("_MD_TADUP_SECOND","秒");
define("_MD_TADUP_README1","■ 「從硬碟上傳檔案」或「直接從網路取得」二選一即可。");
define("_MD_TADUP_NO_EDIT_POWER","您沒有編輯或上傳權限");
define("_MD_TADUP_UPLOAD_INTERFACE","『%s』資料夾上傳介面");
define("_MD_TADUP_NO_POWER","您沒有權限進行此操作。");
define("_MD_TADUP_NO_LOGIN","您尚未登入，沒有權限進行此操作。");
define("_MD_TADUP_CANT_DELETE1","尚有 %s 子目錄 %s 檔案，故無法刪除。");
define("_MD_TADUP_DB_ERROR8","無法刪除tad_uploader中的資料");

define("_MA_TADUP_ALL_OK","所有群組");

define("_MA_TADUP_UPLOAD_ONE","單檔上傳");
define("_MI_TADUP_JAVA_UPLOAD","大檔上傳");
define("_MI_TADUP_BATCH_UPLOAD","批次上傳");
define("_MA_TADUP_OF_TADUP_SN","選擇目錄");
define("_MA_TADUP_NEW_TADUP_SN","建立新目錄：");
define("_MA_TADUP_UPLOAD","直接上傳");
define("_MA_TADUP_LINK","連結檔案");

define("_MD_TADUP_UPDATE_DATE","更新日期");
define("_MD_TADUP_DONT_UPDATE_DATE","維持原日期");
define("_MD_TADUP_UPDATE_TO_NEW_DATE","更新到目前日期");
define("_MD_TADUP_ROOT","不目錄");
?>
