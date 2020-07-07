<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use LogsActivity;
    
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    public function purchase(){
    	return $this->hasMany('App\Purchase', 'supplier_id');
    }
}
