<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request){
		//authToken
		try {
			
			$request->validate([
			  'email' => 'email|required',
			  'password' => 'required',
			  'device_name' => 'required',
			]);
		
			$credentials = request(['email', 'password']);
			if (!Auth::attempt($credentials)) {
			  return response()->json([
				'status_code' => 500,
				'message' => 'The provided credentials are incorrect.'
			  ]);
			}
			$user = User::where('email', $request->email)->with('country')->first();
			$user->avatar = "storage/".$user->avatar;
			if ( ! Hash::check($request->password, $user->password, [])) {
			   throw new \Exception('Wrong Password');
			}
			$tokenResult = $user->createToken($request->device_name)->plainTextToken;
			return response()->json([
			  'status_code' => 200,
			  'access_token' => $tokenResult,
			  'token_type' => 'Bearer',
			  'user' => $user
			]);	
		}catch (Exception $error){
			
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in Login',
			  'error' => $error,
			]);
		}
	}
	
		/**
 * API Register
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
	public function register(Request $request)
	{
	   // die('here I ');
		$rules = [
			'name' => 'required',
			'email'    => 'unique:users|required',
			'password' => 'required',
		];

		$input     = $request->only('name', 'email','password');
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {
			return response()->json(['success' => false, 'error' => $validator->messages()]);
		}
		$name = 	$request->name;
		$email    = $request->email;
		$password = $request->password;
		$dob = !empty($request->dob)?$request->dob:"0";
		$country = !empty($request->country)?$request->country:0;
		$phone = !empty($request->phone)?$request->phone:0;
		
		$user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->phone = $phone;
        $user->country = $country;
        $user->dob = $dob;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->district = $request->district;
        $user->gender = $request->gender;
        $user->save();


		try{
			
			return response()->json([
			  'status_code' => 200,
			  'message' => 'User Created successfuly',
			  'user' => $user,
			]);
		}catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in creating new user!',
			  'error' => $error,
			]);
		}

	}
	
	public function logout(Request $request){
		$request->user()->currentAccessToken()->delete();
		return response()->json([
			  'status_code' => 200,
			  'message' => 'Token Deleted successfuly!',
			
		]);
	}
	
	
	public function verify_user(Request $request){
	
	    $rules = [
			'email'    => 'unique:users|required',
			'phone' => 'unique:users|required',
		];

		$input     = $request->only('email','phone');
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {
			return response()->json(['status_code' => 500,'success' => false, 'error' => $validator->messages()]);
		}
	
	
		return response()->json([
			  'status_code' => 200,
			  'message' => 'verified successfuly!',
			
		]);
	}
	
	
}
