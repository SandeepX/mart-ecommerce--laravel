<script>
    $(document).ready(function() {

        function loadMainTabContent(url){
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
            }).done( function (response){
                $('#general-content').html("");
                $('#general-content').append(response.html);
            }).fail( function (data){
                displayErrorMessage(data);
            });
        }
        var defaultActiveTab = $("#vendor_detail_tabs li.active a").attr('href');
        if(defaultActiveTab == "#general"){
            loadMainTabContent('{{route('admin.vendor.general.detail',['vendorCode'=>$vendorCode])}}')
        }
        // ajax call corresponding to active tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            console.log(e.target);
            var clickedHref = $(e.target).attr('href')

            if(clickedHref == "#general"){
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.vendor.general.detail',['vendorCode'=>$vendorCode])}}')
            }

            if(clickedHref == "#product"){
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.vendor.products',['vendorCode'=>$vendorCode])}}')
            }
            localStorage.setItem('activeTab', clickedHref);
        });

        // vendor product table pagination
        $('body').on('click', '#vendorProduct-pagination .pagination a', function(e) {
            e.preventDefault();
            $(this).parent('li').addClass('active');
            var myurl = '{{route('admin.vendor.products',['vendorCode'=>$vendorCode])}}';
            var page = $(this).attr('href').split('page=')[1];
            localStorage.setItem('activePage', page);
            getPaginationData(page,myurl);
        });

        function getPaginationData(page,url){
            $.ajax(
                {
                    url: url + '?page=' + page,
                    type: "get",
                    datatype: "html"
                }).done(function(response){
                $('#general-content').html("");
                $('#general-content').append(response.html);
            }).fail(function(error){
                displayErrorMessage(error);
            });
        }

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

        function displayErrorMessage(data,flashElementId='showStoreFlashMessage') {
            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();
            if (data.status === 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
            else{
                flashMessage.html(closeButton + data.responseJSON.message);
            }
        }
    });
</script>








