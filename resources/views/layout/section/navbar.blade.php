<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">

        <span class="brand-text font-weight-dark center">  E-Locker RC4</span>

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{--  <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">  --}}
            </div>
            <div class="info d-flex align-items-center">
                <span class="mr-2">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>


        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-header">MANAJEMEN DATA</li>
                <li class="nav-item">
                    <a href="{{ url('manual') }}" class="nav-link {{ Request::is('manual') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('user') }}" class="nav-link {{ Request::is('user') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('loker') }}" class="nav-link {{ Request::is('loker') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <p>Loker</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('datalog') }}" class="nav-link {{ Request::is('datalog') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <p>Datalog</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('data-proses') }}" class="nav-link {{ Request::is('data-proses') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <p>Data Proses</p>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
