<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'custom_url',
        'recipient_name',
        'creator_name',
        'creator_hint',
        'room_context',
        'status',
        'current_step',
        'relationship_choice',
        'contact_type',
        'contact_value',
        'note_from_recipient',
        'opened_at',
        'is_destroyed',
        'destroyed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'destroyed_at' => 'datetime',
        'is_destroyed' => 'boolean',
    ];
}

