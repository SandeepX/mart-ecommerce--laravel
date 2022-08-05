<script>
    $(document).ready(function() {
        function loadMainTabContent(url) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
            }).done(function (response) {
                $('#general-content').html("");
                $('#general-content').append(response.html);

            }).fail(function (data) {
                displayErrorMessage(data);
            });
        }


        // for sidebar tab of general details
        $('body').on('click', '#general-detail-tabs a[data-toggle="tab"]', function(e) {
            var clickedNewHref = $(e.target).attr('href')
            localStorage.setItem('activeGeneralDetailSubTab', clickedNewHref);
            localStorage.setItem('activeTab', '#general');
        });


        var defaultActiveTab = $("#warehouse_detail_tabs li.active a").attr('href');
        if(defaultActiveTab == "#general"){
            console.log('default tab is general')
            loadMainTabContent('{{route('admin.warehouse.general.detail',['warehouseCode'=>$warehouseCode])}}')
        }


        // ajax call corresponding to active tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var clickedHref = $(e.target).attr('href')
            localStorage.setItem('activeTab', clickedHref);

            if(clickedHref == "#general"){
                localStorage.removeItem('activePage');
                loadMainTabContent('{{route('admin.warehouse.general.detail',['warehouseCode'=>$warehouseCode])}}')

            }else if(clickedHref == "#preorder"){
                localStorage.removeItem('activePage');
                loadMainTabContent('{{route('admin.warehouse.preorder',['warehouseCode'=>$warehouseCode])}}')
            }
            // connected-store
            else if(clickedHref == "#store-connection"){
                localStorage.removeItem('activePage');
                loadMainTabContent('{{route('admin.warehouse.connected-store',['warehouseCode'=>$warehouseCode])}}')
            }
        });




        // on page refresh
        window.onload = function() {
            var currentWindowLocation = window.location.toString();
            var previousWindowLocation = document.referrer.toString();
            var newCurrentWindowLocation,newPreviousWindowLocation;

            if (currentWindowLocation.indexOf("#") === -1) {
                newCurrentWindowLocation = currentWindowLocation;
            }else{
                newCurrentWindowLocation = currentWindowLocation.replace("#", "");
            }

            if (previousWindowLocation.indexOf("#") === -1)
            {
                newPreviousWindowLocation = previousWindowLocation;
            }else{
                newPreviousWindowLocation = previousWindowLocation.replace("#", "");
            }



            if(newCurrentWindowLocation !== newPreviousWindowLocation){
                localStorage.clear();
                // console.log('they dont match so clear the storage')
            }

            // console.log(' new previous location ' + newPreviousWindowLocation);
            // console.log('new current location ' + newCurrentWindowLocation);
            // console.log('previous location ' + document.referrer);
            // console.log('current location ' + window.location);
            // console.log('new current location ' + newCurrentWindowLocation);

            var activeTab = localStorage.getItem('activeTab');
            var activePage = localStorage.getItem('activePage');
            var activeGeneralDetailSubTab = localStorage.getItem('activeGeneralDetailSubTab');


            if (activeTab) {
                if(activePage){ //both active tab and active page is present condition
                    console.log('active tab with active page')
                    if(activeTab == '#preorder') {
                        var myurl = '{{route('admin.warehouse.preorder',['warehouseCode'=>$warehouseCode])}}';
                        $('#warehouse_detail_tabs li a[href="'+activeTab+'"]').tab('show');
                        getPaginationData(activePage,myurl);

                    }else if(activeTab == '#store-connection') {

                        var myurl = '{{route('admin.warehouse.connected-store',['warehouseCode'=>$warehouseCode])}}';
                        $('#warehouse_detail_tabs li a[href="'+activeTab+'"]').tab('show');
                        getPaginationData(activePage,myurl);
                    }
                    else{
                            $('#warehouse_detail_tabs li a[href="'+activeTab+'"]').tab('show');
                        }
                }
                else{  //activeTab but no active page condition
                    if(activeTab == '#general'){

                        $('#warehouse_detail_tabs li a[href="'+activeTab+'"]').tab('show');

                        $(document).on('DOMNodeInserted', function(e) {
                            $( '#general-detail-tabs a[href="'+activeGeneralDetailSubTab+'"]' ).trigger( "click" );
                            $( '#general-detail-tabs a[href="'+activeGeneralDetailSubTab+'"]' ).trigger( "focus" );
                        });
                        // console.log('previous active tab ' + localStorage.getItem('activeGeneralDetailSubTab'));

                    }else{
                        $('#warehouse_detail_tabs li a[href="'+activeTab+'"]').tab('show');
                    }
                }
            }
        }


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






    });
</script>
