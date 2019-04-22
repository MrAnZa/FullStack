<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function pruebas(Request $request){
		return "Accion de Pruebas de User-Controller";
	}

	public function register(Request $request){

		//Recoger los Datos POST
			$json= $request->input('json',null);
			$params=json_decode($json);//objeto
			$paramsArray=json_decode($json,true);//array
		//Validar Datos

		//Cifrar Pass

		//Crear Usario

		$data = array(
			'status' => 'error' ,
			'code' => 404 ,
			'message' => 'El usuario no se ha creado' );

		return response()->json($data,$data['code']);
	}

	public function login(Request $request) {
		return "hola desde login";
	}
}
