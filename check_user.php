<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking user authentication data...\n";

// Check pembimbing user
$pembimbingUser = App\Models\User::where('role', 'pembimbing')->first();
if ($pembimbingUser) {
    echo "Pembimbing user found: {$pembimbingUser->email}\n";
    echo "User ID: {$pembimbingUser->id}\n";
    
    $pembimbingRecord = $pembimbingUser->pembimbing;
    if ($pembimbingRecord) {
        echo "Pembimbing record found: {$pembimbingRecord->nama}\n";
        echo "Pembimbing ID: {$pembimbingRecord->id}\n";
        echo "Pembimbing email: {$pembimbingRecord->email}\n";
    } else {
        echo "No pembimbing record found for this user\n";
        
        // Check if there's a pembimbing with matching email
        $pembimbingByEmail = App\Models\Pembimbing::where('email', $pembimbingUser->email)->first();
        if ($pembimbingByEmail) {
            echo "Found pembimbing by email: {$pembimbingByEmail->nama}\n";
        }
    }
} else {
    echo "No pembimbing user found\n";
}

// Check all users
$allUsers = App\Models\User::all();
echo "\nAll users:\n";
foreach ($allUsers as $user) {
    echo "  - {$user->email} (role: {$user->role})\n";
}

// Check all pembimbing records
$allPembimbings = App\Models\Pembimbing::all();
echo "\nAll pembimbing records:\n";
foreach ($allPembimbings as $pembimbing) {
    echo "  - {$pembimbing->nama} ({$pembimbing->email})\n";
}
