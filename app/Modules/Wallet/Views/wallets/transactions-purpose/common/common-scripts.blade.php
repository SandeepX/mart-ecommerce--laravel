<script>

    $(document).ready(function (){
        $("#user_type_id").on('change',function (){
            var selectedUserTypes = [];
            $('#user_type_id :selected').each(function(i, selectedElement) {
                selectedUserTypes[i] = [$(selectedElement).val(), $(selectedElement).text().replace(/\s+/g,' ').trim()];
            });
            $("#user-types-data").text('');
            if(selectedUserTypes.length>0){
                $('#extra-information').show();
            }else{
                $('#extra-information').hide();
            }
            var data = '';
            selectedUserTypes.forEach(function (selectUserType,key){

                  data += ' <tr> ' +
                      '<td>'+ selectUserType[1] + '<input type="hidden" name="user_type['+key+']" value="'+ selectUserType[0] +'"> </td>'+
                        '<td><input type="hidden" value="0" name="is_active['+key+']"><input type="checkbox" value="1" name="is_active['+key+']">'+
                        '</td> <td> <input type="hidden" value="0" name="admin_control['+key+']"> <input type="checkbox" value="1"  name="admin_control['+key+']"> </td>' +
                        '<td><input type="hidden" value="0" name="close_for_modification['+key+']"><input type="checkbox" value="1"  name="close_for_modification['+key+']"></td>'+
                    '</tr>'
            });

            $("#user-types-data").append(data);
        });
    });

</script>
