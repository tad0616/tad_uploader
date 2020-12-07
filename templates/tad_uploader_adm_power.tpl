<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
    <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var $tabs = $("#grouppermform-tabs").tabs({ cookie: { expires: 30 } , collapsible: true});
        });
    </script>

    <div id="grouppermform-tabs">
        <ul>
            <li><a href="#tabs-1"><{$smarty.const._MA_TADUP_SET_ACCESS_POWER}></a></li>
            <li><a href="#tabs-2"><{$smarty.const._MA_TADUP_SET_UPLOAD_POWER}></a></li>
        </ul>
        <div id="tabs-1">
            <{$main1}>
        </div>
        <div id="tabs-2">
            <{$main2}>
        </div>
    </div>
</div>