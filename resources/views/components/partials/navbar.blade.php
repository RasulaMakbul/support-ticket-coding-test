<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">{{ __('NetCoden') }}</a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch"
                aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
                <svg class="bi">
                    <use xlink:href="#search" />
                </svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 " type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </li>
    </ul>

    <!-- User Dropdown - Now always visible -->
    <div class="nav-item dropdown ms-auto">
        <button class="btn btn-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

            <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
        </ul>
    </div>
</header>
