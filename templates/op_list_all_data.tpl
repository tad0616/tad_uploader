<div style="margin-bottom: 30px;">
    <{$path}>
</div>
<{if $cat_title}>
    <h3><{$cat_title}>
        <{if $up_power and $cat_sn > 0 and $xoops_isuser}>
            <a href="index.php?op=tad_uploader_cate_form&cat_sn=<{$cat_sn}>" class="btn btn-warning btn-sm btn-xs"><{$smarty.const._TAD_EDIT}></a>
        <{/if}>
    </h3>
<{else}>
    <h2 class="sr-only">Files List</h2>
<{/if}>

<div id="save_msg"></div>

<div style="clear:both;"></div>

<{if $cat_desc}>
    <div class="alert alert-info"><{$cat_desc}></div>
<{/if}>

<form action="index.php" method="POST" enctype="multipart/form-data" role="form">
    <{if $list_mode=="icon"}>
        <{includeq file="$xoops_rootpath/modules/tad_uploader/templates/sub_list_all_data_icon.tpl"}>
    <{else}>
        <{includeq file="$xoops_rootpath/modules/tad_uploader/templates/sub_list_all_data.tpl"}>
    <{/if}>
    <{if $up_power and $xoops_isuser}>
        <{includeq file="$xoops_rootpath/modules/tad_uploader/templates/sub_selected_files_tool.tpl"}>
    <{/if}>
</form>


<{if $up_power and $xoops_isuser}>
    <{includeq file="$xoops_rootpath/modules/tad_uploader/templates/sub_js.tpl"}>
    <{includeq file="$xoops_rootpath/modules/tad_uploader/templates/sub_batch_tool.tpl"}>
<{/if}>
