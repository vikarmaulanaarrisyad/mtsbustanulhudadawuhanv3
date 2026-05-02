@extends($layout)

@section('title', 'Profil Saya')

@section('content')
@if(auth()->user()->hasRole('Guru'))
    <div class="bg-indigo-600 pt-10 pb-24 px-6 rounded-b-[3rem] shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        
        <div class="relative flex flex-col items-center text-white pt-4">
            <div class="relative group">
                <div class="w-24 h-24 rounded-[2rem] border-4 border-white/30 shadow-2xl overflow-hidden bg-white/20 backdrop-blur-md">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=fff&color=4f46e5&size=128" class="w-full h-full object-cover">
                    @endif
                </div>
                <button class="absolute -bottom-2 -right-2 w-8 h-8 bg-white text-indigo-600 rounded-xl shadow-lg flex items-center justify-center border-2 border-indigo-600">
                    <i class="fas fa-camera text-xs"></i>
                </button>
            </div>
            <h2 class="mt-4 text-xl font-bold tracking-tight">{{ auth()->user()->name }}</h2>
            <p class="text-indigo-200 text-xs font-medium uppercase tracking-widest opacity-80">{{ auth()->user()->roles->first()->name ?? 'Pengguna' }}</p>
        </div>
    </div>

    <div class="px-6 -mt-12 mb-10">
        <!-- Profile Menu Cards -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-100/40 p-2 border border-slate-50 mb-6">
            <div class="p-4">
                <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4 px-2">Pengaturan Akun</h3>
                
                <a href="{{ route('profile.show') }}" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-2xl transition-all group {{ request('pills') == '' ? 'bg-indigo-50' : '' }}">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-xl {{ request('pills') == '' ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <span class="{{ request('pills') == '' ? 'text-indigo-600' : 'text-slate-600' }} font-bold text-sm">Informasi Profil</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                </a>

                <a href="{{ route('profile.show') }}?pills=password" class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-2xl transition-all group {{ request('pills') == 'password' ? 'bg-indigo-50' : '' }} mt-2">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-xl {{ request('pills') == 'password' ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center">
                            <i class="fas fa-lock"></i>
                        </div>
                        <span class="{{ request('pills') == 'password' ? 'text-indigo-600' : 'text-slate-600' }} font-bold text-sm">Keamanan Password</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-100/40 p-6 border border-slate-50">
            @if (request('pills') == '')
                @includeIf('profile.update-profile-information-form')
            @else
                @includeIf('profile.update-password-form')
            @endif
        </div>

        <!-- Logout Area -->
        <div class="mt-8">
            <button onclick="document.querySelector('#form-logout-mobile').submit()" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-500 font-bold py-4 rounded-[2rem] flex items-center justify-center transition-all border border-rose-100 active:scale-95">
                <i class="fas fa-power-off mr-3"></i> Keluar dari Aplikasi
            </button>
            <form action="{{ route('logout') }}" method="post" id="form-logout-mobile" class="d-none">
                @csrf
            </form>
            <p class="text-center text-slate-300 text-[10px] font-bold uppercase tracking-widest mt-6">Versi 2.0.0 &copy; Smart Madrasah</p>
        </div>
    </div>
@else
    <!-- Standar Layout for Non-Guru -->
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if (request('pills') == '') active @endif"
                        href="{{ route('profile.show') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request('pills') == 'password') active @endif"
                        href="{{ route('profile.show') }}?pills=password">Password</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade @if (request('pills') == '') show active @endif" id="pills-profil"
                    role="tabpanel" aria-labelledby="pills-profil-tab">
                    @includeIf('profile.update-profile-information-form')
                </div>
                <div class="tab-pane fade @if (request('pills') == 'password') show active @endif" id="pills-password"
                    role="tabpanel" aria-labelledby="pills-password-tab">
                    @includeIf('profile.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    /* Android Style Form Adjustments */
    .card { border: none !important; box-shadow: none !important; }
    .card-header { display: none !important; }
    .form-control {
        background: #f8f9fe !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
    }
    .btn-primary {
        background: #4f46e5 !important;
        border: none !important;
        border-radius: 1rem !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 700 !important;
        width: 100%;
        margin-top: 1rem;
    }
</style>
@endsection
