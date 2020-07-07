<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Selling extends Model
{
    use LogsActivity;
    
    protected $table = 'selling';
    protected $primaryKey = 'selling_id';

    public function division()
    {
        return $this->belongsTo("App\Division");
    }

    public function payment()
    {
        return $this->belongsTo("App\Payment");
    }
}
