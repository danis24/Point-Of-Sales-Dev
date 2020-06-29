<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    protected $fillable = [
        "date", "member_id", "details", "qty", "price", "down_payment"
    ];

    public function member()
    {
        return $this->belongsTo("App\Member", "member_id");
    }
}
