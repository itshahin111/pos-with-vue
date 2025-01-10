<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInvoice extends Model
{
    protected $fillable = ['product_id', 'invoice_id','user_id', 'qty', 'sale_price'];

    function product(){
        return $this->belongsTo(Product::class);
    }
}
