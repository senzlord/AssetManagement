<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white" id="sidebar" style="width: 250px; min-height: 100vh;">
            <div class="p-4">
                <h4>{{ config('app.name', 'Laravel') }}</h4>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link text-white">Home</a>
                    </li>
                    <!-- Profile Link -->
                    <li class="nav-item">
                        <a href="{{ route('profile') }}" class="nav-link text-white">Profile</a>
                    </li>
                    <!-- User List Link (conditionally displayed based on "create account" permission) -->
                    @can('create account')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link text-white">User List</a>
                        </li>
                    @endcan

                    <!-- Additional Links -->
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Settings</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area with Header -->
        <div class="flex-grow-1">
            <!-- Header with Dropdown -->
            <header class="bg-light p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Welcome, {{ Auth::user()->name }}</h5>
                    <div class="dropdown">
                        <a href="#" class="text-decoration-none text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('password.change') }}">Change Password</a></li>
                            <hr>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                        <!-- Hidden Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="container py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>