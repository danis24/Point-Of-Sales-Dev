<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PreOrder extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $fillable = [
        "division_id", "date", "member_id", "details", "qty", "price", "down_payment"
    ];

    public function member()
    {
        return $this->belongsTo("App\Member", "member_id");
    }

    public function division()
    {
        return $this->belongsTo("App\Division");
    }

    public function repayments()
    {
        return $this->hasMany("App\Repayment");
    }
}
