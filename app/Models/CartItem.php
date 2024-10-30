<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];
    use HasFactory;
    protected $fillale = [
        'user_id',
        'quantity',
        'product_id',
    ];
    public function user(){
        $this->belongsTo(User::class);
    }
    public function product(){
        $this->belongsTo(Product::class);
    }
}
