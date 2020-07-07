<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;
    
    protected $table = 'category';
    protected $primaryKey = 'category_id';

    public function product(){
    	return $this->hasMany('App\Product', 'category_id');
    }
}
