<?php
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_uploader_adm_power.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/*-----------function區--------------*/
$module_id = $xoopsModule->mid();

//抓取所有資料夾

$item_list = [];
$sql = 'SELECT cat_sn,cat_title FROM ' . $xoopsDB->prefix('tad_uploader');
$result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

while (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {
    $item_list[$cat_sn] = $cat_title;
}

$perm_desc = '';
$formi = new \XoopsGroupPermForm('', $module_id, 'catalog', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main1 = $formi->render();
$xoopsTpl->assign('main1', $main1);

$formi = new \XoopsGroupPermForm('', $module_id, 'catalog_up', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main2 = $formi->render();
$xoopsTpl->assign('main2', $main2);

$EasyResponsiveTabs = new EasyResponsiveTabs('#grouppermform-tabs');
$EasyResponsiveTabs->rander();
/*-----------秀出結果區--------------*/
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . "/modules/tadtools/css/xoops_adm{$_SEESION['bootstrap']}.css");
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/my-input.css');
require_once __DIR__ . '/footer.php';
