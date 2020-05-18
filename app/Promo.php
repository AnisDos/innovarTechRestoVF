<?php

namespace App;
use App\Admin;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    
protected $guarded =[];
 
 public function admin()
 {
     return $this->belongsTo(Admin::class);
 }
 
}
