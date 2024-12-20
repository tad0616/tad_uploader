<?php
use Xmf\Request;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\FooTable;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_uploader\Tools;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_uploader_index.tpl';
if (empty($_SESSION['list_mode'])) {
    $_SESSION['list_mode'] = $xoopsModuleConfig['show_mode'];
}

require XOOPS_ROOT_PATH . '/header.php';
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$cfsn = Request::getInt('cfsn');
$cat_sn = Request::getInt('cat_sn');
$of_cat_sn = Request::getInt('of_cat_sn');
$new_of_cat_sn = Request::getInt('new_of_cat_sn');
$new_cat_sn = Request::getInt('new_cat_sn');
$new_cat_title = Request::getString('new_cat_title');
$cat_title = Request::getString('cat_title');
$all_selected = Request::getString('all_selected');
$select_files = Request::getArray('select_files');
$cat_desc = Request::getString('cat_desc');
$this_folder = Request::getString('this_folder');
$add_cat_title = Request::getString('add_cat_title');
$add_to_cat = Request::getInt('add_to_cat');
$cat_share = Request::getInt('cat_share');
$cat_sort = Request::getInt('cat_sort');
$cat_count = Request::getInt('cat_count');
$cat_enable = Request::getInt('cat_enable');
$catalog = Request::getArray('catalog');
$catalog_up = Request::getArray('catalog_up');

switch ($op) {
    //分類設定
    case 'tad_uploader_cate_form':
        tad_uploader_cate_form($cat_sn);
        break;

    case 'add_tad_uploader':
        add_tad_uploader($cat_sn, $cat_title, $cat_desc, $cat_enable, $of_cat_sn, $add_to_cat, $cat_share, $cat_sort, $cat_count, $catalog, $catalog_up, 'admin');
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
        exit;

    case 'update_data':
        update_data();
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
        exit;

    case 'dlfile':
        dlfile($cfsn);
        exit;

    case 'del_file':
        del_file($cfsn, true, $of_cat_sn);
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$of_cat_sn}");
        exit;

    case 'batch_dir_tools':
        if ('new_cat_title' === $this_folder) {
            update_tad_uploader('cat_title', $new_cat_title, $cat_sn);
            header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
        } elseif ('add_cat_title' === $this_folder) {
            $cat_sn = add_tad_uploader(0, $add_cat_title, '', '1', $cat_sn, $add_to_cat);
            header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
            $cat_sn = $new_cat_sn;
        } elseif ('new_of_cat_sn' === $this_folder) {
            update_tad_uploader('of_cat_sn', $new_of_cat_sn, $cat_sn);
            header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$new_of_cat_sn}");
            $cat_sn = $new_cat_sn;
        }
        exit;

    case 'batch_file_tools':
        if ('all_del' === $all_selected) {
            delfile($select_files, $cat_sn);
        } elseif ('all_move' === $all_selected) {
            movefile($select_files, $new_cat_sn);
            $cat_sn = $new_cat_sn;
        }
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
        exit;

    case 'save_cat_desc':
        update_tad_uploader('cat_desc', $cat_desc, $cat_sn);
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn}");
        exit;

    //刪除資料
    case 'delete_tad_uploader':
        delete_tad_uploader($cat_sn);
        header("location:{$_SERVER['PHP_SELF']}?of_cat_sn={$of_cat_sn}");
        exit;

    default:
        list_all_data($of_cat_sn);
        $op = 'list_all_data';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tad_uploader/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//列出所有資料
