<?php
use App\Models\User;

try {
    foreach(User::take(5)->get() as $u) {
        echo $u->email . " | " . $u->roles->pluck('name')->implode(',') . PHP_EOL;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
