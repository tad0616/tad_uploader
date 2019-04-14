<?php
/**
 * Tad Uploader module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package      Tad Uploader
 * @since        2.5.0
 * @author       Tad
 * @version      $Id $
 **/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

defined('FRAMEWORKS_ART_FUNCTIONS_INI') || require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.ini.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/admin.php';

load_functions('admin');

if (!@require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/main.php') {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/main.php';
}
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
}
// if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/include/beforeheader.php")) {
//     require_once XOOPS_ROOT_PATH . "/modules/tadtools/include/beforeheader.php";
//     $GLOBALS['xoopsOption']['template_main'] = set_bootstrap();
// }
xoops_cp_header();

// Define Stylesheet and JScript
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/admin.css');
//$xoTheme->addScript("browse.php?Frameworks/jquery/jquery.js");
//$xoTheme->addScript("browse.php?modules/" . $xoopsModule->getVar("dirname") . "/js/admin.js");
