<{if $files_list|default:false}>
    <div class="alert alert-info" id="files_tool" style="margin:10px auto;display: none;">
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-sm-right text-sm-end">
                <{$smarty.const._MD_TADUP_SELECTED_FILES}>
            </label>
            <div class="col-sm-4">
                <select name="all_selected" id="all_selected" class="form-select">
                    <option value=""></option>
                    <option value="all_del"><{$smarty.const._MD_TADUP_SELECTED_DEL}></option>
                    <{if $move_option|default:false}>
                        <option value="all_move"><{$smarty.const._MD_TADUP_SELECTED_MOVETO}></option>
                    <{/if}>
                </select>
            </div>

            <{if $move_option|default:false}>
                <div class="col-sm-5" id="all_move_select" style="display: none;">
                    <select name="new_cat_sn" class="form-select">
                        <option value=0><{$smarty.const._MD_TADUP_ROOT}></option>
                        <{$move_option|default:''}>
                    </select>
                </div>
            <{/if}>
            <div class="col-sm-1" id="all_selected_submit" style="display: none;">
                <input name="cat_sn" type="hidden" value="<{$cat_sn|default:''}>">
                <button type="submit" name="op" value="batch_file_tools" class="btn btn-primary"><{$smarty.const._TAD_GO}></button>
            </div>
        </div>
    </div>
<{/if}>