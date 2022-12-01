<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order_Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'status',
        'customer_id',
        'total_price',
        'total_paid',
        'total_paid_with_points',
        'points_gained',
        'points_used_to_pay',
        'payment_type',
        'payment_reference',
        'date',
        'delivered_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order_item()
    {
        return $this->hasMany(Order_Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
