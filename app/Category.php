<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    //Relacion de Uno a Muchos
    public function post(){
    	return $this->hasMany('App\Post');
    }


}
