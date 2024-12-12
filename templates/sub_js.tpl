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

        //***檔案管理****
        $("#all_selected").on('change', function() {
            var selected_op = $("#all_selected").val();
            if(selected_op=="all_del"){
                $("#all_move_select").hide();
                $("#all_selected_submit").show();
            }else if(selected_op=="all_move"){
                $("#all_move_select").show();
                $("#all_selected_submit").show();
            }else{
                $("#all_move_select").hide();
                $("#all_selected_submit").hide();
            }
        });

        //***目錄管理****
        $("#this_folder").on('change', function() {
            var folder_op = $("#this_folder").val();
            if(folder_op=="new_cat_title"){
                $("#new_cat_title_input").show();
                $("#add_cat_title_input").hide();
                $("#new_of_cat_sn_select").hide();
                $("#this_folder_submit").show();
            }else if(folder_op=="add_cat_title"){
                $("#new_cat_title_input").hide();
                $("#add_cat_title_input").show();
                $("#new_of_cat_sn_select").hide();
                $("#this_folder_submit").show();
            }else if(folder_op=="new_of_cat_sn"){
                $("#new_cat_title_input").hide();
                $("#add_cat_title_input").hide();
                $("#new_of_cat_sn_select").show();
                $("#this_folder_submit").show();
            }else{
                $("#new_cat_title_input").hide();
                $("#add_cat_title_input").hide();
                $("#new_of_cat_sn_select").hide();
                $("#this_folder_submit").hide();
            }
        });

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
                $(".u<{$cat_sn|default:''}>").each(function() {
                    $(this).prop("checked", true);
                });
            }else{
                $(".u<{$cat_sn|default:''}>").each(function() {
                    $(this).prop("checked", false);
                });
            }
        });
    });

    function chk_selected_files(){
        var atLeastOneIsChecked = $('input.selected_file:checked').length;
        console.log(atLeastOneIsChecked);
        if(atLeastOneIsChecked >0){
            $("#files_tool").show();
        }else{
            $("#files_tool").hide();
        }
    }
</script>