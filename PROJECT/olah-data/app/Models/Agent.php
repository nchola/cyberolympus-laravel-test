<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agent';
    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'agent_id');
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            Order::class,
            'agent_id', // Foreign key on orders table
            'id', // Foreign key on customers table
            'id', // Local key on agents table
            'customer_id' // Local key on orders table
        );
    }
}
