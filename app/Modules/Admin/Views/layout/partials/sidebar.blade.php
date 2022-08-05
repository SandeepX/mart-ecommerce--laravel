<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar"  id="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{url('admin/images/user.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}</p>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="header" style="color: white;">MAIN NAVIGATION</li>


            <input type="text"
                   id="search"
                   placeholder="search menu ..."
                   autocomplete="off"
                   class="form-control"
                   name="search"
            />



            @include('Admin::layout.partials.sidebar.dashboard.dashboard')
            @include('Admin::layout.partials.sidebar.user-management.user')
            @include('Admin::layout.partials.sidebar.vendor.vendor')
            @include('Admin::layout.partials.sidebar.store.store')
            @include('Admin::layout.partials.sidebar.sales-manager.main')
            @include('Admin::layout.partials.sidebar.customer-management.main')
            @includeIf('Admin::layout.partials.sidebar.balance-management.main')
            @includeIf('Admin::layout.partials.sidebar.bank.main')
            @includeIf('Admin::layout.partials.sidebar.career.main')
            @includeIf('Admin::layout.partials.sidebar.investment-plan.main')

            @include('Admin::layout.partials.sidebar.alpasal-warehouse.alpasal-warehouse')
            {{--@include('Admin::layout.partials.sidebar.lead.main')--}}
            @include('Admin::layout.partials.sidebar.ecommerce.main')
            {{--            @include('Admin::layout.partials.sidebar.reporting.main')--}}
            @includeIf('Admin::layout.partials.sidebar.sms.main')
            {{-- @includeIf('Admin::layout.partials.sidebar.orders.main')--}}
            @includeIf('Admin::layout.partials.sidebar.offline-payment.main')
            @include('Admin::layout.partials.sidebar.contact-message.main')
            @includeIf('Admin::layout.partials.sidebar.global-notification.main')
            @includeIf('Admin::layout.partials.sidebar.inventory-management.main')
            @include('Admin::layout.partials.sidebar.roles.main')
            @includeIf('Admin::layout.partials.sidebar.content-management.main')
            @includeIf('Admin::layout.partials.sidebar.parametrizations.main')
            @includeIf('Admin::layout.partials.sidebar.quiz-game.main')
            {{--            @includeIf('Admin::layout.partials.sidebar.home.main')--}}
            @includeIf('Admin::layout.partials.sidebar.system-setting.main')
            @includeIf('Admin::layout.partials.sidebar.newsletter.main')
            {{--            @includeIf('Admin::layout.partials.sidebar.online-payment.main')--}}
            {{--            @includeIf('Admin::layout.partials.sidebar.wallets.main')--}}
            @includeIf('Admin::layout.partials.sidebar.pricing-link.main')
            @includeIf('Admin::layout.partials.sidebar.stock-management.main')
            @includeIf('Admin::layout.partials.sidebar.lucky-draw.main')
            @includeIf('Admin::layout.partials.sidebar.questionnaire.main')
            @includeIf('Admin::layout.partials.sidebar.marketing.main')



        </ul>

    </section>
    <!-- /.sidebar -->
</aside>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        $("#search").on("keyup", function () {
            if (this.value.length > 0) {
                $("li").hide().filter(function () {
                    return $(this).text().toLowerCase().indexOf($("#search").val().toLowerCase()) !== -1;
                }).show();
            }
            else {
                $("li").show();
            }
        });


    });
</script>

