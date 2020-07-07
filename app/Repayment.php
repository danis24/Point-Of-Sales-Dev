<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        "date", "pre_order_id", "division_id", "payment_id", "nominal", "details"
    ];

    public function preOrder()
    {
        return $this->belongsTo("App\PreOrder", "pre_order_id");
    }

    public function division()
    {
        return $this->belongsTo("App\Division");
    }

    public function payment()
    {
        return $this->belongsTo("App\Payment");
    }
}
