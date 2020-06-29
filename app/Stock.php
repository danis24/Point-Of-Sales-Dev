<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        "product_id",
        "type",
        "stocks",
        "keterangan"
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
