<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    @if(($notDeliveredCount ?? 0) + ($delayedMoreThan7Days ?? 0) > 0)
                        <span class="badge badge-danger badge-counter">
                            {{ ($notDeliveredCount ?? 0) + ($delayedMoreThan7Days ?? 0) +  ($delayedMoreThan14Days ?? 0) }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Notifikasi
                    </h6>

                    @if(($notDeliveredCount ?? 0) > 0)
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('shownotifikasi', ['type' => 'notdelivered']) }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold">SO Belum Delivered</span>
                                <div class="small text-gray-500">{{ $notDeliveredCount }} order</div>
                            </div>
                        </a>
                    @endif

                    @if(($delayedMoreThan7Days ?? 0) > 0)
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('shownotifikasi', ['type' => 'delayed']) }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <div>
                                <span class="font-weight-bold">SO Terlambat > 7 Hari < 14 Hari</span>
                                <div class="small text-gray-500">{{ $delayedMoreThan7Days }} order</div>
                            </div>
                        </a>
                    @endif

                    @if((($notDeliveredCount ?? 0) + ($delayedMoreThan7Days ?? 0)) === 0)
                        <div class="dropdown-item text-center small text-gray-500">
                            Tidak ada notifikasi
                        </div>
                    @endif
                    @if(($delayedMoreThan14Days ?? 0) > 0)
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('shownotifikasi', ['type' => 'delay']) }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-danger">
    <i class="fa fa-exclamation-triangle text-white" aria-hidden="true"></i>
</div>

                            </div>
                            <div>
                                <span class="font-weight-bold">SO Terlambat > 14 Hari</span>
                                <div class="small text-gray-500">{{ $delayedMoreThan14Days }} order</div>
                            </div>
                        </a>
                    @endif
                </div>
            </li>

            @php
                $user = Auth::user();
            @endphp

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name ?? 'User' }}</span>
                    <img class="img-profile rounded-circle"
     src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('sbadmin/img/undraw_profile.svg') }}">
</a>

                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    {{-- <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a> --}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </div>
            </li>

        </ul>

    </nav>
