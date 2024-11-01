<div class="container-fluid" style="margin-bottom: 30px;">
    <{if $now_op|default:false}>
        <{include file="$xoops_rootpath/modules/$xoops_dirname/templates/op_`$now_op`.tpl"}>
    <{/if}>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>