<{$toolbar}>

<{if $up_power}>

  <script type="text/javascript">
  $(document).ready(function()  {

    //***目錄排序****
    $("#dir_sort").sortable({opacity: 0.6, cursor: "move", update: function() {
        var order = $(this).sortable('serialize');
        $.post('save_dir_sort.php', order, function(theResponse){
            $('#save_msg').html(theResponse);
        });
    }
    });


    //***檔案排序****
    $("#sort").sortable({opacity: 0.6, cursor: "move", update: function() {
        var order = $(this).sortable('serialize');
        $.post('save_file_sort.php', order, function(theResponse){
            $('#save_msg').html(theResponse);
        });
    }
    });

    $("#clickAll").click(function() {
      if($("#clickAll").prop("checked")){
        $(".u<{$cat_sn}>").each(function() {
        $(this).prop("checked", true);
        });
      }else{
       $(".u<{$cat_sn}>").each(function() {
           $(this).prop("checked", false);
       });
      }
    });


  });

  </script>
<{/if}>

<div id="save_msg" style="float:right;"></div>

<{$jqueryui}>


<div style="margin-bottom: 30px;">
  <{$path}>
</div>

<div style="clear:both;"></div>

<{if $cat_desc}>
  <div class="card card-body bg-light m-1"><{$cat_desc}></div>
<{/if}>

