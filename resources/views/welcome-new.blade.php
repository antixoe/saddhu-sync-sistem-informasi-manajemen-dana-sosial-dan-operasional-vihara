<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaddhuSync Management System</title>
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
        body {
            background: linear-gradient(135deg, #3E2723 0%, #5d4037 100%);
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
<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="sidebar w-64 text-white flex flex-col hidden md:flex">
            <div class="p-6 border-b border-amber-600/30">
                <h1 class="text-xl font-bold">SaddhuSync</h1>
                <p class="text-xs text-amber-200">Management</p>
            </div>
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="/" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home w-5"></i><span>Home</span>
                </a>
                <button onclick="openModal('loginModal')" class="nav-link px-4 py-3 rounded-lg flex items-center space-x-3 text-sm w-full text-left">
                    <i class="fas fa-sign-in-alt w-5"></i><span>Login</span>
                </button>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 overflow-y-auto p-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-deep-brown mb-4">SaddhuSync Management System</h1>
                <p class="text-xl text-gray-700 mb-8">
                    Digital temple operations and financial management platform
                </p>
                <button onclick="openModal('loginModal')" class="inline-block px-8 py-3 bg-gradient-to-r from-saffron to-gold text-white font-bold rounded-lg hover:shadow-lg transition btn-animated">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
                <a href="{{ route('register') }}" class="inline-block ml-4 px-8 py-3 border-2 border-saffron text-saffron font-bold rounded-lg hover:bg-saffron hover:text-white transition">Register</a>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div onclick="openModal('feat1')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-hands-praying"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Digital Donations</h3>
                    <p class="text-gray-600 text-sm">Accept donations via QRIS, bank transfer, and cash with automatic receipts</p>
                </div>

                <div onclick="openModal('feat2')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Member Management</h3>
                    <p class="text-gray-600 text-sm">Maintain congregation database with QR code check-in system</p>
                </div>

                <div onclick="openModal('feat3')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Ritual Scheduling</h3>
                    <p class="text-gray-600 text-sm">Organize prayers, ceremonies, and dhamma classes with attendance tracking</p>
                </div>

                <div onclick="openModal('feat4')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Inventory Management</h3>
                    <p class="text-gray-600 text-sm">Track temple supplies with low-stock alerts and valuation</p>
                </div>

                <div onclick="openModal('feat5')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Financial Reports</h3>
                    <p class="text-gray-600 text-sm">Real-time financial reports by category with fund allocation tracking</p>
                </div>

                <div onclick="openModal('feat6')" class="bg-white rounded-lg p-6 shadow-lg hover:shadow-xl transition cursor-pointer">
                    <div class="text-3xl text-amber-500 mb-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Audit Trail</h3>
                    <p class="text-gray-600 text-sm">Complete activity log with user tracking and data change history</p>
                </div>
            </div>

            <!-- Key Features -->
            <div class="mt-12 bg-white rounded-lg p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Key Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Multiple donation methods (QRIS, bank transfer, cash)</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Automated e-receipt delivery via email/WhatsApp</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Three fund categories (Operational, Social, Infrastructure)</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Digital member cards with QR code attendance</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Merit history tracking for each member</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Petty cash tracking for daily expenses</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Recurring donation reminders</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-1"></i>
                        <span class="text-gray-700">Anonymous donation support</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-12 text-gray-600">
                <p class="mb-4">Ready to modernize your temple management?</p>
                <button onclick="openModal('loginModal')" class="inline-block px-8 py-3 bg-gradient-to-r from-amber-400 to-amber-600 text-white font-bold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Access SaddhuSync System
                </button>
                <p class="mt-8 text-sm text-gray-400">© 2026 SaddhuSync Management System. Built with <i class="fas fa-heart text-red-500"></i> for Buddhist communities.</p>
            </div>
        </div>
    </div>

    <!-- feature modals -->
    <div id="feat1" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="modal-content bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat1')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Digital Donations</h2>
            <p class="mt-2">Accept QRIS, bank transfers or cash, with automatic e-receipts sent to donors.</p>
            <a href="{{ route('donations.index') }}" class="text-saffron underline mt-4 inline-block">Explore donations</a>
        </div>
    </div>
    <div id="feat2" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat2')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Member Management</h2>
            <p class="mt-2">Register and track congregation members with QR code profiles.</p>
            <a href="{{ route('members.index') }}" class="text-saffron underline mt-4 inline-block">View members</a>
        </div>
    </div>
    <div id="feat3" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat3')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Ritual Scheduling</h2>
            <p class="mt-2">Plan ceremonies, classes and events with attendee tracking.</p>
            <a href="{{ route('rituals.index') }}" class="text-saffron underline mt-4 inline-block">Manage rituals</a>
        </div>
    </div>
    <div id="feat4" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat4')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Inventory Management</h2>
            <p class="mt-2">Keep track of temple supplies and receive low-stock alerts.</p>
            <a href="{{ route('inventory.index') }}" class="text-saffron underline mt-4 inline-block">Check inventory</a>
        </div>
    </div>
    <div id="feat5" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat5')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Financial Reports</h2>
            <p class="mt-2">Generate real-time reports categorized by funds and expenses.</p>
            <a href="{{ route('reports.financial-summary') }}" class="text-saffron underline mt-4 inline-block">View reports</a>
        </div>
    </div>
    <div id="feat6" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500" onclick="closeModal('feat6')">&times;</button>
            <h2 class="text-xl font-semibold text-deep-brown">Audit Trail</h2>
            <p class="mt-2">See a complete log of actions taken within the system.</p>
            <a href="{{ route('reports.activity-log') }}" class="text-saffron underline mt-4 inline-block">Open log</a>
        </div>
    </div>

    <!-- login modal -->
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
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="mt-1 block w-full px-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-saffron focus:border-saffron"
                        placeholder="********">
                    <button type="button" onclick="togglePassword('password','eyeIcon')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 z-20 password-toggle focus:outline-none">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-saffron focus:ring-saffron border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-saffron to-gold text-white py-2 rounded-lg hover:shadow-lg transition btn-animated">
                        Sign In
                    </button>
                </div>
            </form>
            <div class="text-center mt-2 space-y-2">
                <a href="{{ route('register') }}" class="block w-full px-4 py-2 bg-gradient-to-r from-saffron to-gold text-white rounded-lg hover:shadow-lg transition btn-animated" onclick="closeModal('loginModal')">Create account</a>
                <p class="text-xs text-gray-500">Already have one? <a href="#" onclick="openModal('loginModal'); return false;" class="text-saffron hover:underline">Sign in</a></p>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden','closing');
                el.classList.add('active');
            }
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('active');
                el.classList.add('closing');
                el.addEventListener('animationend', () => {
                    el.classList.add('hidden');
                    el.classList.remove('closing');
                }, { once: true });
            }
        }

        // password visibility toggle (used by login modal)
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

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bg-opacity-50')) {
                e.target.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
