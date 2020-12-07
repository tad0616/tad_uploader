<?php
global $xoopsDB;
$userdir = [];
$sql = "select * from xoops2_tad_uploader_file where `cf_name`!=''";
$result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while ($all = $xoopsDB->fetchArray($result)) {
    foreach ($all as $k => $v) {
        $$k = $v;
    }
    if (empty($cf_name)) {
        continue;
    }
    $kind = strpos($cf_type, 'image') !== false ? 'img' : 'file';
    $kind_path = strpos($cf_type, 'image') !== false ? 'image' : 'file';
    $userdir[$uid][$kind] = "/var/www/html/uploads/tad_uploader/user_{$uid}/{$kind_path}/";
}

$all_file = [];
foreach ($userdir as $uid => $path) {
    foreach ($path as $kind => $dir) {
        echo "<h3>$dir</h3>";
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (filetype($dir . $file) == 'file') {
                        if (strpos($file, '_info.txt') !== false) {
                            $filename = file_get_contents($dir . $file);
                            echo "$file = $filename<br>";
                            $all_file[$filename]['kind'] = $kind;
                            $all_file[$filename]['uid'] = $uid;
                            $all_file[$filename]['new_file_name'] = str_replace('_info.txt', '', $file);
                            $all_file[$filename]['upload_date'] = $up_date;
                        }
                    }
                }
                closedir($dh);
            }
        }
    }
}

$sql = "select * from xoops2_tad_uploader_file where `cf_name`!=''";
$result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
while ($all = $xoopsDB->fetchArray($result)) {
    foreach ($all as $k => $v) {
        $$k = $v;
    }
    if (empty($cf_name)) {
        continue;
    }
    $extarr = explode('.', $cf_name);
    $ext = '';
    foreach ($extarr as $val) {
        $ext = mb_strtolower($val);
    }

    $safe_file_name = "cfsn_{$cfsn}_1.{$ext}";
    $new_file_name = $all_file[$cf_name]['new_file_name'];
    $kind = $all_file[$cf_name]['kind'];
    $uid = $all_file[$cf_name]['uid'];
    $upload_date = $all_file[$cf_name]['upload_date'];

    $sql2 = "replace into xoops2_tad_uploader_files_center (`col_name`, `col_sn`, `sort`, `kind`, `file_name`, `file_type`, `file_size`, `description`, `counter`, `original_filename` , `hash_filename` , `sub_dir`, `upload_date`, `uid`) values('cfsn' ,'{$cfsn}' ,'1' ,'{$kind}' ,'{$safe_file_name}' ,'{$cf_type}' ,'{$cf_size}' ,'{$cf_desc}' ,'{$cf_count}' ,'{$cf_name}' ,'{$new_file_name}.{$ext}' ,'/user_{$uid}', '{$upload_date}', '{$uid}')";
    $xoopsDB->queryF($sql2) or Utility::web_error($sql2);
    echo "<div>$sql2</div>";
}
