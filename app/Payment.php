<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        "type",
        "bank_name",
        "account_number",
        "account_name"
    ];
}
