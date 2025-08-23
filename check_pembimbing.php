<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pembimbing;
use App\Models\User;

echo "=== Checking Pembimbing Data ===\n";
echo "Total pembimbing: " . Pembimbing::count() . "\n";
echo "Total users with role pembimbing: " . User::where('role', 'pembimbing')->count() . "\n";

echo "\n=== Latest Pembimbing Records ===\n";
$pembimbings = Pembimbing::latest()->take(5)->get();
foreach ($pembimbings as $p) {
    echo "ID: {$p->id} | Nama: {$p->nama} | Email: {$p->email} | Status: {$p->status} | Created: {$p->created_at}\n";
}

echo "\n=== Latest Users with role pembimbing ===\n";
$users = User::where('role', 'pembimbing')->latest()->take(5)->get();
foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role} | Created: {$u->created_at}\n";
}