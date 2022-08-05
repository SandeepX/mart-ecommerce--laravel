<li class="{{request()->is('admin/career/*')? "active" : ""}} treeview">
    <a href="#">
        <i class="fa fa-file"></i>
        <span>Careers</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{request()->routeIs('admin.careers.index') ? 'active' : ''}} treeview">
            <a href="{{route('admin.careers.index')}}">
                <i class="fa fa-file {{request()->routeIs('admin.careers.index') ? 'fa-spin' : ''}}"></i>
                <span>Career Title</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        <li class="{{request()->routeIs('admin.candidates.index') ? 'active' : ''}} treeview">
            <a href="{{route('admin.candidates.index')}}">
                <i class="fa fa-file {{request()->routeIs('admin.candidates.index') ? 'fa-spin' : ''}}"></i>
                <span>Candidates</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
{{--        <li class="{{request()->routeIs('admin.job-applications.index') ? 'active' : ''}} treeview">--}}
{{--            <a href="{{route('admin.job-applications.index')}}">--}}
{{--                <i class="fa fa-file {{request()->routeIs('admin.job-applications.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                <span>Job Applications</span>--}}
{{--                <span class="pull-right-container">--}}
{{--                </span>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="{{request()->routeIs('admin.job-questions.index') ? 'active' : ''}} treeview">--}}
{{--            <a href="{{route('admin.job-questions.index')}}">--}}
{{--                <i class="fa fa-file {{request()->routeIs('admin.job-questions.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                <span>Job Questions</span>--}}
{{--                <span class="pull-right-container">--}}
{{--                </span>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="{{request()->routeIs('admin.job-openings.index') ? 'active' : ''}} treeview">--}}
{{--            <a href="{{route('admin.job-openings.index')}}">--}}
{{--                <i class="fa fa-file {{request()->routeIs('admin.job-openings.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                <span>Job Openings</span>--}}
{{--                <span class="pull-right-container">--}}
{{--                </span>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
</li>
