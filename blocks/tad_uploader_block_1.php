<?php

//區塊主函式 (最新上傳文件)
function tad_uploader_b_show_1($options)
{
    global $xoopsDB;

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

    $and_cat_sn = empty($options[1]) ? '' : "and b.cat_sn in({$options[1]})";
    $sql        = "select a.cfsn,a.cat_sn,a.cf_name,a.cf_desc,a.file_url from " . $xoopsDB->prefix("tad_uploader_file") . " as a left join " . $xoopsDB->prefix("tad_uploader") . " as b on a.cat_sn=b.cat_sn where b.cat_share='1'  $and_cat_sn order by a.up_date desc limit 0,{$options[0]}";

    $result = $xoopsDB->query($sql) or web_error($sql);

    $block = "";
    $i     = 0;
    while (list($cfsn, $cat_sn, $cf_name, $cf_desc, $file_url) = $xoopsDB->fetchRow($result)) {

        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (!check_up_power("catalog", $cat_sn)) {
            continue;
        }

        $cf_name = empty($cf_name) ? get_basename($file_url) : $cf_name;

        $link[$i]['title']   = empty($cf_desc) ? $cf_name : $cf_desc;
        $link[$i]['cfsn']    = $cfsn;
        $link[$i]['cat_sn']  = $cat_sn;
        $link[$i]['cf_name'] = $cf_name;
        $i++;
    }
    $block['link'] = $link;

    return $block;
}

//區塊編輯函式
function tad_uploader_b_edit_1($options)
{

    $options1_1 = ($options[1] == "1") ? "checked" : "";
    $options1_0 = ($options[1] == "0") ? "checked" : "";
    $option     = block_uploader_cate($options[1]);

    $form = "
    {$option['js']}
      " . _MB_TADUP_SHOW_NUM . "
      <INPUT type='text' name='options[0]' value='{$options[0]}'><br>
    " . _MB_TADUP_SHOW_CATE . "
      {$option['form']}
      <INPUT type='hidden' name='options[1]' id='bb' value='{$options[1]}'><br>
      ";
    return $form;
}

//判別格式圖檔
function chk_file_pic($file)
{
    $f = explode(".", $file);
    $n = sizeof($f) - 1;
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tad_uploader/images/mime/{$f[$n]}.png")) {
        return "mime.png";
    }

    return "{$f[$n]}.png";
}

if (!function_exists("check_up_power")) {
    //檢查有無上傳權利
    function check_up_power($kind = "catalog", $cat_sn = "")
    {
        global $xoopsUser;

        //取得模組編號
        $modhandler  = xoops_getHandler('module');
        $xoopsModule = $modhandler->getByDirname("tad_uploader");
        $module_id   = $xoopsModule->getVar('mid');

        //取得目前使用者的群組編號
        if ($xoopsUser) {
            $groups  = $xoopsUser->getGroups();
            $isAdmin = $xoopsUser->isAdmin($module_id);
            $uid     = $xoopsUser->getVar('uid');
        } else {
            $groups  = XOOPS_GROUP_ANONYMOUS;
            $isAdmin = false;
        }

        //取得群組權限功能
        $gperm_handler = xoops_getHandler('groupperm');

        //權限項目編號
        $perm_itemid = (int)$cat_sn;
        //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
        if (empty($cat_sn)) {
            if ($kind == "catalog") {
                return true;
            } else {
                if ($isAdmin) {
                    return true;
                }

            }
        } else {
            if ($gperm_handler->checkRight($kind, $cat_sn, $groups, $module_id) or $isAdmin) {
                return true;
            }

        }

        return false;
    }
}

//取得所有類別標題
if (!function_exists("block_uploader_cate")) {
    function block_uploader_cate($selected = "")
    {
        global $xoopsDB;

        if (!empty($selected)) {
            $sc = explode(",", $selected);
        }

        $js = "<script>
            function bbv(){
              i=0;
              var arr = new Array();";

        $sql    = "SELECT cat_sn,cat_title FROM " . $xoopsDB->prefix("tad_uploader") . " WHERE cat_enable='1' ORDER BY cat_sort";
        $result = $xoopsDB->query($sql);
        $option = "";
        while (list($cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {

            $js .= "if(document.getElementById('c{$cat_sn}').checked){
               arr[i] = document.getElementById('c{$cat_sn}').value;
               i++;
              }";
            $ckecked = (in_array($cat_sn, $sc)) ? "checked" : "";
            $option .= "<span style='white-space:nowrap;'><input type='checkbox' id='c{$cat_sn}' value='{$cat_sn}' class='bbv' onChange=bbv() $ckecked><label for='c{$cat_sn}'>$cat_title</label></span> ";
        }

        $js .= "document.getElementById('bb').value=arr.join(',');
    }
    </script>";

        $main['js']   = $js;
        $main['form'] = $option;
        return $main;
    }
}
