<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    public function menu()
    {
        return $this->morphOne(Menu::class, 'content');
    }
}
