<{$toolbar|default:''}>

<{include file="$xoops_rootpath/modules/$xoops_dirname/templates/op_`$now_op`.tpl"}>

<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>