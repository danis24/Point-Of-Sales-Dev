<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Spending extends Model
{
    use LogsActivity;
    
    protected $table = 'spending';
    protected $primaryKey = 'spending_id';

    public function division()
    {
        return $this->belongsTo("App\Division");
    }

    public function payment()
    {
        return $this->belongsTo("App\Payment");
    }
}
