<{if $files_list}>
    <div class="alert alert-info my-3" id="files_tool" style="display: none;">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right">
                <{$smarty.const._MD_TADUP_SELECTED_FILES}>
            </label>
            <div class="col-sm-4">
                <select name="all_selected" id="all_selected" class="form-control">
                    <option value=""></option>
                    <option value="all_del"><{$smarty.const._MD_TADUP_SELECTED_DEL}></option>
                    <{if $move_option}>
                        <option value="all_move"><{$smarty.const._MD_TADUP_SELECTED_MOVETO}></option>
                    <{/if}>
                </select>
            </div>

            <{if $move_option}>
                <div class="col-sm-5" id="all_move_select" style="display: none;">
                    <select name="new_cat_sn" class="form-control">
                        <option value=0><{$smarty.const._MD_TADUP_ROOT}></option>
                        <{$move_option}>
                    </select>
                </div>
            <{/if}>
            <div class="col-sm-1" id="all_selected_submit" style="display: none;">
                <input name="cat_sn" type="hidden" value="<{$cat_sn}>">
                <button type="submit" name="op" value="batch_file_tools" class="btn btn-primary"><{$smarty.const._TAD_GO}></button>
            </div>
        </div>
    </div>
<{/if}>