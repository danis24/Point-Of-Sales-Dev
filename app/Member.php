<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends Model
{
    use LogsActivity;
    
    protected $table = 'member';
    protected $primaryKey = 'member_id';

    public function selling(){
    	return $this->hasMany('App\Selling', 'supplier_id');
    }
}
