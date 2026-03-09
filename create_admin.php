<?php
// Run from the root directory
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create admin user
\App\Models\User::updateOrCreate(
    ['email' => 'admin@saddhusync.local'],
    [
        'name' => 'Admin',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]
);

echo "Admin user created/updated successfully!\n";
echo "Email: admin@saddhusync.local\n";
echo "Password: password\n";
