<?php
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_uploader_adm_power.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/*-----------function區--------------*/
$module_id = $xoopsModule->getVar('mid');

$jquery_path = get_jquery(true); //TadTools引入jquery ui
$xoopsTpl->assign('jquery_path', $jquery_path);

//抓取所有資料夾

$item_list = [];
$sql = 'SELECT cat_sn,cat_title FROM ' . $xoopsDB->prefix('tad_uploader');
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _MA_TADUP_DB_ERROR1);
while (false !== (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result))) {
    $item_list[$cat_sn] = $cat_title;
}

$perm_desc = '';
$formi = new XoopsGroupPermForm('', $module_id, 'catalog', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main1 = $formi->render();
$xoopsTpl->assign('main1', $main1);

$formi = new XoopsGroupPermForm('', $module_id, 'catalog_up', $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $formi->addItem($item_id, $item_name);
}

$main2 = $formi->render();
$xoopsTpl->assign('main2', $main2);

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
