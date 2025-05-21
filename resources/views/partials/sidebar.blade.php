<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('sbadmin/img/logo_esa.png') }}" alt="Logo" style="height: 32px;">
        </div>
        <div class="sidebar-brand-text mx-3">ESAutomation</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Sales Orders -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sales-orders.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Sales Orders</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Mobile) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

{{-- <!-- Content Wrapper to ensure main content doesn't overlap with sidebar -->
<div id="content-wrapper" class="d-flex flex-column" style="margin-left: 250px;">
    <!-- Main Content -->
    <div id="content">
        @include('partials.topbar')

        <!-- Begin Page Content -->
        <div class="container-fluid">
            @yield('content')
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    @include('partials.footer')
</div> --}}

