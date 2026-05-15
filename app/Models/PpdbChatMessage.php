<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppdb_chat_room_id',
        'user_id',
        'sender_type',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
    ];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('d M Y, H:i');
    }

    public function room()
    {
        return $this->belongsTo(PpdbChatRoom::class, 'ppdb_chat_room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
