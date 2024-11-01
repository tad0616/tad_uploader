<?php

namespace XoopsModules\Tad_uploader;

use XoopsModules\Tadtools\Utility;

/**
 * Class Tools
 */
class Tools
{

    //檢查有無權限
    public static function check_up_power($kind = 'catalog', $cat_sn = '')
    {
        global $xoopsUser;

        //取得目前使用者的群組編號
        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $groups = $xoopsUser ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
        // die("{$kind}-{$cat_sn}");
        //若沒分享，則看看是否是自己的資料夾即可。
        $tad_uploader = self::get_tad_uploader($cat_sn);
        if (($tad_uploader['cat_share'] == '0' and $tad_uploader['uid'] != $uid) and !$_SESSION['tad_upload_adm']) {
            return false;
        }

        //取得模組編號
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule = $moduleHandler->getByDirname('tad_uploader');
        $module_id = $xoopsModule->mid();

        //取得群組權限功能
        $gpermHandler = xoops_getHandler('groupperm');

        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理

        if (empty($cat_sn)) {
            if ($kind === 'catalog') {
                return true;
            }
            if ($_SESSION['tad_upload_adm']) {
                return true;
            }
        } else {
            if ($gpermHandler->checkRight($kind, $cat_sn, $groups, $module_id) or $_SESSION['tad_upload_adm']) {
                return true;
            }
        }

        return false;
    }

//以流水號取得某目錄資料
    public static function get_tad_uploader($cat_sn = '')
    {
        global $xoopsDB;
        if (empty($cat_sn)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `cat_sn`=?';
        $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

        $data = $xoopsDB->fetchArray($result);

        return $data;
    }

}
