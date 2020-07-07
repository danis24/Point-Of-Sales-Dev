<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SellingDetails extends Model
{
    use LogsActivity;
    
    protected $table = 'selling_details';
    protected $primaryKey = 'selling_details_id';
}
