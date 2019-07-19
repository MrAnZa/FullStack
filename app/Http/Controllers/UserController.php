<?php
namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\User;

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

				if(!empty($params) && !empty($paramsArray)){
						//Limpiar Datos
					$paramsArray=array_map('trim',$paramsArray);

						//Validar Datos
					$validate = \Validator::make($paramsArray,[
						'name' => 'required|alpha',
						'surname'=>'required|alpha',
						'email'=>'required|email|unique:users', 
						'password'=>'required'
					]); //Comprobar si el usuario existe
					if($validate->fails()){
						$data = array(
							'status' => 'error' ,
							'code' => 404 ,
							'message' => 'El usuario no se ha creado',
							'erros' =>  $validate->errors());

						return response()->json($data,$data['code']);
					}else{
						//Cifrar Pass
							//$pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);
							$pwd=hash('sha256', $params->password);
						//Crear Usario
							$user = new User();
							$user->name = $paramsArray['name'];
							$user->surname = $paramsArray['surname'];
							$user->email = $paramsArray['email'];
							$user->password = $pwd;
							$user->role = 'ROLE_USER';
						//Guardar el Usuario
							$user->save();

							$data = array(
								'status' => 'success' ,
								'code' => 200 ,
								'message' => 'El usuario creado correctamente');

						return response()->json($data,$data['code']);
					}
				}else{
					$data = array(
						'status' => 'error' ,
						'code' => 404 ,
						'message' => 'Los Datos Enviados no son correctos');
					
					return response()->json($data,$data['code']);
				}

			}

			public function login(Request $request) {
				$JWTAuth= new \JWTAuth();
				//Recibir Datos por POST
				$json=$request->input('json',null);
				$params=json_decode($json);
				$paramsArray=json_decode($json,true);
				//Validar Datos
				$validate = \Validator::make($paramsArray,[
					'email'=>'required|email', 
					'password'=>'required'
				]);
				if($validate->fails()){
				//La validacion ha fallado
					$signup = array(
						'status' => 'error' ,
						'code' => 404 ,
						'message' => 'El usuario no se ha podido identificar',
						'erros' =>  $validate->errors());

					#return response()->json($signup,$signup['code']);
				}else{
					//Cifro la password
					$pwd=hash('sha256', $params->password);
					//Devolver Token o Datos
					$signup= $JWTAuth->signup($params->email,$pwd);
					if(!empty($params->gettoken)){
						$signup= $JWTAuth->signup($params->email,$pwd,true);
					}
				}
				
				
				return response()->json($signup,200);
			}

			public function update(Request $request){
				$token=$request->header('Authorization');
				$jwtAuth=new \JWTAuth();
				$checkToken=$jwtAuth->checkToken($token);
				if($checkToken){
					echo "<h1>Login correcto</h1>";
				}else{
					echo "<h1>Login Incorrecto</h1>";
				}
				die();
			}
		}
