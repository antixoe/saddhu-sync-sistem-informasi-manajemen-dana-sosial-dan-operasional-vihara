<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SaddhuSync Management System</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center">
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
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-deep-brown to-saffron p-8 text-center text-white">
                <!-- logo removed -->
                <div class="text-5xl mb-4">
                </div>
                <h1 class="text-3xl font-bold">SaddhuSync</h1>
                <p class="text-sm mt-2 opacity-90">Management System</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                @yield('content')
            </div>
        </div>

        <div class="text-center text-white mt-6">
            <p class="text-sm opacity-75">© 2026 SaddhuSync Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
