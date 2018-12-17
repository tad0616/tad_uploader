<{$toolbar}>

<script type="text/javascript">
  $(document).ready(function() {
    var $tabs = $("#jquery-tabs<{$randStr}>").tabs();
    <{$to_batch_upload}>

    <{$hide}>
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

<div id="jquery-tabs<{$randStr}>">

  <ul>
      <li><a href="#fragment-1"><span><{$smarty.const._MD_TADUP_UPLOAD_ONE}></span></a></li>
      <li><a href="batch_upload.php"><span><{$smarty.const._MD_TADUP_BATCH_UPLOAD}></span></a></li>
  </ul>

  <div id="fragment-1">
    <form action="uploads.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TADUP_SELECT_FOLDER}><{$smarty.const._TAD_FOR}>
        </label>
        <div class="col-sm-4">
          <select name="add_to_cat" size=1 class="form-control">
            <option value="0"><{$smarty.const._MD_TADUP_ROOT}></option>
            <{$cate_select}>
          </select>
        </div>
        <div class="col-sm-6">
          <input type="text" name="creat_new_cat" class="form-control" placeholder="<{$smarty.const._MD_TADUP_CREAT_NEW_CATE}>">
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-2">
          <select id="file_where" class="form-control">
            <option value="up" <{$selected_up}>><{$smarty.const._MD_TADUP_UPLOAD}></option>
            <option value="link" <{$selected_link}>><{$smarty.const._MD_TADUP_LINK}></option>
          </select>
        </div>
        <div id="file_up" class="col-sm-10">
          <{$upform}>
        </div>
        <div id="file_link" class="col-sm-10">
          <input type="text" name="file_url" class="form-control" value="<{$file_url}>" placeholder="<{$smarty.const._MD_TADUP_INPUT_LINK}>">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TADUP_FILE_DESC}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="cf_desc" class="form-control"  value="<{$cf_desc}>">
        </div>
      </div>


      <div class="text-center">
        <{if $cfsn}>
          <label class="checkbox-inline">
          <input type="checkbox" name="new_date" value="1"><{$smarty.const._MD_TADUP_UPDATE_TO_NEW_DATE}>
          </label>
        <{/if}>
        <input type="hidden" name="op" value="<{$op}>">
        <input type="hidden" name="cfsn" value="<{$cfsn}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TADUP_SAVE}></button>
      </div>

    </form>
  </div>
</div>