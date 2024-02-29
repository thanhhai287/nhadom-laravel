<button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
    <i class="bi bi-list" style="font-size: 2rem;"></i>
</button>

<button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
    <i class="bi bi-list" style="font-size: 2rem;"></i>
</button>

<ul class="c-header-nav ml-auto">

</ul>
<ul class="c-header-nav ml-auto mr-4">
    @can('create_pos_sales')
        <li class="c-header-nav-item mr-3">
            <a style="background-color: #0e873f; color: #fff" class="btn btn-pill {{ request()->routeIs('app.pos.index') ? 'd-none' : '' }}" href="{{ route('app.pos.index') }}">
                <i class="bi bi-cart mr-1"></i> POS System
            </a>
        </li>
    @endcan
    @can('access_sales')
        <li class="c-header-nav-item mr-3">
            <a style="background-color: #0e873f; color: #fff" class="btn btn-pill {{ request()->routeIs('home') ? 'd-none' : '' }}" href="{{ route('home') }}">
              Tổng quan
            </a>
        </li>
    @endcan
    @can('access_sales')
        <li class="c-header-nav-item mr-3">
            <a style="background-color: #0e873f; color: #fff" class="btn btn-pill {{ request()->routeIs('sales.index') ? 'd-none' : '' }}" href="{{ route('sales.index') }}">
                Báo cáo
            </a>
        </li>
    @endcan
    <li class="c-header-nav-item dropdown">
        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button"
           aria-haspopup="true" aria-expanded="false">
            <div class="c-avatar mr-2">
                <img class="c-avatar rounded-circle" src="{{ auth()->user()->getFirstMediaUrl('avatars') }}" alt="Profile Image">
            </div>
            <div class="d-flex flex-column">
                <span class="font-weight-bold">{{ auth()->user()->name }}</span>
                <span class="font-italic">Online <i class="bi bi-circle-fill text-success" style="font-size: 11px;"></i></span>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right pt-0">
            <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="mfe-2  bi bi-person" style="font-size: 1.2rem;"></i> Profile
            </a>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mfe-2  bi bi-box-arrow-left" style="font-size: 1.2rem;"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
</ul>
