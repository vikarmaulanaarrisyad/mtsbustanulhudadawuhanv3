<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_parent_id')->orderBy('menu_position');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'menu_parent_id');
    }
}
