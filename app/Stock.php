<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Stock extends Model
{
    use LogsActivity;
    
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
