@extends('SupportAdmin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">

        <section class="content">
            @include("Admin::layout.partials.flash_message")
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, {{auth()->user()->name}}</div>
                <div class="panel-body">Allpasal</div>
            </div>
            <div class="row">


            </div>


        </section>

    </div>

@endsection
