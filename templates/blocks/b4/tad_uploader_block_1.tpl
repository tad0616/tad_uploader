<{if $block.link}>
    <ol class="vertical_menu">
        <{foreach item=link from=$block.link}>
            <li>
                <a href="<{$xoops_url}>/modules/tad_uploader/index.php?op=dlfile&cfsn=<{$link.cfsn}>&cat_sn=<{$link.cat_sn}>&n=<{$link.cf_name}>" class="iconize" target="_blank"><{$link.title}></a>
            </li>
        <{/foreach}>
    </ol>

    <div style="text-align:right;">
        <a href="<{$xoops_url}>/modules/tad_uploader" class="label">more...</a>
    </div>
<{/if}>