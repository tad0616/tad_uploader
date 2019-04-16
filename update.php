<?php

use XoopsModules\Tad_themes\Utility;

require __DIR__ . '/header.php';
if (!$isAdmin) {
    redirect_header(XOOPS_URL, 3, '非管理員，無權限使用此功能');
}

//取消上傳時間限制
set_time_limit(0);
$total = 0;
$os = (PATH_SEPARATOR === ':') ? 'linux' : 'win';

$sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_uploader_file') . " WHERE `cf_name`!=''";
$result = $xoopsDB->queryF($sql) or /** @scrutinizer ignore-call */web_error($sql, __FILE__, __LINE__);
while (list($cfsn, $cat_sn, $uid, $cf_name, $cf_desc, $cf_type, $cf_size, $cf_count, $up_date, $file_url, $cf_sort) = $xoopsDB->fetchRow($result)) {
    if (empty($cf_name)) {
        continue;
    }

    echo "<h3>$cf_name</h3>";

    $type = explode('/', $cf_type);
    $kind = ('image' === $type[0]) ? 'img' : 'file';
    $kind_dir = ('img' === $kind) ? 'image' : 'file';
    $extarr = explode('.', $cf_name);
    foreach ($extarr as $val) {
        $ext = mb_strtolower($val);
    }

    $safe_file_name = "cfsn_{$cfsn}_1.{$ext}";
    $new_file_name = md5(mt_rand(0, 1000) . $cf_name);

    echo "<div>將「{$cf_name}」轉換為「{$safe_file_name}」及「{$new_file_name}」</div>";
    $from = XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$cfsn}_{$cf_name}";
    $to = XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/{$new_file_name}.{$ext}";
    $readme = XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/{$new_file_name}_info.txt";

    echo "<div>相關路徑如下：
  <ol>
  <li>原始：{$from}</li>
  <li>新檔：{$to}</li>
  <li>說明：{$readme}</li>
  </ol>
  </div>";

    mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}");

    echo '<div>建立：『' . XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}』</div>";

    if ('img' === $kind) {
        mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs");
        echo '<div>建立：『' . XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs』</div>";
        $to_thumb = XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs/{$new_file_name}.{$ext}";
        echo '<div>產生：『' . XOOPS_ROOT_PATH . "/uploads/tad_uploader/user_{$uid}/{$kind_dir}/.thumbs/{$new_file_name}.{$ext}』</div>";
    }

    if ('win' === $os and _CHARSET === 'UTF-8') {
        $from = iconv(_CHARSET, 'Big5', $from);
        $to = iconv(_CHARSET, 'Big5', $to);

        echo '<div>簡檔案名稱轉為 Big5 格式</div>';
    } elseif ('linux' === $os and _CHARSET === 'Big5') {
        $from = iconv(_CHARSET, 'UTF-8', $from);
        $to = iconv(_CHARSET, 'UTF-8', $to);

        echo '<div>簡檔案名稱轉為 UTF-8 格式</div>';
    }

    if (file_exists($from)) {
        if (rename($from, $to)) {
            $sql2 = 'insert into ' . $xoopsDB->prefix('tad_uploader_files_center') . " (`col_name`, `col_sn`, `sort`, `kind`, `file_name`, `file_type`, `file_size`, `description`, `counter`, `original_filename` , `hash_filename` , `sub_dir`) values('cfsn' ,'{$cfsn}' ,'1' ,'{$kind}' ,'{$safe_file_name}' ,'{$cf_type}' ,'{$cf_size}' ,'{$cf_desc}' ,'{$cf_count}' ,'{$cf_name}' ,'{$new_file_name}.{$ext}' ,'/user_{$uid}')";
            echo "<div>執行：「{$sql}」</div>";
            $xoopsDB->queryF($sql2) or /** @scrutinizer ignore-call */web_error($sql2);
            $fp = fopen($readme, 'wb');
            fwrite($fp, $cf_name);
            fclose($fp);
            echo "<div style='color:blue'>產生：「{$cf_name}」完畢</div>";
        } else {
            echo "<div style='color:red'>將{$from}」改名為「{$to}」失敗！</div>";
        }
        $total++;
    } else {
        echo "<div style='color:green'>「{$cf_name}」已存在，故無轉換</div>";
    }
}

echo "<h1>全部轉換完畢，共 {$total} 個檔案</h1>";
