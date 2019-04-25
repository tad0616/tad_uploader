<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
include 'header.php';
$xoopsOption['template_main'] = 'tad_uploader_uploads.tpl';

if (count($upload_powers) <= 0 or empty($xoopsUser)) {
    redirect_header(XOOPS_URL . '/user.php', 3, _MD_TADUP_NO_EDIT_POWER);
}
include XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

function uploads_tabs($cat_sn = '', $cfsn = '')
{
    global $xoopsDB, $xoopsModuleConfig, $xoopsModule, $xoopsTpl, $interface_menu, $TadUpFiles;
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $jquery_path = Utility::get_jquery(true);
    $randStr = Utility::randStr();

    if ('to_batch_upload' === $_REQUEST['op']) {
        $to_batch_upload = '$tabs.tabs("select", last_tab);';
    }

    //抓取預設值
    if (!empty($cfsn)) {
        $DBV = get_tad_uploader_file($cfsn);
    } else {
        $DBV = [];
    }

    //預設值設定

    $cfsn = (!isset($DBV['cfsn'])) ? $cfsn : $DBV['cfsn'];
    $cat_sn = (!isset($DBV['cat_sn'])) ? $cat_sn : $DBV['cat_sn'];
    $cf_desc = (!isset($DBV['cf_desc'])) ? '' : $DBV['cf_desc'];
    $file_url = (!isset($DBV['file_url'])) ? '' : $DBV['file_url'];
    $cf_sort = (!isset($DBV['cf_sort'])) ? '' : $DBV['cf_sort'];
    //get_tad_uploader_cate_option($of_cat_sn=0,$level=0,$v="",$show_dot='1',$optgroup=true,$chk_view='1')
    $cate_select = get_tad_uploader_cate_option(0, 0, $cat_sn, 1, false);

    $op = (empty($cfsn)) ? 'insert_tad_uploader' : 'update_tad_uploader';

    if (empty($file_url)) {
        $hide = "$('#file_link').hide();";
        $selected_up = 'selected';
    } else {
        $hide = "$('#file_up').hide();";
        $selected_link = 'selected';
    }

    $upform = $TadUpFiles->upform(true, 'upfile', null, false);
    $xoopsTpl->assign('upform', $upform);

    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
    $xoopsTpl->assign('randStr', $randStr);
    $xoopsTpl->assign('jquery', $jquery_path);
    $xoopsTpl->assign('to_batch_upload', $to_batch_upload);

    $xoopsTpl->assign('hide', $hide);
    $xoopsTpl->assign('cate_select', $cate_select);
    $xoopsTpl->assign('file_url', $file_url);
    $xoopsTpl->assign('selected_up', $selected_up);
    $xoopsTpl->assign('selected_link', $selected_link);
    $xoopsTpl->assign('cf_desc', $cf_desc);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('cfsn', $cfsn);
}

//更新資料到tad_uploader中
function update_tad_uploader($cfsn = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $myts = MyTextSanitizer::getInstance();

    if (!empty($_POST['new_cat_sn'])) {
        $cat_sn = add_tad_uploader('', $_POST['new_cat_sn'], '', '1', $_POST['cat_sn'], $_POST['add_to_cat']);
    } else {
        $cat_sn = $_POST['add_to_cat'];
    }

    $uid = $xoopsUser->uid();

    if (!empty($_POST['file_url'])) {
        $file_url = $myts->addSlashes($_POST['file_url']);
    } else {
        $file_url = '';
    }

    if (!empty($_POST['cf_desc'])) {
        $cf_desc = $myts->addSlashes($_POST['cf_desc']);
    } else {
        $cf_desc = '';
    }

    if ('1' == $_POST['new_date']) {
        $now = date('Y-m-d H:i:s');
        $uptime = ",up_date='{$now}'";
    } else {
        $uptime = '';
    }

    //die(var_export($_FILES));
    if (!empty($_FILES['upfile']['name'][0])) {
        //先刪掉原有檔案
        $TadUpFiles->set_dir('subdir', "/user_{$uid}");
        $TadUpFiles->set_col('cfsn', $cfsn);
        $TadUpFiles->del_files();

        foreach ($_FILES['upfile']['name'] as $i => $name) {
            $name = $_FILES['upfile']['name'][0];

            $sql = 'update ' . $xoopsDB->prefix('tad_uploader_file') . " set cat_sn='{$cat_sn}',cf_name='{$name}',cf_desc='{$cf_desc}',cf_type='{$_FILES['upfile']['type'][$i]}',cf_size='{$_FILES['upfile']['size'][$i]}' {$uptime} where cfsn='$cfsn'";
            $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR5);

            $TadUpFiles->upload_one_file($name, $_FILES['upfile']['tmp_name'][$i], $_FILES['upfile']['type'][$i], $_FILES['upfile']['size'][$i], null, null, '', $cf_desc, true, true);
        }
    } elseif (!empty($file_url)) {
        $size = remote_file_size($file_url);
        $sql = 'update ' . $xoopsDB->prefix('tad_uploader_file') . " set cat_sn='{$cat_sn}',cf_name='{$name}',cf_desc='{$cf_desc}',cf_size='{$size}' {$uptime},file_url='{$file_url}' where cfsn='$cfsn'";

        $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR5);
    } else {
        $sql = 'update ' . $xoopsDB->prefix('tad_uploader_file') . " set cat_sn='{$cat_sn}',cf_desc='{$cf_desc}' {$uptime} where cfsn='$cfsn'";

        $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR5);
    }

    return $cat_sn;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$cfsn = system_CleanVars($_REQUEST, 'cfsn', 0, 'int');
$cat_sn = system_CleanVars($_REQUEST, 'cat_sn', 0, 'int');

switch ($op) {
    //新增資料
    case 'insert_tad_uploader':
        $cat_sn = add_tad_uploader_file();

        if (check_up_power('catalog', $cat_sn)) {
            header("location: index.php?of_cat_sn={$cat_sn}");
            exit;
        }
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);

        break;
    //更新資料
    case 'update_tad_uploader':
        $cat_sn = update_tad_uploader($cfsn);
        if (check_up_power('catalog', $cat_sn)) {
            header("location: index.php?of_cat_sn={$cat_sn}");
            exit;
        }
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);

        break;
    default:
        uploads_tabs($cat_sn, $cfsn);
        break;
}

/*-----------秀出結果區--------------*/

include_once XOOPS_ROOT_PATH . '/footer.php';
