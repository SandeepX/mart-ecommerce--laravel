<script>
    $("#filter_by").change(function(){
        
        var selectedVendor = $('#filter_by option:selected').val();
        if(selectedVendor == 'all'){
            var route = "{{ route('admin.products.index')}}";
            $("#filter_form").attr('action', route);

        }else{
            var url = "{{ url('/admin/vendors/') }}";
            var nextRoute = url+"/"+selectedVendor+"/products";
            console.log(nextRoute);
            $("#filter_form").attr('action', nextRoute);
        }

    });
</script>