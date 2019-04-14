<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function pruebas(Request $request){
		return "Accion de Pruebas de Post-Controller";
	}
}
