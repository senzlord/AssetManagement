<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom active class for highlighting */
        .nav-link.active {
            background-color: white;
            color: black !important;
            font-weight: bold;
        }
    </style>
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
                    @can('view device data')
                    <li class="nav-item">
                        <span class="nav-link text-white">Perangkat</span>
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">Hardware</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white">Non-Hardware</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sfp.index') }}" class="nav-link text-white">SFP</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('create account')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link text-white">User List</a>
                        </li>
                    @endcan
                    <!-- Additional Links -->
                    <li class="nav-item">
                        <a href="{{ route('logs.index') }}" class="nav-link text-white">Log History</a>
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

    <script>
        // Get all sidebar links
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            // Check if the current URL matches the link's href
            if (link.href === window.location.href) {
                link.classList.add('active'); // Add 'active' class to the matching link
            }
        });
    </script>
</body>
</html>
