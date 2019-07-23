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
						'status' => 'error2019' ,
						'code' => 404 ,
						'message' => 'El usuario no se ha podido identificar',
						'erros' =>  $validate->errors());

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
				//comprobar si el usuario esta identificado
				$token=$request->header('Authorization');
				$jwtAuth=new \JWTAuth();
				$checkToken=$jwtAuth->checkToken($token);
					//Actualizar Usuario
					//Recoger los Datos por POST
					$json=$request->input('json',null);
					$params_array=json_decode($json,true);

				if($checkToken && !empty($params_array)){
					
					//Sacar usuario identificado
					$user=$jwtAuth->checkToken($token,true);
					//Validar datos
					$validate=\Validator::make($params_array,[
						'name' => 'required|alpha',
						'surname'=>'required|alpha',
						'email'=>'required|email|unique:users,'.$user->sub
					]);
					//Quitar los datos que no quiero actualizar
					unset($params_array['id']);
					unset($params_array['role']);
					unset($params_array['password']);
					unset($params_array['created_at']);
					unset($params_array['remember_token']);
					//Actualizar usuario en BD
						$user_update=User::where('id',$user->sub)->update($params_array);
					//Devolver array con resultado
					$data = array(
						'code'=>200,
						'status'=>'success',
						'user'=>$user,
						'changes'=>$params_array
					);
				}else{
					$data = array(
						'code'=>400,
						'status'=>'error',
						'message'=>'El usuario no esta identificado'
					);
				}
				
				return response()->json($data,$data['code']);
			}
			
			public function upload(Request $request){
				$data = array(
					'code'=>400,
					'status'=>'error',
					'message'=>'El usuario no esta identificado2019'
				);
				return response($data,$data['code'])->header('Content-Type','text/plain');
			}
		}