function list_all_data($the_cat_sn = 0)
{
    global $xoopsDB, $xoopsModuleConfig, $xoopsTpl, $TadUpFiles, $interface_menu;

    $path = '';

    //目前路徑
    $categoryHelper = new CategoryHelper('tad_uploader', 'cat_sn', 'of_cat_sn', 'cat_title');
    $arr = $categoryHelper->getCategoryPath($the_cat_sn);
    // $arr = get_tad_uploader_cate_path($the_cat_sn);
    $path = Utility::tad_breadcrumb($the_cat_sn, $arr, 'index.php', 'of_cat_sn', 'cat_title');

    //新增人氣值
    if (!empty($the_cat_sn)) {
        update_tad_uploader_count($the_cat_sn);
    }

    //權限檢查
    $check_power = Tools::check_up_power('catalog', $the_cat_sn);
    $check_up_power = Tools::check_up_power('catalog_up', $the_cat_sn);

    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
    if (!$check_power) {
        return _MD_TADUP_NO_ACCESS_POWER;
    }

    //底下目錄
    $folder_list = get_folder_list($the_cat_sn, $check_up_power);

    //抓取該目錄底下的檔案
    $files_list = get_files_list($the_cat_sn, $check_up_power);

    //若有權限則可排序
    Utility::get_jquery(true);

    $upform = $move_option = '';
    if ($check_up_power) {
        //搬移的選單
        $disbale[] = $the_cat_sn;
        $move_option = get_cata_select($disbale);
        $menu_option = get_tad_uploader_cate_option(0, 0, $the_cat_sn, 1, false);
        $upform = $TadUpFiles->upform(true, 'upfile', null, false);
    }

    $FooTable = new FooTable();
    $FooTable->render(false);

    $sql = 'select * from ' . $xoopsDB->prefix('tad_uploader') . ' where cat_sn=?';
    $result = Utility::query($sql, 'i', [$the_cat_sn]);
    $cate = $xoopsDB->fetchArray($result);

    foreach ($cate as $k => $v) {
        $$k = $v;
        $xoopsTpl->assign($k, $v);
    }

    $xoopsTpl->assign('upform', $upform);
    $xoopsTpl->assign('move_option', $move_option);
    $xoopsTpl->assign('menu_option', $menu_option);
    $xoopsTpl->assign('memory_limit', (int) ini_get('memory_limit'));
    $xoopsTpl->assign('post_max_size', (int) ini_get('post_max_size'));
    $xoopsTpl->assign('upload_max_filesize', (int) ini_get('upload_max_filesize'));
    $xoopsTpl->assign('max_execution_time', ini_get('max_execution_time'));
    $xoopsTpl->assign('path', $path);
    $xoopsTpl->assign('cat_sn', $the_cat_sn);
    $xoopsTpl->assign('folder_list', $folder_list);
    $xoopsTpl->assign('files_list', $files_list);
    $xoopsTpl->assign('up_power', $check_up_power);
    $xoopsTpl->assign('list_mode', $_SESSION['list_mode']);
    $xoopsTpl->assign('only_show_desc', $xoopsModuleConfig['only_show_desc']);
    $xoopsTpl->assign('icon_width', '130px');

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_uploader_func', "index.php?op=delete_tad_uploader&of_cat_sn={$the_cat_sn}&cat_sn=", 'cat_sn');
    $SweetAlert2 = new SweetAlert();
    $SweetAlert2->render('delete_file_func', "index.php?op=del_file&of_cat_sn={$the_cat_sn}&cfsn=", 'cfsn');
}

