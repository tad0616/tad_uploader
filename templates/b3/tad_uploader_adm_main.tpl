<div class="container-fluid">
  <div id="save_msg"></div>
  <div class="row">
    <div class="col-sm-3">
      <{$ztree_code}>

      <{if $cat_sn!="" and $op!="tad_uploader_cate_form"}>
        <div>
          <h3><{$cate.cat_title}></h3>
          <ul>
            <li style="line-height:2;"><{$smarty.const._MA_TADUP_FILE_COUNTER}><{$smarty.const._TAD_FOR}><{$cate.cat_count}></li>
            <li style="line-height:2;"><{$smarty.const._MA_TADUP_ENABLE}><{$smarty.const._TAD_FOR}><{$cate.enable}></li>
            <li style="line-height:2;"><{$smarty.const._MA_TADUP_SHARE}><{$smarty.const._TAD_FOR}><{$cate.share}></li>
          </ul>
        </div>
      <{/if}>

      <div class="text-center">
        <a href="main.php?op=tad_uploader_cate_form" class="btn btn-info btn-block"><{$smarty.const._MA_TADUP_ADD_FORM}></a>
      </div>
    </div>
    <div class="col-sm-9">
      <{if $cat_sn!="" and $op!="tad_uploader_cate_form"}>
        <div class="row">
          <div class="col-sm-4">
            <h3>
              <{$cate.cat_title}>
            </h3>
          </div>
          <div class="col-sm-8 text-right">
            <div style="margin-top: 10px;">
              <{if $op!="tad_uploader_cate_form" and $cat_sn}>
                <a href="javascript:delete_tad_uploader_func(<{$cate.cat_sn}>);" class="btn btn-danger <{if $cate.count > 0}>disabled<{/if}>"><{$smarty.const._TAD_DEL}></a>
                <a href="main.php?op=tad_uploader_cate_form&cat_sn=<{$cat_sn}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
              <{/if}>
            </div>
          </div>
        </div>

        <{if $cate.cat_desc}>
          <div class="row">
            <div class="col-sm-12">
              <div class="alert alert-success"><{$cate.cat_desc}></div>
            </div>
          </div>
        <{/if}>
      <{/if}>

      <{if $op=="tad_uploader_cate_form"}>

        <{$formValidator_code}>
        <h3><{$smarty.const._MA_TADUP_ADD_FORM}></h3>

        <form action="main.php" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="row">
            <div class="col-sm-5">

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MA_TADUP_FATHER_FOLDER}>
                </label>
                <div class="col-sm-8">
                  <select name="of_cat_sn" class="form-control" id= "of_cat_sn">
                    <option value=""></option>
                    <{$cata_select}>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MA_TADUP_FOLDER_NAME}>
                </label>
                <div class="col-sm-8">
                  <input type="text" name="cat_title" value="<{$cat_title}>" class="form-control" placeholder="<{$smarty.const._MA_TADUP_FOLDER_NAME}>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
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


              <div class="form-group">
                <label class="col-sm-3 control-label">
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
                    <span style="color:gray;font-size:12px;"> (<{$file.cf_count}>)</span>
                  </td>
                  <td><{$file.up_date}></td>
                  <td><{$file.cf_size}></td>
                  <td>
                    <a href="javascript:delete_file_func(<{$file.cfsn}>,<{$file.cat_sn}>);" class="btn btn-xs btn-danger" id="del<{$file.cat_sn}>"><{$smarty.const._TAD_DEL}></a>
                    <a href="<{$xoops_url}>/modules/tad_uploader/uploads.php?cfsn=<{$file.cfsn}>" class="btn btn-xs btn-info" id="update<{$file.cat_sn}>"><{$smarty.const._TAD_EDIT}></a>
                  </td>
                </tr>
              <{/foreach}>
            </tbody>
          </table>
          <{$bar}>
        </form>
      <{else}>
        <div class="alert alert-danger text-center">
          <h3><{$smarty.const._MA_TADUP_EMPTY}></h3>
        </div>
      <{/if}>
    </div>
  </div>
</div>