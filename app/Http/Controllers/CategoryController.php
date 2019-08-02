<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller
{
    public function pruebas(Request $request){
		return "Accion de Pruebas de Category-Controller";
	}

	public function index(){
		$categories = Category::all();

		return response()->json([
			'code' =>  200,
			'status' => 'success',
			'categories' => $categories
		]);
	}

	public function show($id){
		$category = Category::find($id);

		if (is_object($category)) {
			$data =[
				'code' => 200,
				'status' => 'success',
				'category' => $category
			];
		}else {
			$data =[
				'code' => 404,
				'status' => 'Error',
				'mesage' => 'la categoria no existe'
			];
		}
		return response()->json($data, $data['code']);
	}
}