//抓取底下目錄
function get_folder_list($the_cat_sn = '', $check_up_power = '')
{
    global $xoopsDB;

    $sql = 'SELECT `cat_sn`,`cat_title`,`cat_desc`,`cat_enable`,`uid`,`of_cat_sn`,`cat_share`,`cat_sort`,`cat_count` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=? AND `cat_enable`=1 ORDER BY `cat_sort`';
    $result = Utility::query($sql, 'i', [$the_cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

    $all = [];
    $i = 0;
    while (list($cat_sn, $cat_title, $cat_desc, $cat_enable, $uid, $of_cat_sn, $cat_share, $cat_sort, $cat_count) = $xoopsDB->fetchRow($result)) {
        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (!Tools::check_up_power('catalog', $cat_sn)) {
            continue;
        }

        //底下檔案數
        $file_num = get_catfile_num($cat_sn);
        $lock = ('1' == $cat_share) ? '' : '_lock';

        $all[$i]['the_cat_sn'] = $the_cat_sn;
        $all[$i]['cat_sn'] = $cat_sn;
        $all[$i]['lock'] = $lock;
        $all[$i]['cat_title'] = $cat_title;
        $all[$i]['file_num'] = $file_num;
        $all[$i]['cat_count'] = $cat_count;
        $all[$i]['cat_desc'] = $cat_desc;
        $i++;
    }

    return $all;
}

//抓取該目錄底下的檔案
function get_files_list($the_cat_sn = '', $check_up_power = '')
{
    global $xoopsDB, $xoopsModuleConfig;

    //排序
    $sql = 'SELECT `cfsn`, `cat_sn`, `uid`, `cf_name`, `cf_desc`, `cf_type`, `cf_size`, `cf_count`, `up_date`, `file_url` FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cat_sn` =? ORDER BY `cf_sort`';
    $result = Utility::query($sql, 'i', [$the_cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);

    $all = [];
    $i = 0;
    while (list($cfsn, $cat_sn, $uid, $cf_name, $cf_desc, $cf_type, $cf_size, $cf_count, $up_date, $file_url) = $xoopsDB->fetchRow($result)) {
        $ff = get_file_by_cfsn($cfsn);
        if ('img' === $ff['kind']) {
            list($width, $height, $type, $attr) = getimagesize(XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/image/.thumbs/{$ff['hash_filename']}");
            $pic = XOOPS_URL . "/uploads/tad_uploader/user_{$uid}/image/.thumbs/{$ff['hash_filename']}";
        } else {
            $pic = XOOPS_URL . '/modules/tadtools/images/mimetype/' . file_pic($cf_name);
        }

        $size = roundsize($cf_size);
        $cf_desc = nl2br($cf_desc);
        $cf_desc = (empty($cf_desc)) ? $cf_name : $cf_desc;

        $fname = mb_strtolower($cf_name);
        if ('1' == $xoopsModuleConfig['only_show_desc']) {
            if (!empty($file_url)) {
                $fname = basename($file_url);
            }
        }

        $up_date = date('Y-m-d H:i:s', xoops_getUserTimestamp(strtotime($up_date)));

        if ('img' === $ff['kind']) {
            $all[$i]['thumb_style'] = ($height > $width) ? 'width:85px;' : 'height:64px;max-width:85px;';
        } else {
            $all[$i]['thumb_style'] = 'height:64px;';
        }
        $all[$i]['pic'] = $pic;
        $all[$i]['fname'] = ('img' === $ff['kind']) ? '' : $fname;
        $all[$i]['cfsn'] = $cfsn;
        $all[$i]['cf_name'] = $cf_name;
        $all[$i]['up_date'] = $up_date;
        $all[$i]['size'] = $size;
        $all[$i]['cf_count'] = $cf_count;
        $all[$i]['cf_desc'] = $cf_desc;
        $all[$i]['cat_sn'] = $cat_sn;
        $all[$i]['uid'] = $uid;
        $i++;
    }

    return $all;
}

function roundsize($size)
{
    $i = 0;
    $iec = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
    while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;
    }

    return (round($size, 1) . ' ' . $iec[$i]);
}

//更新tad_uploader某一筆資料
function update_tad_uploader($col_name = '', $col_val = '', $cat_sn = '')
{
    global $xoopsDB;
    if (!Tools::check_up_power('catalog', $cat_sn)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);
    }

    $col_name = $xoopsDB->escape($col_name);
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader') . '` SET `' . $col_name . '` = ? WHERE `cat_sn` = ?';
    Utility::query($sql, 'si', [$col_val, $cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR3);

    return $cat_sn;
}

//更新tad_uploader_data現有資料
function update_data()
{
    foreach ($_POST['cf_desc'] as $cfsn => $cf_desc) {
        $cfsn = update_tad_uploader_file($cfsn, 'cf_desc', $cf_desc);
    }
}

//找出路徑
function find_path($cat_sn = '')
{
    global $xoopsDB;
    if (empty($cat_sn)) {
        return;
    }
    $sql = 'SELECT `cat_sn`, `cat_title`, `of_cat_sn` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `cat_sn` = ?';
    $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

    while (list($cat_sn, $cat_title, $of_cat_sn) = $xoopsDB->fetchRow($result)) {
        $cat_sn_array = $cat_sn . "'>" . $cat_title;
        if (!empty($of_cat_sn)) {
            $cat_sn_array .= '||' . find_path($of_cat_sn);
        }
    }

    return $cat_sn_array;
}

//取得目前所在路徑
function get_path($cat_sn = '')
{
    $cat_sn_str = find_path($cat_sn);
    $cat_sn_array = explode('||', $cat_sn_str);
    $path = '';
    for ($i = count($cat_sn_array); $i >= 0; $i--) {
        if (empty($cat_sn_array[$i])) {
            continue;
        }

        $path .= "/ <a href='{$_SERVER['PHP_SELF']}?of_cat_sn={$cat_sn_array[$i]}</a>";
    }

    return $path;
}

//搬移檔案
function movefile($select_files = [], $new_cat_sn = '')
{
    if (empty($select_files)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_SELECTED_FILE);
    }

    foreach ($select_files as $cfsn => $cf_name) {
        update_tad_uploader_file($cfsn, 'cat_sn', $new_cat_sn);
    }
}

//更新目錄人氣值
function update_tad_uploader_count($cat_sn = '')
{
    global $xoopsDB;
    if (!Tools::check_up_power('catalog', $cat_sn)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_UPLOADED_AND_NO_POWER);
    }
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader') . '` SET `cat_count` = `cat_count`+1 WHERE `cat_sn` = ?';
    Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR3);

    return $cat_sn;
}
