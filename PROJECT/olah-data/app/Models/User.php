<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // opsional, default sudah 'users'
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke Customer (Referral)
    public function referredCustomers()
    {
        return $this->hasMany(Customer::class, 'referral_id');
    }
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    // Jika ingin menampilkan nama lengkap:
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
