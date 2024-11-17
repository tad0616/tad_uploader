<?php
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\TadUpFiles') or !class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

use XoopsModules\Tad_uploader\Tools;
if (!class_exists('XoopsModules\Tad_uploader\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tad_uploader/preloads/autoloader.php';
}

$TadUpFiles = new TadUpFiles('tad_uploader');

$uid_dir = 0;
if ($xoopsUser) {
    $uid_dir = $xoopsUser->uid();
}
define('_TAD_UPLOADER_DIR', XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid_dir}");
define('_TAD_UPLOADER_BATCH_DIR', XOOPS_ROOT_PATH . "/uploads/tad_uploader_batch/user_{$uid_dir}");
define('_TAD_UPLOADER_URL', XOOPS_URL . '/uploads/tad_uploader');
Utility::mk_dir(_TAD_UPLOADER_BATCH_DIR);

//新增資料到tad_uploader中
function add_tad_uploader_file()
{
    global $xoopsDB, $xoopsUser, $TadUpFiles;

    $file_url = isset($_POST['file_url']) ? $_POST['file_url'] : '';

    if (!empty($_POST['creat_new_cat'])) {
        $cat_sn = add_tad_uploader('', $_POST['creat_new_cat'], '', '1', $_POST['cat_sn'], $_POST['add_to_cat']);
    } else {
        $cat_sn = $_POST['add_to_cat'];
    }

    if (empty($_FILES['upfile']['name'][0]) and empty($file_url)) {
        return $cat_sn;
    }

    $uid = $xoopsUser->uid();
    $cf_sort = 1;

    $cf_desc = $_POST['cf_desc'];
    $now = date('Y-m-d H:i:s');
    $cat_sn = (int) $cat_sn;

    if (!empty($file_url)) {
        $size = remote_file_size($file_url);
        $name = Utility::get_basename($file_url);
        $name = urldecode($name);
        if (empty($cf_desc)) {
            $cf_desc = $name;
        }

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_uploader_file') . '` (`cat_sn`,`uid`,`cf_name`,`cf_desc`,`cf_type`,`cf_size`,`up_date`,`file_url`,`cf_sort`) VALUES (?,?,?,?,?,?,?,?,?)';
        Utility::query($sql, 'iisssissi', [$cat_sn, $uid, $name, $cf_desc, '', $size, $now, $file_url, $cf_sort]) or Utility::web_error($sql, __FILE__, __LINE__);
    } else {
        // die(var_export($_FILES));
        foreach ($_FILES['upfile']['name'] as $i => $name) {
            if (empty($_POST['cf_desc'])) {
                $cf_desc = $name;
            }

            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_uploader_file') . '` (`cat_sn`, `uid`, `cf_name`, `cf_desc`, `cf_type`, `cf_size`, `up_date`, `cf_sort`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            Utility::query($sql, 'iisssisi', [$cat_sn, $uid, $name, $cf_desc, $_FILES['upfile']['type'][$i], $_FILES['upfile']['size'][$i], $now, $cf_sort]) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $cfsn = $xoopsDB->getInsertId();

            $TadUpFiles->set_dir('subdir', "/user_{$uid}");
            $TadUpFiles->set_col('cfsn', $cfsn);
            $TadUpFiles->upload_one_file($name, $_FILES['upfile']['tmp_name'][$i], $_FILES['upfile']['type'][$i], $_FILES['upfile']['size'][$i], null, null, '', $_cf_desc, true, true);
            $sort++;
        }
    }

    return $cat_sn;
}

//取得遠端檔案的大小
function remote_file_size($url)
{
    // 檢查URL格式
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    // 方法1: 使用 get_headers()
    if (function_exists('get_headers')) {
        $headers = get_headers($url, 1);
        if (isset($headers['Content-Length'])) {
            return (int) $headers['Content-Length'];
        }
        // 某些伺服器回傳小寫標頭
        if (isset($headers['content-length'])) {
            return (int) $headers['content-length'];
        }
    }

    // 方法2: 使用 curl
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        if ($ch !== false) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $data = curl_exec($ch);
            $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            curl_close($ch);

            if ($size != -1) {
                return (int) $size;
            }
        }
    }

    // 方法3: 使用 stream get_headers
    $context = stream_context_create([
        'http' => [
            'method' => 'HEAD',
            'follow_location' => 1,
            'ignore_errors' => true,
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $fd = @fopen($url, 'rb', false, $context);
    if ($fd) {
        $meta = stream_get_meta_data($fd);
        fclose($fd);

        if ($meta && isset($meta['wrapper_data'])) {
            foreach ($meta['wrapper_data'] as $header) {
                if (preg_match('/Content-Length: (\d+)/i', $header, $matches)) {
                    return (int) $matches[1];
                }
            }
        }
    }

    return false;
}

//找出目前資料夾應設順序
function get_cat_max_sort($of_cat_sn = '')
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`cat_sort`) FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=?';
    $result = Utility::query($sql, 'i', [$of_cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

    list($max_sort) = $xoopsDB->fetchRow($result);

    return $max_sort + 1;
}

//找出目前資料夾應設順序
function get_file_max_sort($cat_sn = '')
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`cf_sort`) FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cat_sn`=?';
    $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);
    list($max_sort) = $xoopsDB->fetchRow($result);

    return $max_sort + 1;
}

//取得類別下拉選單
function get_cata_select($disable_cat_sn = [], $dbv = 0, $of_cat_sn = 0, $tab = '')
{
    global $xoopsDB;
    $sql = 'SELECT `cat_sn`, `cat_title` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=? AND `cat_enable`=1';
    $result = Utility::query($sql, 'i', [$of_cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

    $option = $disabled = '';
    $sub_disable_cat_sn = [];
    if ($of_cat_sn) {
        $tab .= '----';
    }

    $categoryHelper = new CategoryHelper('tad_uploader', 'cat_sn', 'of_cat_sn', 'cat_title');

    while (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {
        if ((is_array($disable_cat_sn) && in_array($cat_sn, $disable_cat_sn)) || $dbv == $cat_sn) {
            $disabled = 'disabled';
            $sub_disable_cat_sn = array_keys($categoryHelper->getSubCategories($cat_sn));
            $new_disable_cat_sn = array_merge($disable_cat_sn, $sub_disable_cat_sn);
        } else {
            $disabled = '';
            $new_disable_cat_sn = $disable_cat_sn;
        }

        if (!Tools::check_up_power('catalog_up', $cat_sn)) {
            continue;
        }

        $option .= "<option value='$cat_sn' " . Utility::chk($cat_sn, $dbv, '', 'selected') . " $disabled>{$tab} {$cat_title}</option>\n";
        $option .= get_cata_select($new_disable_cat_sn, $dbv, $cat_sn, $tab);
    }

    return $option;
}

//取得目錄下拉選單
function get_tad_uploader_cate_option($of_cat_sn = 0, $level = 0, $v = '', $show_dot = '1', $optgroup = true, $chk_view = '1')
{
    global $xoopsDB;
    $dot = ($show_dot == '1') ? str_repeat('--', $level) : '';
    $level += 1;

    $cate_count = [];
    $sql = 'SELECT COUNT(*), `cat_sn` FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` GROUP BY `cat_sn`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($count, $cat_sn) = $xoopsDB->fetchRow($result)) {
        $cate_count[$cat_sn] = $count;
    }

    $option = '';
    $sql = 'SELECT `cat_sn`,`cat_title` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=? ORDER BY `cat_sort`';
    $result = Utility::query($sql, 'i', [$of_cat_sn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $ok_cat = ($chk_view) ? Utility::get_gperm_cate_arr('catalog_up', 'tad_uploader') : [];

    while (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {
        $selected = ($v == $cat_sn) ? 'selected' : '';
        if (empty($cate_count[$cat_sn]) and $optgroup) {
            $option .= ($chk_view and !in_array($cat_sn, $ok_cat)) ? '' : "<optgroup label='{$cat_title}' style='font-style: normal;color:black;'>" . get_tad_uploader_cate_option($cat_sn, $level, $v, '0') . '</optgroup>';
        } else {
            $counter = (empty($cate_count[$cat_sn])) ? 0 : $cate_count[$cat_sn];
            $option .= ($chk_view and !in_array($cat_sn, $ok_cat)) ? '' : "<option value='{$cat_sn}' $selected >{$dot}{$cat_title} ($counter)</option>";
            $option .= get_tad_uploader_cate_option($cat_sn, $level, $v, $show_dot, $optgroup, $chk_view);
        }
    }

    return $option;
}

//取代/新增tad_uploader現有資料
function add_tad_uploader($the_cat_sn = 0, $cat_title = '', $cat_desc = '', $cat_enable = '1', $of_cat_sn = '0', $cat_add_form = 0, $cat_share = 'auto', $cat_sort = '0', $cat_count = '0', $catalog = [1, 2, 3], $catalog_up = [1], $is_back = 0)
{
    global $xoopsDB, $xoopsUser, $xoopsModule;
    // die('the_cat_sn=' . $the_cat_sn);
    if (!empty($the_cat_sn) && !Tools::check_up_power('catalog_up', $the_cat_sn)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_POWER);
    }

    $uid = ($xoopsUser) ? $xoopsUser->uid() : 0;

    //$of_cat_sn=0;
    if (!empty($cat_add_form)) {
        $of_cat_sn = $cat_add_form;
    }
    //有上層目錄，新增目錄時，而且在前台時($is_back=0) , 依上層權限
    if ($of_cat_sn and $the_cat_sn == '' and $is_back == 0) {
        $catalog = Utility::get_perm($of_cat_sn, 'catalog');
        $catalog_up = Utility::get_perm($of_cat_sn, 'catalog_up');
    }
    if ($cat_share === 'auto') {
        if (empty($of_cat_sn)) {
            $cat_share = 1;
        } else {
            $cat = Tools::get_tad_uploader($of_cat_sn);
            $cat_share = $cat['cat_share'];
        }
    }
    $the_cat_sn = (int) $the_cat_sn;
    $of_cat_sn = (int) $of_cat_sn;
    $cat_count = (int) $cat_count;

    $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_uploader') . '` (`cat_sn`, `cat_title`, `cat_desc`, `cat_enable`, `uid`, `of_cat_sn`, `cat_share`, `cat_sort`, `cat_count`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'isssiisii', [$the_cat_sn, $cat_title, $cat_desc, $cat_enable, $uid, $of_cat_sn, $cat_share, $cat_sort, $cat_count]) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $cat_sn = $xoopsDB->getInsertId();
    if (empty($cat_sn)) {
        $cat_sn = $the_cat_sn;
    }

    //寫入權限
    Utility::save_perm($catalog, $cat_sn, 'catalog');
    Utility::save_perm($catalog_up, $cat_sn, 'catalog_up');

    get_path_belong($cat_sn, $catalog, $catalog_up, $cat_enable, $cat_share);

    return $cat_sn;
}

//以下目錄屬性相同
function get_path_belong($cat_sn, $tad_uploader, $tad_uploader_up, $cat_enable, $cat_share)
{
    global $xoopsDB;
    $sql = 'SELECT `cat_sn` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=?';
    $result = Utility::query($sql, 'i', [$cat_sn]) or die($sql);

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $sn = $row['cat_sn'];

        $sql2 = 'UPDATE `' . $xoopsDB->prefix('tad_uploader') . '` SET `cat_enable`=?, `cat_share`=? WHERE `cat_sn`=?';
        Utility::query($sql2, 'ssi', [$cat_enable, $cat_share, $sn]) or Utility::web_error($sql2);

        //寫入權限
        Utility::save_perm($tad_uploader, $sn, 'tad_uploader');
        Utility::save_perm($tad_uploader_up, $sn, 'tad_uploader_up');
        get_path_belong($sn, $tad_uploader, $tad_uploader_up, $cat_enable, $cat_share);
    }
}

/********************檔案處理******************
 * @param string $real_file_name
 * @param string $cfsn
 * @return string
 */

//取得上傳檔名
function get_file_name($real_file_name = '', $cfsn = '')
{
    $f = explode('.', $real_file_name);
    $ln = count($f) - 1;
    $sub = $f[$ln];
    if ($sub === 'php') {
        $real_file_name .= 's';
    }

    $uploadfile = "{$cfsn}_{$real_file_name}";
    //die($uploadfile);
    return $uploadfile;
}

//判別格式圖檔
function file_pic($file)
{
    $extarr = explode('.', $file);
    foreach ($extarr as $val) {
        $ext = mb_strtolower($val);
    }
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/images/mimetype/{$ext}.png")) {
        return 'none.png';
    }

    return "{$ext}.png";
}

//以流水號取得某筆檔案資料
function get_file($cfsn = '')
{
    global $xoopsDB;
    if (empty($cfsn)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cfsn` =?';
    $result = Utility::query($sql, 'i', [$cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR1);

    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//刪除檔案
function delfile($select_files, $of_cat_sn = '')
{
    global $xoopsDB, $TadUpFiles;
    if (empty($select_files)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_SELECTED_FILE);
    }
    foreach ($select_files as $cfsn => $cf_name) {
        del_file($cfsn, true, $of_cat_sn);
    }
}

//刪除一個檔案
function del_file($cfsn = '', $del_sql = true, $of_cat_sn = '')
{
    global $xoopsDB, $TadUpFiles, $xoopsUser;
    if (empty($cfsn)) {
        return;
    }

    //權限檢查
    $power = Tools::check_up_power('catalog_up', $of_cat_sn);

    if (!$power) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_POWER);
    }

    //若要限制原上傳者才能刪除
    $where_uid = '';
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $where_uid = ($_SESSION['tad_upload_adm']) ? '' : " and uid = '{$uid}'";
    } else {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_LOGIN);
    }

    $file = get_file($cfsn);
    // die(var_export($file));
    if ($del_sql) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cfsn`=? ' . $where_uid;
        Utility::query($sql, 'i', [$cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR7);

    }
    $TadUpFiles->set_dir('subdir', "/user_{$file['uid']}");
    $TadUpFiles->set_col('cfsn', $cfsn); //若要整個刪除
    $TadUpFiles->del_files();
}

//下載檔案
function dlfile($cfsn = '')
{
    global $xoopsUser, $xoopsDB, $TadUpFiles;
    if (empty($cfsn)) {
        return;
    }

    $cf = get_tad_uploader_file($cfsn);
    if (!Tools::check_up_power('catalog', $cf['cat_sn'])) {
        redirect_header('index.php', 3, _MD_TADUP_NO_ACCESS_POWER);
    }
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        add_dl_log($cfsn, $uid);
    }
    //更新人氣值
    update_tad_uploader_file_count($cfsn);

    if (!empty($cf['file_url'])) {
        header("location: {$cf['file_url']}");
        exit;
    } else {
        $sql = 'SELECT `uid`, `cf_type` FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cfsn` = ?';
        $result = Utility::query($sql, 'i', [$cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($uid, $cf_type) = $xoopsDB->fetchRow($result);
        $TadUpFiles->set_dir('subdir', "/user_{$uid}");

        $sql = 'SELECT `files_sn`,`kind` FROM `' . $xoopsDB->prefix('tad_uploader_files_center') . '` WHERE `col_name`="cfsn" AND `col_sn`=?';
        $result = Utility::query($sql, 'i', [$cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($files_sn, $kind) = $xoopsDB->fetchRow($result);

        $force_arr = ['application/pdf', 'audio/mp3', 'video/mp4'];
        $force = ($kind == 'img' or in_array($cf_type, $force_arr)) ? true : false;
        $TadUpFiles->add_file_counter($files_sn, true, $force);
    }
}

function get_file_by_cfsn($cfsn = '')
{
    global $xoopsDB;
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_uploader_files_center') . '` WHERE `col_name`=? AND `col_sn`=?';
    $result = Utility::query($sql, 'si', ['cfsn', $cfsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $file = $xoopsDB->fetchArray($result);

    return $file;
}

//下載紀錄
function add_dl_log($cfsn = '', $uid = '')
{
    global $xoopsDB;
    if (empty($cfsn)) {
        return;
    }

    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $myip = $_SERVER['REMOTE_ADDR'];
    } else {
        $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $myip = $myip[0];
    }

    $now = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_uploader_dl_log') . '` (`uid`, `dl_time`, `from_ip`, `cfsn`) VALUES (?, ?, ?, ?)';
    Utility::query($sql, 'issi', [$uid, $now, $myip, $cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, 'add log error');
}

//以流水號取得某筆tad_uploader_file資料
function get_tad_uploader_file($cfsn = '')
{
    global $xoopsDB;
    if (empty($cfsn)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cfsn` =?';
    $result = Utility::query($sql, 'i', [$cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);

    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//更新檔案人氣值
function update_tad_uploader_file_count($cfsn = '')
{
    global $xoopsDB;
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader_file') . '` SET `cf_count` = `cf_count`+1 WHERE `cfsn`=?';
    Utility::query($sql, 'i', [$cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR6);

    return $cfsn;
}

//更新檔案資料
function update_tad_uploader_file($cfsn = '', $col_name = '', $col_value = '')
{
    global $xoopsDB;

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_uploader_file') . '` SET `?`=? WHERE `cfsn`=?';
    Utility::query($sql, 'ssi', [$col_name, $col_value, $cfsn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR6);

    return $cfsn;
}

//刪除目錄
function delete_tad_uploader($cat_sn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModule;

    $power = Tools::check_up_power('catalog_up', $cat_sn);

    if (!$power) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_POWER);
    }
    $where = '';
    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
        $where = ($_SESSION['tad_upload_adm']) ? '' : " and uid='{$uid}'";
    } else {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_LOGIN);
    }

    //找出所屬資料夾
    $sql = 'SELECT `cat_sn` FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn`=? ' . $where;
    $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);

    while (list($sub_cat_sn) = $xoopsDB->fetchRow($result)) {
        delete_tad_uploader($sub_cat_sn);
    }

    //找出所屬檔案
    $flies = [];
    $sql = 'SELECT `cfsn`, `cf_name` FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` WHERE `cat_sn`=?';
    $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);

    while (list($cfsn, $cf_name) = $xoopsDB->fetchRow($result)) {
        $flies[$cfsn] = $cf_name;
    }

    //刪除所屬檔案
    if (!empty($flies)) {
        delfile($flies);
    }

    //取得某資料夾檔案數
    $get_catfile_num = get_catfile_num($cat_sn);
    //取得某資料夾檔案數
    $get_subcat_num = get_subcat_num($cat_sn);
    $total = $get_catfile_num + $get_subcat_num;

    if ($total > 0) {
        redirect_header($_SERVER['PHP_SELF'], 3, sprintf(_MD_TADUP_CANT_DELETE1, $get_subcat_num, $get_catfile_num));
    } else {
        //刪掉指定資料夾
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `cat_sn`=? ' . $where;
        Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR8);
    }
}

//取得某資料夾檔案數
function get_catfile_num($cat_sn = 0)
{
    global $xoopsDB;
    if (empty($cat_sn)) {
        return;
    }

    $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_uploader_file') . '` AS a, `' . $xoopsDB->prefix('tad_uploader') . '` AS b WHERE a.`cat_sn`=b.`cat_sn` AND (a.`cat_sn`=? OR b.`of_cat_sn`=?)';
    $result = Utility::query($sql, 'ii', [$cat_sn, $cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);
    $total = 0;
    while (list($data) = $xoopsDB->fetchRow($result)) {
        $total += $data;
    }

    return $total;
}

//取得某資料夾目錄數
function get_subcat_num($cat_sn = 0)
{
    global $xoopsDB;

    $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_uploader') . '` WHERE `of_cat_sn` =?';
    $result = Utility::query($sql, 'i', [$cat_sn]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_DB_ERROR2);

    list($data) = $xoopsDB->fetchRow($result);

    return $data;
}

//tad_uploader編輯表單
function tad_uploader_cate_form($cat_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsTpl;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    if (!Tools::check_up_power('catalog_up', $cat_sn) and $cat_sn != 0) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADUP_NO_POWER);
    }

    //抓取預設值
    if (!empty($cat_sn)) {
        $DBV = Tools::get_tad_uploader($cat_sn);
    } else {
        $DBV = [];
    }

    //預設值設定
    $cat_sn = (!isset($DBV['cat_sn'])) ? $cat_sn : $DBV['cat_sn'];
    $cat_title = (!isset($DBV['cat_title'])) ? '' : $DBV['cat_title'];
    $cat_desc = (!isset($DBV['cat_desc'])) ? '' : $DBV['cat_desc'];
    $cat_enable = (!isset($DBV['cat_enable'])) ? '1' : $DBV['cat_enable'];
    $of_cat_sn = (!isset($DBV['of_cat_sn'])) ? '' : $DBV['of_cat_sn'];
    $cata_select = get_cata_select([(int) $cat_sn], 0);
    $cat_share = (!isset($DBV['cat_share'])) ? '1' : $DBV['cat_share'];
    $cat_count = (!isset($DBV['cat_count'])) ? '' : $DBV['cat_count'];

    $cat_max_sort = get_cat_max_sort();
    $cat_sort = (!isset($DBV['cat_sort'])) ? $cat_max_sort : $DBV['cat_sort'];

    $mod_id = $xoopsModule->getVar('mid');
    $modulepermHandler = xoops_getHandler('groupperm');

    $read_group = $modulepermHandler->getGroupIds('catalog', $cat_sn, $mod_id);

    $post_group = $modulepermHandler->getGroupIds('catalog_up', $cat_sn, $mod_id);

    if (empty($read_group)) {
        $read_group = [1, 2, 3];
    }

    if (empty($post_group)) {
        $post_group = [1];
    }

    //可見群組
    $SelectGroup_name = new \XoopsFormSelectGroup('view_group', 'catalog', true, $read_group, 6, true);
    $SelectGroup_name->setExtra("class='form-control' id='view_group'");
    $enable_group = $SelectGroup_name->render();

    //可上傳群組
    $SelectGroup_name = new \XoopsFormSelectGroup('upload_group', 'catalog_up', true, $post_group, 6, true);
    $SelectGroup_name->setExtra("class='form-control' id='upload_group'");
    $enable_upload_group = $SelectGroup_name->render();

    $xoopsTpl->assign('cata_select', $cata_select);
    $xoopsTpl->assign('cat_title', $cat_title);
    $xoopsTpl->assign('cat_desc', $cat_desc);
    $xoopsTpl->assign('enable_group', $enable_group);
    $xoopsTpl->assign('enable_upload_group', $enable_upload_group);
    $xoopsTpl->assign('cat_sn', $cat_sn);
    $xoopsTpl->assign('cat_count', $cat_count);
    $xoopsTpl->assign('cat_sort', $cat_sort);
    $xoopsTpl->assign('cat_enable', $cat_enable);
    $xoopsTpl->assign('cat_share', $cat_share);
}

if (!function_exists('mime_content_type')) {
    function mime_content_type($filename)
    {
        $mime_types = [
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        ];

        $ext = mb_strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);

            return $mimetype;
        }

        return 'application/octet-stream';
    }
}
