<form action="uploads.php" method="post" id="myForm" class="form-horizontal" enctype="multipart/form-data">
    <div class="alert alert-success">
        <{$smarty.const._MD_TADUP_BATCH_UPLOAD_TO}>
        <span style="color: red">
            <{$smarty.const._TAD_UPLOADER_BATCH_DIR}>
        </span>
    </div>

    <div class="form-group row mb-3">
        <label class="col-sm-2 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADUP_SELECT_FOLDER}>
        </label>
        <div class="col-sm-4">
            <select name="cat_sn" size="1" class="form-control">
                <{if $smarty.session.tad_upload_adm|default:false}>
                    <option value=""><{$smarty.const._MD_TADUP_ROOT}></div>
                <{/if}>
                <{$cate_select|default:''}>
            </select>
        </div>
        <label class="col-sm-2 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADUP_CREATE_NEW_FOLDER}>
        </label>
        <div class="col-sm-4">
            <input type="text" name="new_cat_sn" class="form-control" />
        </div>
    </div>

    <table class="table table-striped table-hover">
        <tr>
            <th>
                <{$smarty.const._MD_TADUP_FILE_NAME}>
            </th>
            <th><{$smarty.const._MD_TADUP_FILE_DESC}></th>
        </tr>

        <{foreach from=$all_file item=f}>
            <tr>
                <td style="word-wrap: break-word;">
                    <div class="form-check-inline checkbox-inline">
                        <label class="form-check-label">
                            <input type="checkbox" name="files[<{$f.filename}>]" value="<{$f.file}>"  class="form-check-input" checked>
                            <{$f.file}>
                        </label>
                    </div>
                </td>
                <td>
                    <input type="text" name="cf_desc[<{$f.filename}>]" value="<{$f.filename}>" class="form-control">
                </td>
            </tr>
        <{/foreach}>
    </table>

    <div class="text-center">
        <input type="hidden" name="op" value="import" />
        <button type="submit" class="btn btn-primary">
            <{$smarty.const._MD_BATCH_SAVE}>
        </button>
    </div>
</form>