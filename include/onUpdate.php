<?php

use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_uploader\Update;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tad_uploader\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_tad_uploader()
{
    global $xoopsDB;

    if (Update::chk_chk1()) {
        Update::go_update1();
    }

    if (!Update::chk_chk2()) {
        Update::go_update2();
    }

    if (!Update::chk_chk3()) {
        Update::go_update3();
    }

    if (!Update::chk_chk4()) {
        Update::go_update4();
    }

    if (!Update::chk_chk5()) {
        Update::go_update5();
    }

    if (Update::chk_chk6()) {
        Update::go_update6();
    }

    if (Update::chk_chk7()) {
        Update::go_update7();
    }

    Update::chk_tad_uploader_block();
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_uploader_batch');

    //新增檔案欄位
    if (Update::chk_fc_tag()) {
        Update::go_fc_tag();
    }

    return true;
}
