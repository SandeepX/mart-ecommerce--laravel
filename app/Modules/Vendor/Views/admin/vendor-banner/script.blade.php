<script>
     $(document).ready(function(){  
      var i=1;  
      $('#add_more').click(function(){  
           i++;
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="file" class="form-control" name="banners[]" required></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
           $(this).remove();  
      });  
    
 });
</script>