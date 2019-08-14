<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;

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
			$data=  [
				'code' => 404,
				'status' => 'error',
				'message' => 'la Entrada no existe'
			];
		}
		return response()->json($data, $data['code']);
	}
	public function store(Request $request) {
		//Recoger datos por POST
		$json = $request->input('json',null);
		$params = json_decode($json);
		$params_array = json_decode($json,true);

		if(!empty($params_array)){
		//Conseguir usuario identificado
		$user = $this->getIdentity($request);
		//Validar datos
			$validate = \Validator::make($params_array,[
				'title' => 'required',
				'content' => 'required',
				'category_id' => 'required',
				'image' => 'required'
				
			]);
			if ($validate->fails()) {
				$data=  [
					'code' => 400,
					'status' => 'error',
					'message' => 'No se ha Guardado, faltan datos'
				];
			}else {
				//Guardar el articulo
				$post = new Post();
				$post->user_id=$user->sub;
				$post->category_id=$params->category_id;
				$post->title=$params->title;
				$post->content=$params->content;
				$post->image=$params->image;
				$post->save();
				
				$data=  [
					'code' => 200,
					'status' => 'success',
					'post' => $post
				];

			}
		
		}else{
			$data=  [
				'code' => 400,
				'status' => 'error',
				'message' => 'envia los datos correctamente'
			];
		}
		//Devolver respuesta
		return response()->json($data, $data['code']);
	}
	public function update($id, Request $request){
		$data = array(
			'code' => 400,
			'status' => 'error',
			'message' => 'Datos Enviados Incorrectos'
		);
		//Recoger los datos por POST
		$json = $request->input('json',null);
		$params_array = json_decode($json,true);
		if(!empty($params_array)){
		//Validar los Datos
		$validate = \Validator::make($params_array,[
			'title' => 'required',
			'content' => 'required',
			'category_id' => 'required',
		]);
		if($validate->fails()){
			$data = array(
				'code' => 400,
				'status' => 'error',
				'message' => json_encode($validate->errors())
			);
		}else{
		//Eliminar lo que no queremos actualizar
		unset($params_array['id']);
		unset($params_array['user_id']);
		unset($params_array['created_at']);
		unset($params_array['user']);
		//Conseguir usuario identificado
		$user = $this->getIdentity($request);
		//Conseguir el Registro
		$post = Post::where('id',$id)->where('user_id',$user->sub)->first();
		if(!empty($post) && is_object($post)){
		//Actualizar el Registro en concreto
		$post->update($params_array);
		//Devolver algo
		$data = array(
			'code' => 200,
			'status' => 'success',
			'post' => $post,
			'changes' => $params_array
		);
			}
		}
	}

		return response()->json($data, $data['code']);
	}
	
	public function destroy($id, Request $request){
		//Conseguir usuario identificado
		$user = $this->getIdentity($request);

		//Conseguir el Registro
		$post = Post::where('id',$id)->where('user_id',$user->sub)->first();
		if(!empty($post)){
		//Borrarlo
		$post->delete();
		//Devolver Algo
		$data = [
			'code' => 200,
			'status' => 'success',
			'post' => $post
		];

		return response()->json($data, $data['code']);
	}else{
		$data = [
			'code' => 404,
			'status' => 'error',
			'message' => 'El post no existe'
		];	
		return response()->json($data, $data['code']);
	}
	}
	private function getIdentity(Request $request){
		$jwtAuth = new JwtAuth();
		$token = $request->header('Authorization',null);
		$user = $jwtAuth->checkToken($token,true);
		return $user;
	}
}
