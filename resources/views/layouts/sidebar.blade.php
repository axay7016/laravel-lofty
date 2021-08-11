<div class="leftside-menu leftside-menu-detached">

    <div class="leftbar-user">
        <a href="javascript: void(0);">
            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
            <span class="leftbar-user-name">{{ Auth::user()->name }}</span>
        </a>
    </div>

    <!--- Sidemenu -->
    <ul class="side-nav">
        <li class="side-nav-title side-nav-item mt-1">Components</li>
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false" aria-controls="sidebarTables" class="side-nav-link">
                <i class="uil-user-square"></i>
                <span> Users </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarTables">
                <ul class="side-nav-second-level">
                    <li>

                        <a href="{{route('users')}}"><i class="uil-users-alt"></i>List Users</a>
                    </li>
                    <li>

                        <a href="{{route('insertrole')}}"><i class="uil-user-plus"></i>Add User</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarMaps" aria-expanded="false" aria-controls="sidebarMaps" class="side-nav-link">
                <i class=" uil-chart-bar"></i>
                <span> Reports </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarMaps">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{route('cadencereport')}}"><i class="uil-chart-line"></i>Cadence Report</a>
                    </li>
                    <li>
                        <a href="{{route('executivereport')}}"><i class="uil-chart-line"></i>Executive Report</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>


    <!-- End Sidebar -->

    <div class="clearfix"></div>
    <!-- Sidebar -left -->

</div>