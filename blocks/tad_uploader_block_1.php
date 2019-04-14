<?php

//區塊主函式 (最新上傳文件)
function tad_uploader_b_show_1($options)
{
    global $xoopsDB, $xoTheme;
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    require_once XOOPS_ROOT_PATH . '/modules/tadtools/tad_function.php';

    $and_cat_sn = empty($options[1]) ? '' : "and b.cat_sn in({$options[1]})";
    $sql = 'select a.cfsn,a.cat_sn,a.cf_name,a.cf_desc,a.file_url from ' . $xoopsDB->prefix('tad_uploader_file') . ' as a left join ' . $xoopsDB->prefix('tad_uploader') . " as b on a.cat_sn=b.cat_sn where b.cat_share='1'  $and_cat_sn order by a.up_date desc limit 0,{$options[0]}";

    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $block = [];
    $i = 0;
    while (false !== (list($cfsn, $cat_sn, $cf_name, $cf_desc, $file_url) = $xoopsDB->fetchRow($result))) {
        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (!check_up_power('catalog', $cat_sn)) {
            continue;
        }

        $cf_name = empty($cf_name) ? get_basename($file_url) : $cf_name;

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

if (!function_exists('check_up_power')) {
    //檢查有無上傳權利
    function check_up_power($kind = 'catalog', $cat_sn = '')
    {
        global $xoopsUser;

        //取得模組編號
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule = $moduleHandler->getByDirname('tad_uploader');
        $module_id = $xoopsModule->getVar('mid');

        //取得目前使用者的群組編號
        if ($xoopsUser) {
            $groups = $xoopsUser->getGroups();
            $isAdmin = $xoopsUser->isAdmin($module_id);
            $uid = $xoopsUser->uid();
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $isAdmin = false;
        }

        //取得群組權限功能
        $gpermHandler = xoops_getHandler('groupperm');

        //權限項目編號
        $perm_itemid = (int) $cat_sn;
        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (empty($cat_sn)) {
            if ('catalog' === $kind) {
                return true;
            }
            if ($isAdmin) {
                return true;
            }
        } else {
            if ($gpermHandler->checkRight($kind, $cat_sn, $groups, $module_id) or $isAdmin) {
                return true;
            }
        }

        return false;
    }
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
        while (false !== (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result))) {
            $js .= "if(document.getElementById('c{$cat_sn}').checked){
               arr[i] = document.getElementById('c{$cat_sn}').value;
               i++;
              }";
            $ckecked = (in_array($cat_sn, $sc, true)) ? 'checked' : '';
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
