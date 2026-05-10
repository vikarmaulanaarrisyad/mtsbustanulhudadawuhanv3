<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'budi@guru.com')->first();
if ($user) {
    echo "User: {$user->name}\n";
    echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "Permissions (direct): " . $user->permissions->pluck('name')->implode(', ') . "\n";
    echo "Permissions (via roles): " . $user->getPermissionsViaRoles()->pluck('name')->implode(', ') . "\n";
} else {
    echo "User not found\n";
}
