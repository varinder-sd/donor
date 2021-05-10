<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    //
    
    public function editUser(Request $request){
        
        try{
        
            $validator = Validator::make($request->all(), [
                "name" => 'required|string|min:3|max:50',
                "email" => 'email|max:255',
                "phone" => 'required|min:10',
                "country" => 'required|integer',
                "state" => 'required|integer',
                "city" => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()->all()], 400);
            }
                
            $users = $request->user();
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->dob = $request->dob;
            $users->country = $request->country;
            $users->state = $request->state;
            $users->district = $request->district;
            $users->city = $request->city;
            $users->gender = $request->gender;
            
            if($request->hasFile('avatar')) {
                
                $input = $request->only('avatar');        
                  
                $rules = array('avatar' => 'mimes:jpeg,jpg,png|max:20048',);

                $validator = Validator::make($input, $rules);

                if ($validator->fails()) {
                  return response()->json($validator->errors());
                }
                
                if(!empty($users->avatar)){
                  
                   unlink(public_path('storage/'.$users->avatar));
                 
                }
                $avatar = time().'_'.$request->avatar->getClientOriginalName();
                $users->avatar = $request->file('avatar')->storeAs('users', $avatar, 'public');
            }
            
            $users->save();
            $users->avatar = 'storage/'.$users->avatar;
            return response()->json([
    			  'status_code' => 200,
    			  'message' => 'User Updated successfuly',
    			  'user' =>$users
    		]);
		
        }catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in updating data!',
			  'error' => $error,
			]);
		}

    }
}
