<div id="save_msg"></div>
<div class="row">
    <div class="col-sm-3">
        <{$ztree_code|default:''}>

        <{if $cat_sn!="" and $now_op!="tad_uploader_cate_form"}>
            <div>
                <h3><{$cate.cat_title}></h3>
                <ul>
                    <li style="line-height:2;"><{$smarty.const._MA_TADUP_FILE_COUNTER}><{$smarty.const._TAD_FOR}><{$cate.cat_count}></li>
                    <li style="line-height:2;"><{$smarty.const._MA_TADUP_ENABLE}><{$smarty.const._TAD_FOR}><{if $cate.cat_enable|default:false}><i class="fa fa-check-circle text-success" aria-hidden="true"></i><{else}><i class="fa fa-times-circle text-secondary" aria-hidden="true"></i><{/if}></li>
                    <li style="line-height:2;"><{$smarty.const._MA_TADUP_SHARE}><{$smarty.const._TAD_FOR}><{if $cate.cat_share|default:false}><i class="fa fa-unlock text-success" aria-hidden="true"></i><{else}><i class="fa fa-lock text-danger" aria-hidden="true"></i><{/if}></li>
                </ul>
            </div>
        <{/if}>

        <div class="text-center d-grid gap-2">
            <a href="main.php?op=tad_uploader_cate_form" class="btn btn-info btn-block"><{$smarty.const._MA_TADUP_ADD_FORM}></a>
        </div>
    </div>
    <div class="col-sm-9">
        <{if $cat_sn!="" and $now_op!="tad_uploader_cate_form"}>
            <div class="row">
                <div class="col-sm-4">
                    <h3><a href="../index.php?of_cat_sn=<{$cat_sn|default:''}>"><{$cate.cat_title}></a></h3>
                </div>
                <div class="col-sm-8 text-right text-end">
                    <div style="margin-top: 10px;">
                        <{if $now_op!="tad_uploader_cate_form" and $cat_sn}>
                            <a href="javascript:delete_tad_uploader_func(<{$cate.cat_sn}>);" class="btn btn-danger <{if $cate.count > 0}>disabled<{/if}>"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                            <a href="main.php?op=tad_uploader_cate_form&cat_sn=<{$cat_sn|default:''}>" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                        <{/if}>
                    </div>
                </div>
            </div>

            <{if $cate.cat_desc|default:false}>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success"><{$cate.cat_desc}></div>
                    </div>
                </div>
            <{/if}>
        <{/if}>

        <{if $now_op=="tad_uploader_cate_form"}>
            <{include file="$xoops_rootpath/modules/tad_uploader/templates/op_tad_uploader_cate_form.tpl"}>
        <{elseif $files}>
            <form action="main.php" method="post" class="form-horizontal" role="form">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th nowrap><{$smarty.const._MA_TADUP_FOLDER_NAME}></th>
                        <th nowrap><{$smarty.const._MA_TADUP_FILE_NAME}></th>
                        <th nowrap><{$smarty.const._MA_TADUP_FILE_DATE}></th>
                        <th nowrap><{$smarty.const._MA_TADUP_FILE_SIZE}></th>
                        <th nowrap><{$smarty.const._TAD_FUNCTION}></th>
                    </tr>
                    <tbody>
                        <{foreach from=$files item=file}>
                            <tr>
                                <td>
                                    <a href="main.php?cat_sn=<{$file.cat_sn}>"><{$file.cat_title}></a>
                                </td>
                                <td>
                                    <a href="<{$xoops_url}>/modules/tad_uploader/index.php?of_cat_sn=<{$file.cat_sn}>"><{$file.cf_desc}></a>
                                    <span style="color:gray;font-size: 75%;"> (<{$file.cf_count}>)</span>
                                </td>
                                <td><{$file.up_date}></td>
                                <td><{$file.cf_size}></td>
                                <td>
                                    <a href="javascript:delete_file_func(<{$file.cfsn}>,<{$file.cat_sn}>);" class="btn btn-sm btn-xs btn-danger" id="del<{$file.cat_sn}>"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                                    <a href="<{$xoops_url}>/modules/tad_uploader/uploads.php?cfsn=<{$file.cfsn}>" class="btn btn-sm btn-xs btn-info" id="update<{$file.cat_sn}>"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                                </td>
                            </tr>
                        <{/foreach}>
                    </tbody>
                </table>
                <{$bar|default:''}>
            </form>
        <{else}>
            <div class="alert alert-danger text-center">
                <h3><{$smarty.const._MA_TADUP_EMPTY}></h3>
            </div>
        <{/if}>
    </div>
</div>