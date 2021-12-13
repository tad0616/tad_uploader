<?php
//網路硬碟搜尋程式
function tad_uploader_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();
    if (is_array($queryarray)) {
        foreach ($queryarray as $k => $v) {
            $arr[$k] = $myts->addSlashes($v);
        }
        $queryarray = $arr;
    } else {
        $queryarray = [];
    }
    $sql = 'SELECT `cfsn`,`cf_name`,`up_date`, `uid` FROM ' . $xoopsDB->prefix('tad_uploader_file') . ' WHERE 1';
    if (0 != $userid) {
        $sql .= ' AND uid=' . $userid . ' ';
    }
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`cf_name` LIKE '%{$queryarray[0]}%'  OR `cf_desc` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`cf_name` LIKE '%{$queryarray[$i]}%' OR  `cf_desc` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= 'ORDER BY  `cf_sort` DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret = [];
    $i = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/folder.png';
        $ret[$i]['link'] = 'index.php?op=dlfile&cfsn=' . $myrow['cfsn'];
        $ret[$i]['title'] = $myrow['cf_name'];
        $ret[$i]['time'] = strtotime($myrow['up_date']);
        $ret[$i]['uid'] = $myrow['uid'];
        $i++;
    }

    return $ret;
}
