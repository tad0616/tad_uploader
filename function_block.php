<?php
//檢查有無權限
if (!function_exists('check_up_power')) {
    function check_up_power($kind = 'catalog', $cat_sn = '')
    {
        global $xoopsUser;

        //取得目前使用者的群組編號
        if ($xoopsUser) {
            $uid = $xoopsUser->uid();
            $groups = $xoopsUser->getGroups();
        } else {
            $uid = 0;
            $groups = XOOPS_GROUP_ANONYMOUS;
        }

        //若沒分享，則看看是否是自己的資料夾即可。
        $tad_uploader = get_tad_uploader($cat_sn);
        if (($tad_uploader['cat_share'] == '0' and $tad_uploader['uid'] != $uid) and !$_SESSION['tad_upload_adm']) {
            return false;
        }

        //取得模組編號
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule = $moduleHandler->getByDirname('tad_uploader');
        $module_id = $xoopsModule->mid();

        //取得群組權限功能
        $gpermHandler = xoops_getHandler('groupperm');

        //權限項目編號
        $perm_itemid = (int) $cat_sn;
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
}

//以流水號取得某目錄資料
if (!function_exists('get_tad_uploader')) {
    function get_tad_uploader($cat_sn = '')
    {
        global $xoopsDB;
        if (empty($cat_sn)) {
            return;
        }

        $sql = 'select * from ' . $xoopsDB->prefix('tad_uploader') . " where cat_sn='$cat_sn'";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);
        $data = $xoopsDB->fetchArray($result);

        return $data;
    }
}
