<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseDetails extends Model
{
    use LogsActivity;
    
    protected $table = 'purchase_details';
    protected $primaryKey = 'purchase_details_id';
}
