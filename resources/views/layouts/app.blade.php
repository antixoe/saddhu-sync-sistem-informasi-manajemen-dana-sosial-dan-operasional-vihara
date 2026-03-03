<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SaddhuSync Management System</title>
    @stack('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'saffron': '#F4A261',
                        'rust': '#D62828',
                        'gold': '#E8B923',
                        'jade': '#2A9D8F',
                        'deep-brown': '#3E2723',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #F4A261;
            border-radius: 4px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #faf8f3;
        }
        /* cropper css adjustments */
        .cropper-container img {
            max-width: 100%;
        }
        .sidebar {
            background: linear-gradient(135deg, #3E2723 0%, #5d4037 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .nav-link:hover {
            background-color: rgba(244, 162, 97, 0.1);
            border-left-color: #F4A261;
        }
        .nav-link.active {
            background-color: rgba(244, 162, 97, 0.2);
            border-left-color: #F4A261;
        }
        .btn-spiritual {
            background: linear-gradient(135deg, #F4A261 0%, #E8B923 100%);
            transition: all 0.3s ease;
        }
        .btn-animated {
            transition: transform 0.2s ease;
        }
        .btn-animated:hover {
            transform: scale(1.05);
        }
        .password-toggle {
            cursor: pointer;
        }

        .password-toggle {
            cursor: pointer;
        }
        .btn-spiritual:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 162, 97, 0.3);
        }
        .card-spiritual {
            background: white;
            border-top: 4px solid #F4A261;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .stat-box {
            background: linear-gradient(135deg, rgba(244, 162, 97, 0.1) 0%, rgba(232, 185, 35, 0.1) 100%);
            border-left: 4px solid #F4A261;
        }
        /* modal animation */
        .modal-overlay {
            align-items: center;
            justify-content: center;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            opacity: 0;
            z-index: 50;
        }
        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.25s ease-out forwards;
        }
        .modal-overlay.closing {
            animation: fadeOut 0.2s ease-in forwards;
        }
        .modal-content {
            background: #faf9f7;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 400px;
            transform: translateY(-20px) scale(0.9);
            opacity: 0;
            transition: transform 0.25s ease-out, opacity 0.25s ease-out;
            position: relative;
            overflow: hidden;
        }
        .modal-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(135deg, #F4A261 0%, #E8B923 100%);
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <script>
        function togglePassword(inputId, iconId) {
            const field = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (field) {
                if (field.type === 'password') {
                    field.type = 'text';
                    if (icon) icon.classList.replace('fa-eye','fa-eye-slash');
                } else {
                    field.type = 'password';
                    if (icon) icon.classList.replace('fa-eye-slash','fa-eye');
                }
            }
        }
    </script>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="sidebar w-64 text-white flex flex-col">
            <div class="p-6 border-b border-amber-600/30">
                <div class="text-center mb-2">
                    <!-- logo intentionally removed for clarity -->
                    <h1 class="text-xl font-bold">SaddhuSync</h1>
                    <p class="text-xs text-amber-200">Management System</p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <div class="px-4 py-2 text-xs font-semibold text-amber-200 uppercase tracking-wider">Main</div>
                <a href="{{ route('dashboard') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <div class="px-4 py-2 text-xs font-semibold text-amber-200 uppercase tracking-wider mt-4">Operations</div>
                <a href="{{ route('members.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Members</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('roles.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>Roles</span>
                </a>
                @endif
                <a href="{{ route('rituals.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('rituals.*') ? 'active' : '' }}">
                    <i class="fas fa-bell w-5"></i>
                    <span>Rituals & Events</span>
                </a>
                <a href="{{ route('donations.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('donations.*') ? 'active' : '' }}">
                    <i class="fas fa-hands-praying w-5"></i>
                    <span>Donations</span>
                </a>

                <div class="px-4 py-2 text-xs font-semibold text-amber-200 uppercase tracking-wider mt-4">Resources</div>
                <a href="{{ route('inventory.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes w-5"></i>
                    <span>Inventory</span>
                </a>
                <a href="{{ route('petty-cash.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('petty-cash.*') ? 'active' : '' }}">
                    <i class="fas fa-coins w-5"></i>
                    <span>Petty Cash</span>
                </a>

                <div class="px-4 py-2 text-xs font-semibold text-amber-200 uppercase tracking-wider mt-4">Reports</div>
                <a href="{{ route('reports.financial-summary') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Financial</span>
                </a>
                <a href="{{ route('reports.activity-log') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent">
                    <i class="fas fa-history w-5"></i>
                    <span>Activity Log</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('settings.index') }}" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm transition border-l-4 border-transparent {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-amber-600/30">
                @auth
                    <div class="bg-amber-600/20 rounded-lg p-3 text-xs mb-4">
                        <p class="text-amber-100">Logged in as:</p>
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-rust/30 hover:bg-rust/40 text-white text-sm transition flex items-center justify-center space-x-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <button onclick="openModal('loginModal')" class="w-full px-4 py-2 rounded-lg bg-saffron hover:bg-gold text-white text-sm transition flex items-center justify-center space-x-2 btn-animated">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </button>
                @endauth
            </div>
        </aside>

        <!-- login modal for layout -->
        <div id="loginModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="modal-content bg-white rounded-lg w-96 p-8 relative">
                <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('loginModal')">&times;</button>
                <h2 class="text-2xl font-semibold text-deep-brown mb-4">Sign In</h2>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required autofocus
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-saffron focus:border-saffron">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-saffron focus:border-saffron">
                    </div>
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-saffron focus:ring-saffron border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-saffron text-white py-2 rounded-lg hover:bg-gold transition btn-animated">
                            Sign In
                        </button>
                    </div>
                </form>
                <div class="text-center mt-2">
                    <a href="{{ route('register') }}" class="text-sm text-saffron hover:underline" onclick="closeModal('loginModal')">Don't have an account? Register</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-deep-brown">@yield('header', 'Dashboard')</h2>
                        <p class="text-sm text-gray-600 mt-1">@yield('subtitle')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-deep-brown">{{ now()->format('l, F d, Y') }}</p>
                            <p class="text-xs text-gray-600" id="time"></p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto px-8 py-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="font-semibold text-red-800 mb-2">Errors</h3>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-green-800">Success</h3>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Global modal helpers available on every page
        function openModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('hidden');
            // add active for layouts that animate on .active
            el.classList.add('active');
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            // animate closing by toggling closing class if present
            el.classList.add('closing');
            el.classList.remove('active');
            // after animation remove display
            setTimeout(() => el.classList.add('hidden'), 200);
        }

        // click outside to close
        document.addEventListener('click', function(e) {
            const target = e.target;
            if (target.classList && (target.classList.contains('modal-overlay') || target.classList.contains('bg-opacity-50'))) {
                // find parent modal overlay
                let overlay = target;
                if (!overlay.classList.contains('modal-overlay')) {
                    overlay = target.closest('.modal-overlay');
                }
                if (overlay) {
                    overlay.classList.add('closing');
                    overlay.classList.remove('active');
                    setTimeout(() => overlay.classList.add('hidden'), 200);
                }
            }
        });

        // close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(el => {
                    el.classList.add('closing');
                    el.classList.remove('active');
                    setTimeout(() => el.classList.add('hidden'), 200);
                });
            }
        });

        function updateTime() {
            const now = new Date();
            const t = document.getElementById('time');
            if (t) t.textContent = now.toLocaleTimeString();
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
    @stack('scripts')
</body>
</html>
