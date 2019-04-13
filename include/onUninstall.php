<?php

function xoops_module_uninstall_tad_uploader(&$module)
{
    global $xoopsDB;
    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tad_uploader', XOOPS_ROOT_PATH . "/uploads/tad_uploader_bak_{$date}");
    rename(XOOPS_ROOT_PATH . '/uploads/tad_uploader_batch', XOOPS_ROOT_PATH . "/uploads/tad_uploader_batch_bak_{$date}");

    return true;
}
