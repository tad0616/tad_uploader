<script type="text/javascript">
    $(document).ready(function() {
        <{if $file_url|default:false}>
            $('#file_up').hide();
        <{else}>
            $('#file_link').hide();
        <{/if}>
        $("#file_where").change(function() {
            if ($("#file_where").val()=="up") {
                $("#file_up").show();
                $("#file_link").hide();
            } else{
                $("#file_link").show();
                $("#file_up").hide();
            }
        });

    });
</script>

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
