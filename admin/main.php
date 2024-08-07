<?php
use Xmf\Request;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tad_uploader_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$cat_sn = Request::getInt('cat_sn');
$of_cat_sn = Request::getInt('of_cat_sn');
$cfsn = Request::getInt('cfsn');

switch ($op) {
    case 'add_tad_uploader':
        list_tad_uploader_cate_tree($cat_sn);
        add_tad_uploader($cat_sn, $_POST['cat_title'], $_POST['cat_desc'], $_POST['cat_enable'], $of_cat_sn, $_POST['add_to_cat'], $_POST['cat_share'], $_POST['cat_sort'], $_POST['cat_count'], $_POST['catalog'], $_POST['catalog_up'], 'admin');
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //刪除資料
    case 'delete_tad_uploader':
        delete_tad_uploader($cat_sn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //分類設定
    case 'tad_uploader_cate_form':
        list_tad_uploader_cate_tree($cat_sn);
        tad_uploader_cate_form($cat_sn);
        break;

    case 'del_file':
        del_file($cfsn, true, $of_cat_sn);
        header("location: {$_SERVER['PHP_SELF']}?of_cat_sn={$of_cat_sn}");
        exit;

    default:
        list_tad_uploader_cate_tree($cat_sn);
        list_tad_uploader($cat_sn);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_uploader_cate資料
function list_tad_uploader_cate_tree($def_cat_sn = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'SELECT cat_sn , count(*) FROM ' . $xoopsDB->prefix('tad_uploader_file') . ' GROUP BY cat_sn';
    $result = $xoopsDB->query($sql);
    while (list($cat_sn, $counter) = $xoopsDB->fetchRow($result)) {
        $cate_count[$cat_sn] = $counter;
    }
    $path = get_tad_uploader_cate_path($def_cat_sn);
    $path_arr = array_keys($path);

    $data[] = "{ id:0, pId:0, name:'All', url:'main.php', target:'_self', open:true}";

    $sql = 'SELECT cat_sn,of_cat_sn,cat_title FROM ' . $xoopsDB->prefix('tad_uploader') . '  ORDER BY cat_sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($cat_sn, $of_cat_sn, $cat_title) = $xoopsDB->fetchRow($result)) {
        $font_style = $def_cat_sn == $cat_sn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        //$open            = in_array($cat_sn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$cat_sn]) ? '' : " ({$cate_count[$cat_sn]})";
        $data[] = "{ id:{$cat_sn}, pId:{$of_cat_sn}, name:'{$cat_title}{$display_counter}', url:'main.php?cat_sn={$cat_sn}', open: true ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    $Ztree = new Ztree('news_tree', $json, 'save_drag.php', 'save_sort.php', 'of_cat_sn', 'cat_sn');
    $ztree_code = $Ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

//列出所有tad_uploader資料
function list_tad_uploader($cat_sn = '')
{
    global $xoopsDB, $xoopsTpl;

    $and = !empty($cat_sn) ? "and a.cat_sn='{$cat_sn}'" : '';
    Utility::get_jquery(true);
    $sql = 'select a.*,b.cat_title from ' . $xoopsDB->prefix('tad_uploader_file') . ' as a left join  ' . $xoopsDB->prefix('tad_uploader') . " as b on a.cat_sn=b.cat_sn where 1 $and order by up_date desc";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, $to_limit, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $files = [];
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        $files[] = $all;
    }
    $cate = get_cate_data($cat_sn);
    $xoopsTpl->assign('files', $files);
    $xoopsTpl->assign('cate', $cate);
    $xoopsTpl->assign('cat_sn', $cat_sn);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('total', $total);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_uploader_func', 'main.php?op=delete_tad_uploader&cat_sn=', 'cat_sn');
    $SweetAlert2 = new SweetAlert();
    $SweetAlert2->render('delete_file_func', "main.php?op=del_file&cat_sn={$cat_sn}&cfsn=", 'cfsn');

}

//取得所有資料夾列表
function get_cate_data($cat_sn = 0)
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_uploader') . " where cat_sn='$cat_sn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = [];

    list($cat_sn, $cat_title, $cat_desc, $cat_enable, $uid, $of_cat_sn, $cat_share, $cat_sort, $cat_count) = $xoopsDB->fetchRow($result);

    $cat_desc = nl2br($cat_desc);
    $uid_name = \XoopsUser::getUnameFromId($uid, 1);
    $uid_name = (empty($uid_name)) ? XoopsUser::getUnameFromId($uid, 0) : $uid_name;

    $enable = ('1' == $cat_enable) ? "<img src='../images/button_ok.png'>" : "<img src='../images/button_cancel.png'>";
    $share = ('1' == $cat_share) ? "<img src='../images/button_ok.png'>" : "<img src='../images/encrypted.gif'>";

    $data['cat_sort'] = $cat_sort;
    $data['cat_title'] = $cat_title;
    $data['cat_desc'] = $cat_desc;
    $data['uid_name'] = $uid_name;
    $data['enable'] = $enable;
    $data['share'] = $share;
    $data['cat_count'] = $cat_count;
    $data['cat_sn'] = $cat_sn;

    return $data;
}
