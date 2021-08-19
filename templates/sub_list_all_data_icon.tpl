    <{if $folder_list or $files_list}>
        <div class="row">
        <{if $folder_list}>
            <ul id="dir_sort" style="display:inline;">
                <{foreach from=$folder_list item=folder}>
                    <li id="tr_<{$folder.cat_sn}>" style="display:inline; margin:2px; width:<{$icon_width}>; height:130px; float:left;">
                        <a href="index.php?of_cat_sn=<{$folder.cat_sn}>" style="display:block;height:64px;overflow:hidden;margin:0px auto;text-align:center;">
                            <{if $folder.lock}>
                                <img src="images/folder_lock.png" alt="folder">
                            <{elseif $folder.file_num > 0}>
                                <img src="images/folder_full.png" alt="folder">
                            <{else}>
                                <img src="images/folder_empty.png" alt="folder">
                            <{/if}>
                        </a>
                        <div style="overflow: hidden; width:100%; height:50px; text-align:center;">
                            <a href="index.php?of_cat_sn=<{$folder.cat_sn}>" style="font-size: 75%;text-align:left;">
                            <{$folder.cat_title}></a>(<{$folder.file_num}>)
                        </div>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>

        <{if $files_list}>
            <ul id="sort" style="display:inline;">
                <{foreach from=$files_list item=file}>
                    <li id="tr_<{$file.cfsn}>" style="display:inline;margin:2px;width:<{$icon_width}>;height:130px;float:left;">
                    <a href="index.php?op=dlfile&cfsn=<{$file.cfsn}>&cat_sn=<{$file.cat_sn}>" style="display:block;height:64px;overflow:hidden;margin:0px auto;text-align:center;">
                    <img src="<{$file.pic}>" alt="<{$file.cf_desc}>" title="<{$file.cf_desc}>" class="rounded" style="<{$file.thumb_style}>;">
                    </a>

                    <div style="overflow: hidden;width:100%;height:50px;text-align:center;">
                        <{if $up_power and $xoops_isuser}>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="select_files[<{$file.cfsn}>]" value="<{$file.cf_name}>" class="u<{$file.cat_sn}> selected_file" onChange="chk_selected_files();">
                        <{/if}>

                        <a href="index.php?op=dlfile&cfsn=<{$file.cfsn}>&cat_sn=<{$file.cat_sn}>" style="display:inline-block;width:auto;line-height:110%;font-size: 75%;text-align:left;margin:0px auto;">

                        <{if $only_show_desc=="1"}>
                            <{$file.cf_desc}>
                        <{else}>
                            <{$file.cf_name}>
                        <{/if}>
                        </a>
                        <{if $up_power and $xoops_isuser}>
                        </label>
                        <{/if}>
                    </div>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>

        </div>
    <{/if}>