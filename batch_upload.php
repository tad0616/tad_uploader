<?php
require_once __DIR__ . '/header.php';
require_once "language/{$xoopsConfig['language']}/batch.php";

$op = (empty($_REQUEST['op'])) ? '' : $_REQUEST['op'];
$cat_sn = (isset($_REQUEST['cat_sn'])) ? (int) $_REQUEST['cat_sn'] : 0;

switch ($op) {
    case 'import':
        $cat_sn = tad_uploader_batch_import();
        header("location:index.php?of_cat_sn=$cat_sn");
        break;
    default:
        echo tad_uploader_batch_upload_form($cat_sn);
        break;
}

//批次上傳表單
function tad_uploader_batch_upload_form($cat_sn = '')
{
    global $xoopsDB, $xoopsModuleConfig, $ok_video_ext, $ok_image_ext, $isAdmin;
    $cate_select = get_tad_uploader_cate_option(0, 0, $cat_sn, 1, false);

    $i = 0;
    $tr = '';
    if ($dh = opendir(_TAD_UPLOADER_BATCH_DIR)) {
        while (false !== ($file = readdir($dh))) {
            if (mb_strlen($file) <= 2) {
                continue;
            }

            $file = auto_charset($file, 'web');

            $f = explode('.', $file);
            foreach ($f as $part) {
                $ext = mb_strtolower($part);
            }
            $len = (mb_strlen($ext) + 1) * -1;
            $filename = mb_substr($file, 0, $len);

            $tr .= "
            <tr>
              <td><label class='checkbox'><input type='checkbox' name='files[$filename]' value='{$file}' checked>{$file}</label></td>
              <td><input type='text' name='cf_desc[$filename]' value='{$filename}' class='span12'></td>
            </tr>\n";
        }
        closedir($dh);
    }

    $root = $isAdmin ? "<option value=''>" . _MD_TADUP_ROOT . '</div>' : '';

    $main = "
    <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' class='form-horizontal' enctype='multipart/form-data'>
        <div class='alert alert-success'>
          " . _MA_TADUP_BATCH_UPLOAD_TO . "<span style='color:red;'>" . _TAD_UPLOADER_BATCH_DIR . "</span>
        </div>

        <div class='form-group row'>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>
            " . _MD_TADUP_SELECT_FOLDER . "
          </label>
          <div class='col-sm-4'>
            <select name='cat_sn' size=1 class='form-control'>
              $root
              $cate_select
            </select>
          </div>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>
            " . _MD_TADUP_CREATE_NEW_FOLDER . "
          </label>
          <div class='col-sm-4'>
            <input type='text' name='new_cat_sn' class='form-control'>
          </div>
        </div>


        <table class='table table-striped table-hover'>
            <tr>
            <th>" . _MD_TADUP_FILE_NAME . '</th>
            <th>' . _MD_TADUP_FILE_DESC . "</th>
            </tr>
            $tr
        </table>

        <div class='text-center'>
            <input type='hidden' name='op' value='import'>
            <button type='submit' class='btn btn-primary'>" . _MA_BATCH_SAVE . '</button>
        </div>
    </form>';

    return $main;
}

//批次匯入
function tad_uploader_batch_import()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $TadUpFiles;

    $new_cat_sn = (int) $_POST['new_cat_sn'];
    $cat_sn = (int) $_POST['cat_sn'];

    if (!empty($new_cat_sn)) {
        $cat_sn = add_tad_uploader('', $new_cat_sn, '', '1', $cat_sn, $_POST['cat_add_form']);
    }

    $uid = $xoopsUser->uid();
    $uid_name = XoopsUser::getUnameFromId($uid, 1);
    //$now=xoops_getUserTimestamp(time());

    set_time_limit(0);
    foreach ($_POST['files'] as $filename => $file_path) {
        if (empty($file_path)) {
            continue;
        }

        $file_src = _TAD_UPLOADER_BATCH_DIR . "/{$file_path}";

        $file_src = auto_charset($file_src, 'os');

        $type = mime_content_type($file_src);
        $cf_sort = get_file_max_sort($cat_sn);
        $size = filesize($file_src);

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $sql = 'insert into ' . $xoopsDB->prefix('tad_uploader_file') . " (cat_sn,uid,cf_name,cf_desc,cf_type,cf_size,up_date,cf_sort)
        values('{$cat_sn}','{$uid}','{$file_path}','{$_POST['cf_desc'][$filename]}','{$type}','{$size}','{$now}','{$cf_sort}')";
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $cfsn = $xoopsDB->getInsertId();

        $new_filename = _TAD_UPLOADER_DIR . "/{$cfsn}_{$file_path}";

        $file_src = auto_charset($file_src, 'web');
        $new_filename = auto_charset($new_filename, 'web');

        //複製匯入單一檔案：
        $TadUpFiles->set_dir('subdir', "/user_{$uid}");
        $TadUpFiles->set_col('cfsn', $cfsn);
        $TadUpFiles->import_one_file($file_src, $new_filename, null, null, null, $_POST['cf_desc'][$filename], true, true);

        unlink($file_src);
    }

    //刪除其他多餘檔案
    rrmdir(_TAD_UPLOADER_BATCH_DIR);

    return $cat_sn;
}
