<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
class PatientController extends Controller
{
    
    public function store(Request $req){
   
        $data = $req->all();

        try{
           
           
            $rules = [
                'name' => 'required',
                'email'    => 'unique:patients|required',
            ];
            
            $input     = $req->only('name', 'email');
            $validator = Validator::make($input, $rules);
            
            
            if ($validator->fails()) {
                return response()->json(['success' => false,'status_code' => 500, 'error' => $validator->messages()]);
            }  

            $patient = new Patient();
            $img_array = array('patient_picture','perscription');
            foreach($data as $key => $value){
                if(!in_array($key,$img_array)){
                    $patient->$key = $value;
                }
            }
            
            $user = $req->user();
            $patient->user_id = $user->id;
            if($req->file()) {
    
            // code for display picture 
                if($req->hasFile('patient_picture')) {
                    
                    $input = $req->only('patient_picture');        
                  
                    $rules = array('patient_picture' => 'mimes:jpeg,jpg,png|max:10000',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $donor_picture = time().'_'.$req->patient_picture->getClientOriginalName();
                    $dp = $req->file('patient_picture')->storeAs('patient_picture', $donor_picture, 'public');
                    
                    $patient->patient_picture = 'storage/'.$dp;
                    
                    
                }
    
    
             // code for positive report picture 
                if($req->hasFile('perscription')) {
                    
                    $input = $req->only('perscription');        
                  
                    $rules = array('perscription' => 'mimes:csv,txt,xlx,xls,pdf,jpg,png|max:10000',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $perscription = time().'_'.$req->perscription->getClientOriginalName();
                    $PostivefilePath = $req->file('perscription')->storeAs('perscription', $perscription, 'public');
                    
                    $patient->perscription = 'storage/'.$PostivefilePath;
                    
                    
                }

            }
        
            $patient->save();
        
			return response()->json([
			  'status_code' => 200,
			  'message' => 'Patient Created successfuly',
			]);
		}catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in creating new Patient!',
			  'error' => $error,
			]);
		}
    }
    
    
    public function editPatient(Request $req){
        
        $data = $req->all();

        try{
           
           
            $rules = [
                'name' => 'required',
                'email'    => 'required|email|unique:patients,email,'.$data['patient_id'],
            ];
            
            $input     = $req->only('name', 'email');
            $validator = Validator::make($input, $rules);
            
            
            if ($validator->fails()) {
                return response()->json(['success' => false,'status_code' => 500, 'error' => $validator->messages()]);
            }  

            $patient = Patient::find($data['patient_id']);
            $img_array = array('patient_picture','perscription','patient_id');
            foreach($data as $key => $value){
                if(!in_array($key,$img_array)){
                    $patient->$key = $value;
                }
            }
            
            if($req->file()) {
    
            // code for display picture 
                if($req->hasFile('patient_picture')) {
                    
                    $input = $req->only('patient_picture');        
                  
                    $rules = array('patient_picture' => 'mimes:jpeg,jpg,png|max:10000',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $donor_picture = time().'_'.$req->patient_picture->getClientOriginalName();
                    $dp = $req->file('patient_picture')->storeAs('patient_picture', $donor_picture, 'public');
                    
                    $patient->patient_picture = 'storage/'.$dp;
                    
                    
                }
    
    
             // code for positive report picture 
                if($req->hasFile('perscription')) {
                    
                    $input = $req->only('perscription');        
                  
                    $rules = array('perscription' => 'mimes:csv,txt,xlx,xls,pdf,jpg,png|max:10000',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $perscription = time().'_'.$req->perscription->getClientOriginalName();
                    $PostivefilePath = $req->file('perscription')->storeAs('perscription', $perscription, 'public');
                    
                    $patient->perscription = 'storage/'.$PostivefilePath;
                    
                    
                }

            }
        
            $patient->save();
        
			return response()->json([
			  'status_code' => 200,
			  'message' => 'Patient Updated successfuly',
			]);
		}catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in Updating Patient!',
			  'error' => $error,
			]);
		}

    }
    
    
    public function list(Request $req){
    
    // DB::enableQueryLog();

        $list = Patient::with(['user','blood'])->where('user_id', '=', Auth::user()->id)->get();
      
    //return  $query = DB::getQueryLog();
        if($list->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No Patient!",
        	]);
        }else{
             return response()->json([
        	  'status_code' => 200,
        	  'patients' => $list,
        	]);
        }
         
    }
    
    
}
