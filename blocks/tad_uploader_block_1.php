<?php
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (最新上傳文件)
function tad_uploader_b_show_1($options)
{
    global $xoopsDB, $xoTheme;

    require_once XOOPS_ROOT_PATH . "/modules/tad_uploader/function_block.php";
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    $and_cat_sn = empty($options[1]) ? '' : "and b.cat_sn in({$options[1]})";
    $sql = 'select a.cfsn,a.cat_sn,a.cf_name,a.cf_desc,a.file_url from ' . $xoopsDB->prefix('tad_uploader_file') . ' as a left join ' . $xoopsDB->prefix('tad_uploader') . " as b on a.cat_sn=b.cat_sn where b.cat_share='1'  $and_cat_sn order by a.up_date desc limit 0,{$options[0]}";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $block = $link = [];
    $i = 0;
    while (list($cfsn, $cat_sn, $cf_name, $cf_desc, $file_url) = $xoopsDB->fetchRow($result)) {
        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (!check_up_power('catalog', $cat_sn)) {
            continue;
        }

        $cf_name = empty($cf_name) ? Utility::get_basename($file_url) : $cf_name;

        $link[$i]['title'] = empty($cf_desc) ? $cf_name : $cf_desc;
        $link[$i]['cfsn'] = $cfsn;
        $link[$i]['cat_sn'] = $cat_sn;
        $link[$i]['cf_name'] = $cf_name;
        $i++;
    }
    $block['link'] = $link;

    return $block;
}

//區塊編輯函式
function tad_uploader_b_edit_1($options)
{
    $options1_1 = ('1' == $options[1]) ? 'checked' : '';
    $options1_0 = ('0' == $options[1]) ? 'checked' : '';
    $option = block_uploader_cate($options[1]);

    $form = "
    {$option['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADUP_SHOW_NUM . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADUP_SHOW_CATE . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[1]' id='bb' value='{$options[1]}'>
            </div>
        </li>
    </ol>";

    return $form;
}

//取得所有類別標題
if (!function_exists('block_uploader_cate')) {
    function block_uploader_cate($selected = '')
    {
        global $xoopsDB;

        if (!empty($selected)) {
            $sc = explode(',', $selected);
        }

        $js = '<script>
            function bbv(){
              i=0;
              var arr = new Array();';

        $sql = 'SELECT cat_sn,cat_title FROM ' . $xoopsDB->prefix('tad_uploader') . " WHERE cat_enable='1' ORDER BY cat_sort";
        $result = $xoopsDB->query($sql);
        $option = '';
        while (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {
            $js .= "if(document.getElementById('c{$cat_sn}').checked){
               arr[i] = document.getElementById('c{$cat_sn}').value;
               i++;
              }";
            $ckecked = (in_array($cat_sn, $sc)) ? 'checked' : '';
            $option .= "<span style='white-space:nowrap;'><input type='checkbox' id='c{$cat_sn}' value='{$cat_sn}' class='bbv' onChange=bbv() $ckecked><label for='c{$cat_sn}'>$cat_title</label></span> ";
        }

        $js .= "document.getElementById('bb').value=arr.join(',');
    }
    </script>";

        $main['js'] = $js;
        $main['form'] = $option;

        return $main;
    }
}
