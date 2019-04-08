
<{$formValidator_code}>
<h3><{$smarty.const._MA_TADUP_ADD_FORM}></h3>

<form action="main.php" method="POST" enctype="multipart/form-data" role="form">
    <div class="row">
    <div class="col-sm-5">

        <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right">
            <{$smarty.const._MA_TADUP_FATHER_FOLDER}>
        </label>
        <div class="col-sm-8">
            <select name="of_cat_sn" class="form-control" id= "of_cat_sn">
            <option value=""></option>
            <{$cata_select}>
            </select>
        </div>
        </div>

        <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right">
            <{$smarty.const._MA_TADUP_FOLDER_NAME}>
        </label>
        <div class="col-sm-8">
            <input type="text" name="cat_title" value="<{$cat_title}>" class="form-control" placeholder="<{$smarty.const._MA_TADUP_FOLDER_NAME}>">
        </div>
        </div>

        <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right">
            <{$smarty.const._MA_TADUP_ENABLE}>
        </label>
        <div class="col-sm-8">
            <label class="radio-inline">
            <input type="radio" name="cat_enable" id="cat_enable1" value="1" <{if $cat_enable=="1"}>checked<{/if}>><{$smarty.const._YES}>
            </label>
            <label class="radio-inline">
            <input type="radio" name="cat_enable" id="cat_enable0" value="0" <{if $cat_enable=="0"}>checked<{/if}>><{$smarty.const._NO}>
            </label>
        </div>
        </div>


        <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right">
            <{$smarty.const._MA_TADUP_SHARE}>
        </label>
        <div class="col-sm-8">
            <label class="radio-inline">
            <input type="radio" name="cat_share" id="cat_share1" value="1" <{if $cat_share=="1"}>checked<{/if}>><{$smarty.const._YES}>
            </label>
            <label class="radio-inline">
            <input type="radio" name="cat_share" id="cat_share0" value="0" <{if $cat_share=="0"}>checked<{/if}>><{$smarty.const._NO}>
            </label>
        </div>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="row">
        <label><{$smarty.const._MA_TADUP_CAN_ACCESS_GROUPS2}></label>
        <{$enable_group}>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="row">
        <label><{$smarty.const._MA_TADUP_CAN_UPLOADS_GROUPS}></label>
        <{$enable_upload_group}>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="row">
        <label><{$smarty.const._MA_TADUP_FOLDER_DESC}></label>
        <textarea name="cat_desc" class="form-control" style="height:120px;font-size:12px"><{$cat_desc}></textarea>
        </div>
    </div>

    </div>

    <div class="text-center">
    <input type="hidden" name="cat_sn" value="<{$cat_sn}>">
    <input type="hidden" name="cat_sort" value="<{$cat_sort}>">
    <input type="hidden" name="cat_count" value="<{$cat_count}>">
    <input type="hidden" name="op" value="add_tad_uploader">
    <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADUP_SAVE}></button>
    </div>
</form>