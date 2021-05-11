<?php

namespace App\Http\Controllers;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Imports\BulkImport;
use App\Imports\BulkImprt;
use Maatwebsite\Excel\Facades\Excel;
class DoctorController extends Controller
{
    
    public function store(Request $req){
   
        $data = $req->all();

        try{
           
           
            $rules = [
                'name' => 'required',
                'email' => 'unique:consulting_doctors|required',
                'mobile' => 'unique:consulting_doctors|required',
            ];
            
            $input     = $req->only('name', 'email','mobile');
            $validator = Validator::make($input, $rules);
            
            
            if ($validator->fails()) {
                return response()->json(['success' => false,'status_code' => 500, 'error' => $validator->messages()]);
            }  

            $doctor = new Doctor();
            $img_array = array('donor_picture');
            foreach($data as $key => $value){
                if(!in_array($key,$img_array)){
                    $doctor->$key = $value;
                }
            }
            
//            $user = $req->user();
         
    
          //   $donor->user_id = $user->id;
        
            $doctor->save();
        
			return response()->json([
			  'status_code' => 200,
			  'message' => 'Doctor Created successfuly',
			]);
		}catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in creating new Patient!',
			  'error' => $error,
			]);
		}
    }
    
    
    public function list(Request $req){
    
    // DB::enableQueryLog();

        $list = Doctor::where('status', '=', 1)->get();
      
    //return  $query = DB::getQueryLog();
        if($list->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No doctor found!",
        	]);
        }else{
             return response()->json([
        	  'status_code' => 200,
        	  'donors' => $list,
        	]);
        }
         
    }
    
    
    public function Alist(Request $req){
    
    // DB::enableQueryLog();

        $all = Doctor::with(['user','blood','country','state','district', 'city'])
        ->paginate(15);
    
    //return  $query = DB::getQueryLog();
        if($all->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No donor you have in your city!",
        	]);
        }else{
             return view('donor.list',compact('all'));
        }
         
    }
    
    
    public function search(Request $request){
    
    // DB::enableQueryLog();
    $data = $request->all();
    
    
    if(isset($data['start_time']) && !empty($data['start_time'])){
        $clause_blood = "=";
        $start_time = $data['start_time'];
    }else{
        $clause_blood = "!=";
        $start_time = 0;
    }
    
    
    if(isset($data['end_time']) && $data['end_time'] != ''){
        $clause_covid = "=";
        $end_time = $data['end_time'];
    }else{
        $clause_covid = ">=";
        $end_time = -1;
    }
    

    $list = Doctor::where('status','=',1)
        ->whereTime('start_time', '>', $start_time)
        ->whereTime('end_time', '<', $end_time)
        ->get(); 
 
    //return  $query = DB::getQueryLog();
        if($list->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No doctor found!",
        	]);
        }else{
             return response()->json([
        	  'status_code' => 200,
        	  'donors' => $list,
        	]);
        }
         
    }
    
//end class;
}
