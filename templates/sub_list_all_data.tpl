    <{if $folder_list or $files_list}>
        <table class="footable" id="filetbl">
            <thead>
                <tr class="success">
                    <th id="h1" data-class="expand" colspan=2>
                        <label class="sr-only" for="clickAll">clickAll</label>
                        <div class="checkbox-inline">
                            <input type="checkbox" id="clickAll" onChange="chk_selected_files();"> <{$smarty.const._MD_TADUP_FILE_NAME}>
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


        <{if $folder_list}>
            <tbody id="dir_sort">
                <{foreach from=$folder_list item=folder}>
                    <tr id="tr_<{$folder.cat_sn}>">
                        <td headers="h1" colspan=3>
                            <{if $up_power}>
                                <label>
                            <{/if}>
                            <{if $folder.lock}>
                                <img src="images/folder_lock.png" alt="folder" style="width: 24px;">
                            <{elseif $folder.file_num > 0}>
                                <img src="images/folder_full.png" alt="folder" style="width: 24px;">
                            <{else}>
                                <img src="images/folder_empty.png" alt="folder" style="width: 24px;">
                            <{/if}>
                            <a href="index.php?of_cat_sn=<{$folder.cat_sn}>"><{$folder.cat_title}></a>
                            <{if $up_power}>
                                </label>
                            <{/if}>
                        </td>
                        <td headers="h3" style="font-size: 68.75%;text-align:center;"><{$folder.file_num}><{$smarty.const._MD_TADUP_FILE}></td>
                        <td headers="h4" style="font-size: 68.75%;text-align:center;"><{$folder.cat_count}></td>
                        <{if $only_show_desc!="1"}>
                        <td headers="h5" style="font-size: 75%;"><{$folder.cat_desc}></td>
                        <{/if}>
                        <{if $up_power}>
                        <td headers="h6" style="text-align:center;" nowrap>
                            <{if $folder.file_num==0}>
                            <a href="javascript:delete_tad_uploader_func(<{$folder.cat_sn}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                            <{/if}>
                        </td>
                        <{/if}>
                    </tr>
                <{/foreach}>
            </tbody>
        <{/if}>

        <{if $files_list}>
            <tbody id="sort">
                <{foreach from=$files_list item=file}>
                    <tr id="tr_<{$file.cfsn}>">
                    <td headers="h1" style="max-width: 16px;">
                        <{if $up_power}>
                        <input type="checkbox" name="select_files[<{$file.cfsn}>]" value="<{$file.cf_name}>" class="u<{$file.cat_sn}> selected_file" onChange="chk_selected_files();">
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
                    <td headers="h3" style="font-size: 68.75%;text-align:center;"><{$file.up_date}></td>
                    <td headers="h4" style="font-size: 68.75%;text-align:center;"><{$file.size}></td>
                    <td style="font-size: 68.75%;text-align:center;"><{$file.cf_count}></td>

                    <{if $only_show_desc!="1"}>
                        <td headers="h5" style="font-size: 75%;">
                        <{if $file.cf_desc!=$file.cf_name}>
                            <{$file.cf_desc}>
                        <{/if}></td>
                    <{/if}>

                    <{if $up_power}>
                        <td headers="h6" style="text-align:center;" nowrap>
                            <a href="javascript:delete_file_func(<{$file.cfsn}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                            <a href="uploads.php?cfsn=<{$file.cfsn}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                        </td>
                    <{/if}>
                    </tr>
                <{/foreach}>
            </tbody>
        <{/if}>

        </table>
    <{/if}>