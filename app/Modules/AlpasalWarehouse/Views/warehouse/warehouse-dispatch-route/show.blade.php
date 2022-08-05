@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])

    <!-- Main content -->
        <section id="app" class="content">
                <dispatch-order-detail-page></dispatch-order-detail-page>
        </section>
        <!-- /.content -->
    </div>

@endsection



