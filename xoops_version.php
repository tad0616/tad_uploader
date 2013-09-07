<?php
//  ------------------------------------------------------------------------ //
// 本模組由 吳弘凱(tad0616@gmail.com) 製作
// 製作日期：2008-02-06
// $Id: xoops_version.php,v 1.1 2008/05/14 01:27:37 tad Exp $
// ------------------------------------------------------------------------- //

//---基本設定---//
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADUP_NAME;
$modversion['version']	= '3.0';
$modversion['description'] = _MI_TADUP_DESC;
$modversion['author'] = 'Tad(tad0616@gmail.com)';
$modversion['credits']	= "";
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']		= "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname']		= basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['release_date'] = '2013/5/7';
$modversion['module_website_url'] = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TADUP_WEB;
$modversion['module_status'] = 'RC1';
$modversion['author_website_url'] = 'http://tad0616.net/';
$modversion['author_website_name'] = 'Tad';
$modversion['min_php']='5.2';
$modversion['min_xoops']='2.5';
$modversion['min_db'] = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TADUP_DESC;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'TWD';


//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "tad_uploader";
$modversion['tables'][2] = "tad_uploader_file";
$modversion['tables'][3] = "tad_uploader_dl_log";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";


//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---安裝設定---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";


//---使用者主選單設定---//
$modversion['hasMain'] = 1;
	

//---樣板設定---//
$modversion['templates'][1]['file'] = 'tu_main_tpl.html';
$modversion['templates'][1]['description'] = _MI_TADUP_TEMPLATE_DESC1;
$modversion['templates'][2]['file'] = 'tu_upload_tpl.html';
$modversion['templates'][2]['description'] = _MI_TADUP_TEMPLATE_DESC2;

//---區塊設定---//
$modversion['blocks'][1]['file'] = "catalog_block_1.php";
$modversion['blocks'][1]['name'] = _MI_TADUP_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADUP_BDESC1;
$modversion['blocks'][1]['show_func'] = "catalog_b_show_1";
$modversion['blocks'][1]['template'] = "catalog_block_1.html";
$modversion['blocks'][1]['edit_func'] = "catalog_b_edit_1";
$modversion['blocks'][1]['options'] = "10";


//---模組偏好設定---//
$modversion['config'][1]['name'] = 'page_show_num';
$modversion['config'][1]['title'] = '_MI_PAGE_SHOW_NUM';
$modversion['config'][1]['description'] = '_MI_PAGE_SHOW_NUM_DESC';
$modversion['config'][1]['formtype'] = 'texbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = "16";

$modversion['config'][2]['name'] = 'only_show_desc';
$modversion['config'][2]['title'] = '_MI_ONLY_SHOW_DESC';
$modversion['config'][2]['description'] = '_MI_ONLY_SHOW_DESC_DESC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = "1";

$modversion['config'][3]['name'] = 'show_mode';
$modversion['config'][3]['title'] = "_MI_SHOW_MODE";
$modversion['config'][3]['description'] = '_MI_SHOW_MODE_DESC';
$modversion['config'][3]['formtype'] = 'select';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['options'] = array(
											_MI_SHOW_MODE_MORE=>'more',
											_MI_SHOW_MODE_ICON=>'icon'
											);
$modversion['config'][3]['default'] = 'more';

?>
