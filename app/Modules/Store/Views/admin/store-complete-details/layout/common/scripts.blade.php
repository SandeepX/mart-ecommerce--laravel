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
        var defaultActiveTab = $("#store_detail_tabs li.active a").attr('href');
        if(defaultActiveTab == "#general"){
            loadMainTabContent('{{route('admin.store.general.detail',['storeCode'=>$storeCode])}}')
        }
        // ajax call corresponding to active tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            console.log(e.target);
            var clickedHref = $(e.target).attr('href')

            if(clickedHref == "#general"){
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.store.general.detail',['storeCode'=>$storeCode])}}')
            }else if(clickedHref == "#storeOrder"){
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.store.order',['storeCode'=>$storeCode])}}')
            }else if(clickedHref == "#miscellaneous") {
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.store.miscellaneous',['storeCode'=>$storeCode])}}')
            }
            else if(clickedHref == "#kyc") {
                localStorage.setItem('activePage', 0);
                loadMainTabContent('{{route('admin.store.kyc',['storeCode'=>$storeCode])}}')
            }
            else if(clickedHref == "#balanceManagement") {
                localStorage.setItem('activePage',0);
                loadMainTabContent('{{route('admin.store.balance',['storeCode'=>$storeCode])}}')
                loadBalanceTransactionTabContent();
            }else if (clickedHref == '#pre-order'){
                console.log('pre-order tab');
                localStorage.setItem('activePage',0);
                loadMainTabContent('{{route('admin.store.pre-order',['storeCode'=>$storeCode])}}')
            }
            {{--else if(clickedHref=="#enquiry-message"){--}}
            {{--    localStorage.setItem('activePage',0);--}}
            {{--    loadMainTabContent('{{route('admin.store.enquiry-message',['userCode'=>$storeCode])}}')--}}
            {{--}--}}
            localStorage.setItem('activeTab', clickedHref);
        });
        // for sub tab of balance management
        $('body').on('click', '#store_balance_tabs a[data-toggle="tab"]', function(e) {
            var clickedNewHref = $(e.target).attr('href')
            localStorage.setItem('activePage', 0);
            localStorage.setItem('activeBalanceSubTab', clickedNewHref);
        });
        // on page refresh
        {{--window.onload = function() {--}}
        {{--    // e.stopImmediatePropagation();--}}
        {{--    // e.preventDefault();--}}
        {{--    var currentWindowLocation = window.location.toString();--}}
        {{--    var previousWindowLocation = document.referrer.toString();--}}
        {{--    var newCurrentWindowLocation,newPreviousWindowLocation;--}}
        {{--    if (currentWindowLocation.indexOf("#") === -1) {--}}
        {{--        newCurrentWindowLocation = currentWindowLocation;--}}
        {{--    }else{--}}
        {{--        newCurrentWindowLocation = currentLocation.replace("#", "");--}}
        {{--    }--}}
        {{--    if (previousWindowLocation.indexOf("#") === -1)--}}
        {{--    {--}}
        {{--        newPreviousWindowLocation = previousWindowLocation;--}}
        {{--    }else{--}}
        {{--        newPreviousWindowLocation = previousWindowLocation.replace("#", "");--}}
        {{--    }--}}
        {{--    if(newCurrentWindowLocation != newPreviousWindowLocation){--}}
        {{--        localStorage.clear();--}}
        {{--    }--}}
        {{--    // console.log('previous location ' + document.referrer);--}}
        {{--    // console.log('current location ' + window.location);--}}
        {{--    // console.log('new current location ' + newCurrentWindowLocation);--}}
        {{--    var activeTab = localStorage.getItem('activeTab');--}}
        {{--    var activePage = localStorage.getItem('activePage');--}}
        {{--    var activeSubTab = localStorage.getItem('activeBalanceSubTab');--}}
        {{--    console.log(activeTab,activePage,activeSubTab, '  values')--}}
        {{--    if (activeTab) {--}}
        {{--        if(activePage !== '0'){ //both active tab and active page is present condition--}}
        {{--            console.log('active tab with active page')--}}
        {{--            if(activeTab == '#storeOrder')--}}
        {{--            {--}}
        {{--                var myurl = '{{route('admin.store.order',['storeCode'=>$storeCode])}}';--}}
        {{--                $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                getPaginationData(activePage,myurl);--}}
        {{--            }else if(activeTab == '#miscellaneous')--}}
        {{--            {--}}
        {{--                var myurl = '{{route('admin.store.miscellaneous',['storeCode'=>$storeCode])}}';--}}
        {{--                $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                getPaginationData(activePage,myurl);--}}
        {{--            }else if (activeTab == '#balanceManagement')--}}
        {{--            {--}}
        {{--                loadMainTabContent('{{route('admin.store.balance',['storeCode'=>$storeCode])}}')--}}
        {{--                if(activeSubTab)--}}
        {{--                {--}}
        {{--                    if(activeSubTab == '#balance'){--}}
        {{--                        console.log('balance-->balance transaction--->active pagination');--}}
        {{--                        $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                        --}}{{--var myurl = '{{route('admin.store.balance.transaction',['storeCode'=>$storeCode])}}';--}}
        {{--                        loadBalanceTransactionTabContent();--}}
        {{--                        // getPaginationDataForBalanceManagementTab(activePage,myurl);--}}
        {{--                        --}}{{--$('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                        --}}{{--$('#store_balance_tabs li a[href="'+activeSubTab+'"]').tab('show');--}}
        {{--                        --}}{{--getPaginationDataForBalanceManagementTab(activePage,myurl);--}}
        {{--                    }else if(activeSubTab == '#withdrawRequest'){--}}
        {{--                        console.log('withdraw with active page')--}}
        {{--                        var myurl = '{{route('admin.store.balance.withdraw',['storeCode'=>$storeCode])}}';--}}
        {{--                        // $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                        // $('#store_balance_tabs li a[href="'+activeSubTab+'"]').tab('show');--}}
        {{--                        loadWithdrawRequestTabContent();--}}
        {{--                        // getPaginationDataForBalanceManagementTab(activePage,myurl);--}}
        {{--                    }--}}
        {{--                }else{--}}
        {{--                    loadBalanceTransactionTabContent();--}}
        {{--                }--}}
        {{--            }--}}
        {{--        }--}}
        {{--        else{  //activeTab but no active page condition--}}
        {{--            if (activeTab == '#balanceManagement'){--}}
        {{--                console.log('balance management tab with no active page');--}}
        {{--                $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--                loadMainTabContent('/admin/stores/{{$storeCode}}/balance');--}}
        {{--                loadBalanceTransactionTabContent()--}}
        {{--            }else{--}}
        {{--                $('#store_detail_tabs li a[href="'+activeTab+'"]').tab('show');--}}
        {{--            }--}}
        {{--        }--}}
        {{--    }--}}
        {{--}--}}

        function getPaginationDataForBalanceManagementTab(page,url){
            $.ajax(
                {
                    url: url + '?page=' + page,
                    type: "get",
                    datatype: "html"
                }).done(function(response){
                $('#balanceManagementTable').html("");
                $('#balanceManagementTable').append(response.html);
            }).fail(function(error){
                displayErrorMessage(error);
            });
        }
        //append withdraw request table
        $('body').on('click', '#withdraw-request-tab', function () {
            loadWithdrawRequestTabContent();
        });
        // append balance transaction table
        $('body').on('click', '#balance-transaction-tab', function () {
            loadBalanceTransactionTabContent();
        });
        function loadWithdrawRequestTabContent(){
            $.ajax({
                url: '/admin/stores/{{$storeCode}}/balance/withdraw-request',
                type: "GET",
                success: function (response) {
                    // console.log(response)
                    $('#balanceManagementTable').html("");
                    $('#balanceManagementTable').append(response.html);
                },
                error: function (error) {
                    displayErrorMessage(error);
                }
            });
        }
        function loadBalanceTransactionTabContent(){
            $.ajax({
                url: '/admin/stores/{{$storeCode}}/balance-transaction',
                type: "GET",
                success: function (response) {
                    $('#balanceManagementTable').html("");
                    $('#balanceManagementTable').addClass('active');
                    $('#balanceManagementTable').append(response.html);
                },
                error: function (error) {
                    displayErrorMessage(error);
                }
            });
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
        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';
        function displayErrorMessage(data,flashElementId='showStoreFlashMessage') {
            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();
            if (data.status == 422) {
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
        // responding to misc payment
        $('body').on('click', '#saveMiscPayment', function (){
            Swal.fire({
                title: 'Do you want to save the changes ?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formVerification').submit();
                    Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })
    });
</script>








