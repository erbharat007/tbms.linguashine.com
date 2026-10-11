<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <span class="d-inline-flex align-items-center">
                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <span class="d-none d-sm-inline ml-2 text-dark">{{ Auth::user()->name }}</span>
                    <i class="fas fa-angle-down ml-1 text-muted"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right user-menu">
                <div class="px-3 py-2 border-bottom">
                    <div class="font-weight-bold text-dark">{{ Auth::user()->name }}</div>
                    <div class="small text-muted">{{ Auth::user()->role?->name ?? 'User' }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user fa-fw mr-2 text-muted"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <a href="{{ route('logout') }}" class="dropdown-item"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt fa-fw mr-2 text-muted"></i> Log Out
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
