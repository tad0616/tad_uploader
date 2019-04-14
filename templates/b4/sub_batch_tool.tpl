<form action="index.php" method="POST">
    <div class="alert alert-warning my-3">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right">
                <{$smarty.const._MD_TADUP_FOLDER}>
            </label>
            <div class="col-sm-4">
                <select name="this_folder" id="this_folder" class="form-control">
                    <option value=""></option>
                    <option value="add_cat_title"><{$smarty.const._MD_TADUP_FOLDER_ADD}></option>
                    <{if $cat_sn > 0}>
                        <option value="new_cat_title"><{$smarty.const._MD_TADUP_FOLDER_RENAME}></option>
                        <{if $move_option}>
                            <option value="new_of_cat_sn"><{$smarty.const._MD_TADUP_FOLDER_MOVE}></option>
                        <{/if}>
                    <{/if}>
                </select>
            </div>
            <div class="col-sm-5" id="new_cat_title_input" style="display: none;">
                <input type="text" name="new_cat_title" class="form-control" value="<{$cat_title}>">
            </div>
            <div class="col-sm-5" id="add_cat_title_input" style="display: none;">
                <input type="hidden" name="add_to_cat" value="<{$smarty.get.of_cat_sn}>">
                <input type="text" name="add_cat_title" class="form-control" placeholder="<{$smarty.const._MD_TADUP_NEW_FOLDER}>">
            </div>
            <{if $move_option}>
                <div class="col-sm-5" id="new_of_cat_sn_select" style="display: none;">
                    <select name='new_of_cat_sn' class="form-control">
                        <option value=0><{$smarty.const._MD_TADUP_ROOT}></option>
                        <{$move_option}>
                    </select>
                </div>
            <{/if}>
            <div class="col-sm-1" id="this_folder_submit" style="display: none;">
                <input name="cat_sn" type="hidden" value="<{$cat_sn}>">
                <button type="submit" name="op" value="batch_dir_tools" class="btn btn-primary"><{$smarty.const._TAD_GO}></button>
            </div>
        </div>
    </div>
</form>

<{if $smarty.get.of_cat_sn > 0}>
    <form action="index.php" method="POST" enctype="multipart/form-data" role="form">
        <div class="alert alert-success my-3">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right">
                    <{$smarty.const._MD_TADUP_SELECT_FILES}><{$smarty.const._TAD_FOR}>
                </label>
                <div class="col-sm-4">
                    <input type="hidden" name="add_to_cat" value="<{$smarty.get.of_cat_sn}>">
                    <{$upform}>
                </div>
                <div class="col-sm-5">
                    <input type="text" name="cf_desc" class="form-control"  value="<{$cf_desc}>" placeholder="<{$smarty.const._MD_TADUP_FILE_DESC}>">
                </div>
                <div class="col-sm-1">
                    <input name="cat_sn" type="hidden" value="<{$cat_sn}>">
                    <button type="submit" name="op" value="save_files" class="btn btn-primary"><{$smarty.const._MD_TADUP_UPLOAD_FILE}></button>
                </div>
            </div>
        </div>
    </form>
<{/if}>

<{if $memory_limit <= $post_max_size or $post_max_size <= $upload_max_filesize}>
    <div class="alert alert-danger">
        php.ini: memory_limit (<{$memory_limit}>M) > post_max_size (<{$post_max_size}>M) >      upload_max_filesize (<{$upload_max_filesize}>M) ;
        max_execution_time=<{$max_execution_time}>
    </div>
<{/if}>