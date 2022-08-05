<li class=" {{request()->routeIs('admin.verification-questions.*') ||
request()->routeIs('admin.verification-questions.index') ? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-bar-chart"></i>
        <span>Questionnaire</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        <li class="{{request()->is('admin/verification-questions/*')? "active" : ""}} treeview">
            <a href="{{route('admin.verification-questions.index')}}">
                <i class="fa fa-file {{request()->is('admin/verification-questions/*') ? 'fa-spin' : ''}}"></i>
                <span>Verification Questions</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
    </ul>
</li>

