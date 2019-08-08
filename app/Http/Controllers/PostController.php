<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;

class PostController extends Controller
{
	public function __construct(){
		$this -> middleware('api.auth', ['except' => ['index','show']]);
	}
    public function pruebas(Request $request){
		return "Accion de Pruebas de Post-Controller";
	}

	public function index(){
		$posts = Post::all()->load('category');
		return response()->json([
			'code' => 200,
			'status' => 'success',
			'post' => $posts
		],200);
		
	}
	public function show($id){
		$post = Post::find($id)->load('category');

		if(is_object($post)){
			$data= [
				'code' => 200,
				'status' => 'success',
				'post' => $post
			];
		}else {
			[
				'code' => 404,
				'status' => 'error',
				'message' => 'la Entrada no existe'
			];
		}
		return response()->json($data, $data['code']);
	}
	
}
