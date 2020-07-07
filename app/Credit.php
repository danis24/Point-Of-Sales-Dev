<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Credit extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        "description", "nominal", "division_id", "payment_id"
    ];
    
    public function division()
    {
        return $this->belongsTo("App\Division", "division_id");
    }

    public function payment()
    {
        return $this->belongsTo("App\Payment", "payment_id");
    }
}
