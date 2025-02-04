<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'email',
        'role',
        'token',
        'status',
        'admin_id',
        'expires_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
