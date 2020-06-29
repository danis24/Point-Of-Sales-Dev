<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $table = "supplier_products";

    protected $fillable = [
        "product_name",
        "product_brand",
        "price",
        "supplier_id"
    ];
}
