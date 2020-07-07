<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Purchase extends Model
{
    use LogsActivity;
    
    protected $table = 'purchase';
    protected $primaryKey = 'purchase_id';

    public function division()
    {
        return $this->belongsTo("App\Division");
    }

    public function payment()
    {
        return $this->belongsTo("App\Payment");
    }
}