<form action="index.php" method="POST" enctype="multipart/form-data" role="form">
  <{if $folder_list or $files_list}>

    <{if $list_mode=="icon"}>
      <div class="row">
    <{else}>
      <{$FooTableJS}>
      <table class="footable" id="filetbl">
        <thead>
          <tr class="success">
            <th id="h1" data-class="expand" colspan=2>
              <label class="sr-only" for="clickAll">clickAll</label>
              <div class="checkbox-inline">
                <input type="checkbox" id="clickAll"> <{$smarty.const._MD_TADUP_FILE_NAME}>
              </div>
            </th>
            <th id="h2" data-hide="phone" style="text-align:center;" nowrap>
              <{$smarty.const._MD_TADUP_FILE_DATE}>
            </th>
            <th id="h3" data-hide="phone" style="text-align:center;" nowrap>
              <{$smarty.const._MD_TADUP_FILE_SIZE}>
            </th>
            <th id="h4" data-hide="phone" style="text-align:center;" nowrap>
              <{$smarty.const._MD_TADUP_FILE_COUNTER}>
            </th>

            <{if $only_show_desc!="1"}>
              <th id="h5" data-hide="phone"><{$smarty.const._MD_TADUP_FILE_DESC}></th>
            <{/if}>

            <{if $up_power}>
              <th id="h6" data-hide="phone" style="text-align:center;">
                <{$smarty.const._TAD_FUNCTION}>
              </th>
            <{/if}>
          </tr>
        </thead>
    <{/if}>


    <{if $folder_list}>
      <{if $list_mode=="icon"}>

        <ul id="dir_sort" style="display:inline;">
        <{foreach from=$folder_list item=folder}>
          <li id="tr_<{$folder.cat_sn}>" style="display:inline;margin:2px;width:<{$icon_width}>;height:130px;float:left;">
            <a href="index.php?of_cat_sn=<{$folder.cat_sn}>" style="display:block;height:64px;overflow:hidden;margin:0px auto;text-align:center;">
            <img src="images/folder<{$folder.lock}>.gif" alt="folder">
            </a>
            <div style="overflow: hidden;width:100%;height:50px;text-align:center;">
              <a href="index.php?of_cat_sn=<{$folder.cat_sn}>" style="font-size:12px;text-align:left;">
            <{$folder.cat_title}></a>(<{$folder.file_num}>)
            </div>
          </li>
        <{/foreach}>
        </ul>
      <{else}>

        <tbody id="dir_sort">
        <{foreach from=$folder_list item=folder}>
          <tr id="tr_<{$folder.cat_sn}>">
            <td headers="h1" colspan=3>
              <{if $up_power}>
                <label>
              <{/if}>
              <img src="images/s_folder<{$folder.lock}>.png" hspace="2" alt="folder" align="absmiddle"><a href="index.php?of_cat_sn=<{$folder.cat_sn}>"><{$folder.cat_title}></a>
              <{if $up_power}>
                </label>
              <{/if}>
            </td>
            <td headers="h3" style="font-size:11px;text-align:center;"><{$folder.file_num}><{$smarty.const._MD_TADUP_FILE}></td>
            <td headers="h4" style="font-size:11px;text-align:center;"><{$folder.cat_count}></td>
            <{if $only_show_desc!="1"}>
              <td headers="h5" style="font-size:12px;"><{$folder.cat_desc}></td>
            <{/if}>
            <{if $up_power}>
              <td headers="h6" style="text-align:center;">
                <{if $folder.file_num==0}>
                  <a href="javascript:delete_tad_uploader_func(<{$folder.cat_sn}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                <{/if}>
              </td>
            <{/if}>
          </tr>
        <{/foreach}>
        </tbody>
      <{/if}>
    <{/if}>

    <{if $files_list}>
      <{if $list_mode=="icon"}>
        <ul id="sort" style="display:inline;">
          <{foreach from=$files_list item=file}>
            <li id="tr_<{$file.cfsn}>" style="display:inline;margin:2px;width:<{$icon_width}>;height:130px;float:left;">
              <a href="index.php?op=dlfile&cfsn=<{$file.cfsn}>&cat_sn=<{$file.cat_sn}>" style="display:block;height:64px;overflow:hidden;margin:0px auto;text-align:center;">
              <img src="<{$file.pic}>" alt="<{$file.cf_desc}>" title="<{$file.cf_desc}>" class="rounded" style="<{$file.thumb_style}>;">
              </a>

              <div style="overflow: hidden;width:100%;height:50px;text-align:center;">
                <{if $up_power}>
                  <label class="checkbox-inline">
                  <input type="checkbox" name="select_files[<{$file.cfsn}>]" value="<{$file.cf_name}>" class="u<{$file.cat_sn}>">
                <{/if}>

                <a href="index.php?op=dlfile&cfsn=<{$file.cfsn}>&cat_sn=<{$file.cat_sn}>" style="display:inline-block;width:auto;line-height:110%;font-size:12px;text-align:left;margin:0px auto;">

                <{if $only_show_desc=="1"}>
                  <{$file.cf_desc}>
                <{else}>
                  <{$file.cf_name}>
                <{/if}>
                </a>
                <{if $up_power}>
                  </label>
                <{/if}>
              </div>
            </li>
          <{/foreach}>
        </ul>
      <{else}>
        <tbody id="sort">
          <{foreach from=$files_list item=file}>
            <tr id="tr_<{$file.cfsn}>">
              <td headers="h1" style="max-width: 16px;">
                <{if $up_power}>
                  <input type="checkbox" name="select_files[<{$file.cfsn}>]" value="<{$file.cf_name}>" class="u<{$file.cat_sn}>">
                <{/if}>
              </td>
              <td headers="h2" nowrap>
                <{if $file.pic}>
                  <div style="width:24px;height:24px;background-image: url(<{$file.pic}>);float:left;background-size:cover;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;margin-right:2px;"></div>
                <{/if}>
                <a href="index.php?op=dlfile&cfsn=<{$file.cfsn}>&cat_sn=<{$file.cat_sn}>&name=<{$file.fname}>">
                <{if $only_show_desc=="1"}>
                  <{$file.cf_desc}>
                <{else}>
                  <{$file.cf_name}>
                <{/if}>
                </a>
              </td>
              <td headers="h3" style="font-size:11px;text-align:center;"><{$file.up_date}></td>
              <td headers="h4" style="font-size:11px;text-align:center;"><{$file.size}></td>
              <td style="font-size:11px;text-align:center;"><{$file.cf_count}></td>

              <{if $only_show_desc!="1"}>
                <td headers="h5" style="font-size:12px;">
                <{if $file.cf_desc!=$file.cf_name}>
                <{$file.cf_desc}>
                <{/if}></td>
              <{/if}>

              <{if $up_power}>
                <td headers="h6" style="text-align:center;">
                   <a href="javascript:delete_file_func(<{$file.cfsn}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                  <a href="uploads.php?cfsn=<{$file.cfsn}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
              <{/if}>
            </tr>
          <{/foreach}>
        </tbody>
      <{/if}>
    <{/if}>

    <{if $list_mode=="icon"}>
      </div>
    <{else}>
      </table>
    <{/if}>
  <{/if}>

  <{if $up_power}>
    <script type="text/javascript">
      $(document).ready(function() {
          $("form").submit(function(event) {
              var path = $(this).find("select[name='add_to_cat']").val();
              var new_cate = $(this).find("input[name='creat_new_cat']").val();
              if(path == 0 && new_cate.length == 0) {
                  swal("根目錄不能上傳喔，請重新選擇目錄或新增目錄")
                  return false;
              }
              return true;
          });
      });
  </script>
    <div class="card card-body bg-light m-1">
      <div class="form-group row">
      <{if $files_list}>
        <div class="col-sm-5">
          <label class="radio-inline">
            <input type="radio" name="all_selected"  value="all_del"><{$smarty.const._MD_TADUP_SELECTED_DEL}>
          </label>
        </div>

        <{if $move_option}>
          <label class="radio-inline col-sm-3">
            <input type="radio" name="all_selected"  value="all_move"><{$smarty.const._MD_TADUP_SELECTED_MOVETO}>
          </label>
          <div class="col-sm-4">
            <select name="new_cat_sn" class="form-control">
              <option value=0><{$smarty.const._MD_TADUP_ROOT}></option>
              <{$move_option}>
            </select>
          </div>
        <{/if}>
      <{/if}>
      </div>

      <div class="form-group row">
        <label class="col-sm-2 col-form-label text-sm-right">
          <{$smarty.const._MD_TADUP_SELECT_FOLDER}><{$smarty.const._TAD_FOR}>
        </label>
        <div class="col-sm-4">
          <select name="add_to_cat" size=1 class="form-control">
            <option value="0"><{$smarty.const._MD_TADUP_ROOT}></option>
            <{$menu_option}>
          </select>
        </div>
        <div class="col-sm-6">
          <input type="text" name="creat_new_cat" class="form-control" placeholder="<{$smarty.const._MD_TADUP_CREAT_NEW_CATE}>">
        </div>
      </div>


      <div class="form-group row">
        <label class="col-sm-2 col-form-label text-sm-right">
          <{$smarty.const._MD_TADUP_SELECT_FILES}><{$smarty.const._TAD_FOR}>
        </label>
        <div class="col-sm-4">
          <{$upform}>
        </div>
        <div class="col-sm-6">
          <input type="text" name="cf_desc" class="form-control"  value="<{$cf_desc}>" placeholder="<{$smarty.const._MD_TADUP_FILE_DESC}>">
        </div>
      </div>


      <div class="text-center">
        <input name="op" type="hidden" value="save_files">
        <input name="cat_sn" type="hidden" value="<{$cat_sn}>">
        <button type="button" class="btn"><{$smarty.const._TAD_RESET}></button>
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_GO}></button>
      </div>

    </div>

    <div class="alert alert-info">
    php.ini: memory_limit (<{$memory_limit}>) > post_max_size (<{$post_max_size}>) >      upload_max_filesize (<{$upload_max_filesize}>) ;
    max_execution_time=<{$max_execution_time}>
    </div>
  <{/if}>
</form>
