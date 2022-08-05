<script>
    $('#category_type').change(function(){
        var selectedCategoryType = $('#category_type option:selected').val();
        if(selectedCategoryType == 'branch'){
            $('#select-category').removeClass('hidden');
        }else{
            $('#select-category').addClass('hidden');
            $('#upper_category').prop('selectedIndex',0);
        }
    }).trigger('change');
</script>