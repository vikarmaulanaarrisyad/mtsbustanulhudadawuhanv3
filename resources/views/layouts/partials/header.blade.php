<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 premium-navbar">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link toggle-btn" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- USER PROFILE QUICK LINK -->
        <li class="nav-item dropdown user-dropdown">
            <a class="nav-link d-flex align-items-center px-3" data-toggle="dropdown" href="#">
                <div class="user-avatar-wrapper mr-2">
                    @if (!empty(auth()->user()->profile_photo_path) && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="img-circle elevation-1" alt="User">
                    @else
                        <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" class="img-circle elevation-1" alt="User">
                    @endif
                    <span class="status-indicator"></span>
                </div>
                <div class="user-info-text d-none d-sm-block">
                    <span class="d-block font-weight-bold text-dark mb-0" style="line-height: 1.2;">{{ auth()->user()->name }}</span>
                    <span class="badge badge-soft-success text-xs font-weight-bold">Administrator</span>
                </div>
                <i class="fas fa-chevron-down ml-2 text-muted text-xs"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg-premium animate__animated animate__fadeIn">
                <div class="dropdown-header bg-gradient-success text-white py-4 rounded-top">
                    <div class="text-center">
                        <img src="{{ !empty(auth()->user()->profile_photo_path) ? Storage::url(auth()->user()->profile_photo_path) : asset('AdminLTE/dist/img/user1-128x128.jpg') }}" class="img-circle elevation-2 mb-2 border border-white" style="width: 60px; height: 60px; object-fit: cover;">
                        <h6 class="font-weight-bold mb-0">{{ auth()->user()->name }}</h6>
                        <small class="opacity-8">{{ auth()->user()->email }}</small>
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{ route('profile.show') }}" class="dropdown-item rounded py-2">
                        <i class="fas fa-user-edit mr-2 text-primary"></i> Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item rounded py-2 text-danger" onclick="document.querySelector('#form-logout').submit()">
                        <i class="fas fa-power-off mr-2"></i> Keluar Aplikasi
                    </a>
                </div>
            </div>
        </li>
    </ul>

    <form action="{{ route('logout') }}" method="post" id="form-logout">
        @csrf
    </form>
</nav>

<style>
    /* PREMIUM NAVBAR STYLING */
    .premium-navbar {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin: 15px 20px 0 20px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        z-index: 1030;
    }
    
    .main-header.navbar { border-bottom: none; }
    
    .toggle-btn {
        background: #f4f6f9;
        border-radius: 10px;
        margin-left: 5px;
        color: #333 !important;
        transition: all 0.2s;
    }
    .toggle-btn:hover { background: #e2e6ea; transform: scale(1.05); }

    .user-avatar-wrapper { position: relative; }
    .user-avatar-wrapper img { width: 35px; height: 35px; object-fit: cover; border: 2px solid #fff; }
    .status-indicator {
        position: absolute; bottom: 0; right: 0;
        width: 10px; height: 10px;
        background: #28a745;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .badge-soft-success { background: #e1f5e8; color: #1e7e34; }
    .shadow-lg-premium { box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 15px; overflow: hidden; }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important; }
    
    .dropdown-item { transition: all 0.2s; }
    .dropdown-item:hover { background: #f8f9fa !important; transform: translateX(5px); }

    /* Fix for sticky navbar */
    @media (min-width: 992px) {
        .layout-navbar-fixed .main-header { left: 250px; }
        .sidebar-collapse.layout-navbar-fixed .main-header { left: 4.6rem; }
    }
</style>
