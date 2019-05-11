<?php

use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
function xoops_module_install_tad_uploader(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_uploader');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_uploader_batch');

    return true;
}
