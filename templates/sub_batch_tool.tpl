<form action="index.php" method="POST" class="form-horizontal">
    <div class="alert alert-warning" style="margin: 10px auto;">
        <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TADUP_FOLDER}>
            </label>
            <div class="col-sm-4">
                <select name="this_folder" id="this_folder" class="form-control form-select">
                    <option value=""></option>
                    <option value="add_cat_title"><{$smarty.const._MD_TADUP_FOLDER_ADD}></option>
                    <{if $cat_sn > 0}>
                        <option value="new_cat_title"><{$smarty.const._MD_TADUP_FOLDER_RENAME}></option>
                        <{if $move_option|default:false}>
                            <option value="new_of_cat_sn"><{$smarty.const._MD_TADUP_FOLDER_MOVE}></option>
                        <{/if}>
                    <{/if}>
                </select>
            </div>
            <div class="col-sm-4" id="new_cat_title_input" style="display: none;">
                <input type="text" name="new_cat_title" class="form-control" value="<{$cat_title|default:''}>">
            </div>
            <div class="col-sm-4" id="add_cat_title_input" style="display: none;">
                <input type="hidden" name="add_to_cat" value="<{$smarty.get.of_cat_sn|intval}>">
                <input type="text" name="add_cat_title" class="form-control" placeholder="<{$smarty.const._MD_TADUP_NEW_FOLDER}>">
            </div>
            <{if $move_option|default:false}>
                <div class="col-sm-4" id="new_of_cat_sn_select" style="display: none;">
                    <select name='new_of_cat_sn' class="form-control form-select">
                        <option value=0><{$smarty.const._MD_TADUP_ROOT}></option>
                        <{$move_option|default:''}>
                    </select>
                </div>
            <{/if}>
            <div class="col-sm-2" id="this_folder_submit" style="display: none;">
                <input name="cat_sn" type="hidden" value="<{$cat_sn|default:''}>">
                <button type="submit" name="op" value="batch_dir_tools" class="btn btn-primary"><{$smarty.const._TAD_GO}></button>
            </div>
        </div>
    </div>
</form>

<{if $smarty.get.of_cat_sn > 0}>
<div class="alert alert-success" style="margin: 10px auto;">
    <{include file="$xoops_rootpath/modules/tad_uploader/templates/sub_upload_form.tpl" add_to_cat=$smarty.get.of_cat_sn op="insert_tad_uploader"}>
</div>
<{/if}>

<{if $memory_limit <= $post_max_size or $post_max_size <= $upload_max_filesize}>
    <div class="alert alert-danger">
        php.ini: memory_limit (<{$memory_limit|default:''}>M) > post_max_size (<{$post_max_size|default:''}>M) >      upload_max_filesize (<{$upload_max_filesize|default:''}>M) ;
        max_execution_time=<{$max_execution_time|default:''}>
    </div>
<{/if}>