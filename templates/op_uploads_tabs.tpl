<div id="uploadTab">
    <ul class="resp-tabs-list vert">
        <li> <{$smarty.const._MD_TADUP_UPLOAD_ONE}> </li>
        <li> <{$smarty.const._MD_TADUP_BATCH_UPLOAD}> </li>
    </ul>

    <div class="resp-tabs-container vert">
        <div>
            <{include file="$xoops_rootpath/modules/tad_uploader/templates/sub_upload_form.tpl"}>
        </div>
        <div>
            <{include file="$xoops_rootpath/modules/tad_uploader/templates/sub_upload_batch.tpl"}>
        </div>
    </div>
</div>
