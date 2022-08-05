@canany([
  'View Quiz Game Passage Lists',
  'View Quiz Game Participator Lists'

])
    <li class="
{{
   request()->routeIs('admin.quiz.passage.*') ||
   request()->routeIs('admin.quiz.participator.*')

? 'active' : ''
}}
        treeview
">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Quiz Game </span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            @can('View Quiz Game Passage Lists')
                <li class="{{request()->routeIs('admin.quiz.passage.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.quiz.passage.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.quiz.passage.*') ? 'fa-spin' : ''}}"></i> <span>Quiz Passage</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan

            @can('View Quiz Game Participator Lists')
                <li class="{{request()->routeIs('admin.quiz.participator.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.quiz.participator.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.quiz.participator.*') ? 'fa-spin' : ''}}"></i> <span>Quiz Participator</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan





        </ul>
    </li>
@endcanany




