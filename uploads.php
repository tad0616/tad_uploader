<?php
use Xmf\Request;
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_uploader\Tools;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tad_uploader_index.tpl';

if (count($upload_powers) <= 0 or empty($xoopsUser)) {
    redirect_header(XOOPS_URL . '/user.php', 3, _MD_TADUP_NO_EDIT_POWER);
}
require XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$cfsn = Request::getInt('cfsn');
$cat_sn = Request::getInt('cat_sn');

switch ($op) {
    //新增資料
    case 'insert_tad_uploader':
        $cat_sn = add_tad_uploader_file();

        if (Tools::check_up_power('catalog', $cat_sn)) {
            header("location: index.php?of_cat_sn={$cat_sn}");
            exit;
        }
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);
        break;

    //更新資料
    case 'update_tad_uploader':
        $cat_sn = update_tad_uploader($cfsn);
        if (Tools::check_up_power('catalog', $cat_sn)) {
            header("location: index.php?of_cat_sn={$cat_sn}");
            exit;
        }
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);
        break;

    case 'import':
        $cat_sn = tad_uploader_batch_import();
        header("location:index.php?of_cat_sn=$cat_sn");
        exit;

    default:
        uploads_tabs($cat_sn, $cfsn);
        batch_upload_form($cat_sn);
        $op = 'uploads_tabs';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("now_op", $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tad_uploader/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

function uploads_tabs($cat_sn = '', $cfsn = '')
{
    global $xoopsTpl, $TadUpFiles;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

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

    $cate_select = get_tad_uploader_cate_option(0, 0, $cat_sn, 1, false);

    $op = (empty($cfsn)) ? 'insert_tad_uploader' : 'update_tad_uploader';

    $upform = $TadUpFiles->upform(true, 'upfile', null, false);
    $xoopsTpl->assign('upform', $upform);

    $xoopsTpl->assign('cate_select', $cate_select);
    $xoopsTpl->assign('file_url', $file_url);
    $xoopsTpl->assign('selected_link', $selected_link);
    $xoopsTpl->assign('cf_desc', $cf_desc);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('cfsn', $cfsn);

    $EasyResponsiveTabs = new EasyResponsiveTabs('#uploadTab');
    $EasyResponsiveTabs->render();
}

//更新資料到tad_uploader中
function update_tad_uploader($cfsn = '')
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    if (!empty($_POST['new_cat_sn'])) {
        $cat_sn = add_tad_uploader('', $_POST['new_cat_sn'], '', '1', $_POST['cat_sn'], $_POST['add_to_cat']);
    } else {
        $cat_sn = $_POST['add_to_cat'];
    }

    $uid = $xoopsUser->uid();

    $file_url = empty($_POST['file_url']) ? '' : $_POST['file_url'];
    $cf_desc = empty($_POST['cf_desc']) ? '' : $_POST['cf_desc'];

    if ('1' == $_POST['new_date']) {
        $now = date('Y-m-d H:i:s');
        $uptime = ", `up_date`='{$now}'";
    } else {
        $uptime = '';
    }

    if (!empty($_FILES['upfile']['name'][0])) {
        //先刪掉原有檔案
        $TadUpFiles->set_dir('subdir', "/user_{$uid}");
        $TadUpFiles->set_col('cfsn', $cfsn);
        $TadUpFiles->del_files();

        foreach ($_FILES['upfile']['name'] as $i => $name) {
            $name = $_FILES['upfile']['name'][0];

            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader_file') . '` SET `cat_sn`=?, `cf_name`=?, `cf_desc`=?, `cf_type`=?, `cf_size`=? ' . $uptime . ' WHERE `cfsn`=?';
            Utility::query($sql, 'isssii', [$cat_sn, $name, $cf_desc, $_FILES['upfile']['type'][$i], $_FILES['upfile']['size'][$i], $cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);

            $TadUpFiles->upload_one_file($name, $_FILES['upfile']['tmp_name'][$i], $_FILES['upfile']['type'][$i], $_FILES['upfile']['size'][$i], null, null, '', $cf_desc, true, true);
        }
    } elseif (!empty($file_url)) {
        $size = remote_file_size($file_url);
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader_file') . '` SET `cat_sn`=?, `cf_name`=?, `cf_desc`=?, `cf_size`=? ' . $uptime . ', `file_url`=? WHERE `cfsn`=?';
        Utility::query($sql, 'issisi', [$cat_sn, $cf_desc, $cf_desc, $size, $file_url, $cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    } else {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader_file') . '` SET `cat_sn`=?, `cf_desc`=? ' . $uptime . ' WHERE `cfsn`=?';
        Utility::query($sql, 'isi', [$cat_sn, $cf_desc, $cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    return $cat_sn;
}

//批次匯入
function tad_uploader_batch_import()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $new_cat_sn = (int) $_POST['new_cat_sn'];
    $cat_sn = (int) $_POST['cat_sn'];

    if (!empty($new_cat_sn)) {
        $cat_sn = add_tad_uploader('', $new_cat_sn, '', '1', $cat_sn);
    }

    $uid = $xoopsUser->uid();
    $uid_name = \XoopsUser::getUnameFromId($uid, 1);

    set_time_limit(0);
    foreach ($_POST['files'] as $filename => $file_path) {
        if (empty($file_path)) {
            continue;
        }

        $file_src = _TAD_UPLOADER_BATCH_DIR . "/{$file_path}";

        $file_src = Utility::auto_charset($file_src, 'os');

        $type = mime_content_type($file_src);
        $cf_sort = get_file_max_sort($cat_sn);
        $size = filesize($file_src);

        $now = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_uploader_file') . '` (`cat_sn`,`uid`,`cf_name`,`cf_desc`,`cf_type`,`cf_size`,`up_date`,`cf_sort`) VALUES (?,?,?,?,?,?,?,?)';
        Utility::query($sql, 'iisssisi', [$cat_sn, $uid, $file_path, $_POST['cf_desc'][$filename], $type, $size, $now, $cf_sort]) or Utility::web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $cfsn = $xoopsDB->getInsertId();

        $new_filename = _TAD_UPLOADER_DIR . "/{$cfsn}_{$file_path}";

        $file_src = Utility::auto_charset($file_src, 'web');
        $new_filename = Utility::auto_charset($new_filename, 'web');

        //複製匯入單一檔案：
        $TadUpFiles->set_dir('subdir', "/user_{$uid}");
        $TadUpFiles->set_col('cfsn', $cfsn);
        $TadUpFiles->import_one_file($file_src, $new_filename, null, null, null, $_POST['cf_desc'][$filename], true, true);

        unlink($file_src);
    }

    //刪除其他多餘檔案
    Utility::rrmdir(_TAD_UPLOADER_BATCH_DIR);

    return $cat_sn;
}

//批次上傳表單
function batch_upload_form($cat_sn = '')
{
    global $xoopsDB, $xoopsTpl;

    $i = 0;
    $all_file = [];
    if ($dh = opendir(_TAD_UPLOADER_BATCH_DIR)) {
        while (false !== ($file = readdir($dh))) {
            if (mb_strlen($file) <= 2) {
                continue;
            }

            $file = Utility::auto_charset($file, 'web');

            $f = explode('.', $file);
            foreach ($f as $part) {
                $ext = mb_strtolower($part);
            }
            $len = (mb_strlen($ext) + 1) * -1;
            $filename = mb_substr($file, 0, $len);
            $all_file[$i]['filename'] = $filename;
            $all_file[$i]['file'] = $file;
            $i++;
        }
        closedir($dh);
    }
    $xoopsTpl->assign('all_file', $all_file);
}
