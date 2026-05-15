<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppdb_registrant_id',
        'status',
        'unread_admin',
        'unread_student',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function registrant()
    {
        return $this->belongsTo(PpdbRegistrant::class, 'ppdb_registrant_id');
    }

    public function messages()
    {
        return $this->hasMany(PpdbChatMessage::class, 'ppdb_chat_room_id')->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(PpdbChatMessage::class, 'ppdb_chat_room_id')->latestOfMany();
    }
}
