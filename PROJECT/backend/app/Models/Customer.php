<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function getNameAttribute()
    {
        return $this->user ? "{$this->user->first_name} {$this->user->last_name}" : '';
    }
}
