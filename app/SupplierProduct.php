<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierProduct extends Model
{
    use LogsActivity;
    
    protected $table = "supplier_products";

    protected $fillable = [
        "product_name",
        "product_brand",
        "price",
        "supplier_id"
    ];
}
