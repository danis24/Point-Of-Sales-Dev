<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "type",
        "bank_name",
        "account_number",
        "account_name"
    ];
}
