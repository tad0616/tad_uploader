<?php

//---基本設定---//
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADUP_NAME;
$modversion['version']	= '3.1';
$modversion['description'] = _MI_TADUP_DESC;
$modversion['author'] = 'Tad(tad0616@gmail.com)';
$modversion['credits']	= "";
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']		= "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname']		= basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['release_date'] = '2014/02/17';
$modversion['module_website_url'] = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']=5.2;
$modversion['min_xoops']='2.5';

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';


//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "tad_uploader";
$modversion['tables'][2] = "tad_uploader_file";
$modversion['tables'][3] = "tad_uploader_dl_log";
$modversion['tables'][4] = "tad_uploader_files_center";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/tad_uploader_search.php";
$modversion['search']['func'] = "tad_uploader_search";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---安裝設定---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";


//---使用者主選單設定---//
$modversion['hasMain'] = 1;


//---樣板設定---//
$modversion['templates'] = array();
$i=1;
$modversion['templates'][$i]['file'] = 'tad_uploader_main.html';
$modversion['templates'][$i]['description'] = 'tad_uploader_main.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_uploader_uploads.html';
$modversion['templates'][$i]['description'] = 'tad_uploader_uploads.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_uploader_adm_main.html';
$modversion['templates'][$i]['description'] = 'tad_uploader_adm_main.html';

$i++;
$modversion['templates'][$i]['file'] = 'tad_uploader_adm_power.html';
$modversion['templates'][$i]['description'] = 'tad_uploader_adm_power.html';


//---區塊設定---//
$modversion['blocks'][1]['file'] = "tad_uploader_block_1.php";
$modversion['blocks'][1]['name'] = _MI_TADUP_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADUP_BDESC1;
$modversion['blocks'][1]['show_func'] = "tad_uploader_b_show_1";
$modversion['blocks'][1]['template'] = "tad_uploader_block_1.html";
$modversion['blocks'][1]['edit_func'] = "tad_uploader_b_edit_1";
$modversion['blocks'][1]['options'] = "10";


//---模組偏好設定---//
$i=1;
/*
$modversion['config'][$i]['name'] = 'page_show_num';
$modversion['config'][$i]['title'] = '_MI_PAGE_SHOW_NUM';
$modversion['config'][$i]['description'] = '_MI_PAGE_SHOW_NUM_DESC';
$modversion['config'][$i]['formtype'] = 'texbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = "16";

$i++;
*/
$modversion['config'][$i]['name'] = 'only_show_desc';
$modversion['config'][$i]['title'] = '_MI_ONLY_SHOW_DESC';
$modversion['config'][$i]['description'] = '_MI_ONLY_SHOW_DESC_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = "1";

$i++;
$modversion['config'][$i]['name'] = 'show_mode';
$modversion['config'][$i]['title'] = "_MI_SHOW_MODE";
$modversion['config'][$i]['description'] = '_MI_SHOW_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array(
											_MI_SHOW_MODE_MORE=>'more',
											_MI_SHOW_MODE_ICON=>'icon'
											);
$modversion['config'][$i]['default'] = 'more';

?>
