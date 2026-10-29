<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'priority',
        'status',
        'requester_name',
        'requester_email',
        'avatar_url',
        'assigned_agent',
    ];

    public function getAvatarAttribute(): string
    {
        if ($this->avatar_url) {
            return $this->avatar_url;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->requester_name) . '&background=f59e0b&color=ffffff&bold=true&size=128';
    }
}
