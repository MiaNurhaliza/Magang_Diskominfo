<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Admin Users ===".PHP_EOL;

$adminUsers = App\Models\User::where('role', 'admin')->get(['name', 'email', 'role']);

echo "Total admin users: " . $adminUsers->count() . PHP_EOL;

if ($adminUsers->count() > 0) {
    echo "Admin users found:".PHP_EOL;
    foreach ($adminUsers as $user) {
        echo "- Name: {$user->name}, Email: {$user->email}, Role: {$user->role}".PHP_EOL;
    }
} else {
    echo "No admin users found. Running AdminUserSeeder...".PHP_EOL;
    
    // Run the AdminUserSeeder
    $seeder = new \Database\Seeders\AdminUserSeeder();
    $seeder->run();
    
    echo "AdminUserSeeder executed. Checking again...".PHP_EOL;
    
    $adminUsers = App\Models\User::where('role', 'admin')->get(['name', 'email', 'role']);
    echo "Total admin users after seeding: " . $adminUsers->count() . PHP_EOL;
    
    foreach ($adminUsers as $user) {
        echo "- Name: {$user->name}, Email: {$user->email}, Role: {$user->role}".PHP_EOL;
    }
}

echo "=== End Check ===".PHP_EOL;