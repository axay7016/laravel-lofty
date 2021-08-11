   <!-- Topbar Start -->
   <div class="navbar-custom topnav-navbar topnav-navbar-dark">
       <div class="container-fluid">

           <!-- LOGO -->
           <a href="index.html" class="topnav-logo">
               <span class="topnav-logo-lg">
                   <img src="{{ asset('assets/images/logo-light.png')}}" alt="" height="16">
               </span>
               <span class="topnav-logo-sm">
                   <img src="{{ asset('assets/images/logo_sm.png') }}" alt="" height="16">
               </span>
           </a>

           <ul class="list-unstyled topbar-menu float-end mb-0">
               <li class="dropdown notification-list">
                   <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                       <span class="account-user-avatar">
                           <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle">
                       </span>
                       <span>
                           <span class="account-user-name">{{ Auth::user()->name }}</span>
                       </span>
                   </a>
                   <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                       <!-- item-->
                       <a href="javascript:void(0);" class="dropdown-item notify-item">
                           <i class="mdi mdi-account-circle me-1"></i>
                           <span>My Account</span>
                       </a>

                       <a href="/billing" class="dropdown-item notify-item">
                           <i class="mdi mdi-lock-outline me-1"></i>
                           <span>Billing</span>
                       </a>

                       <form method="POST" action="{{ route('logout') }}">
                           @csrf
                           <!-- item-->
                           <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                               <i class="mdi mdi-logout me-1"></i>
                               <span>Logout</span>
                           </a>
                       </form>
                   </div>
               </li>
           </ul>
           <a class="button-menu-mobile disable-btn">
               <div class="lines">
                   <span></span>
                   <span></span>
                   <span></span>
               </div>
           </a>

       </div>
   </div>
   <!-- end Topbar -->
   <!-- Start Content-->
   <div class="container-fluid">

       <!-- Begin page -->
       <div class="wrapper">