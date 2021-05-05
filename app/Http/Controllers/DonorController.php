<?php

namespace App\Http\Controllers;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
class DonorController extends Controller
{
    
    public function store(Request $req){
   
        $data = $req->all();

        try{
           
           
            $rules = [
                'name' => 'required',
                'email'    => 'unique:donors|required',
            ];
            
            $input     = $req->only('name', 'email');
            $validator = Validator::make($input, $rules);
            
            
            if ($validator->fails()) {
                return response()->json(['success' => false,'status_code' => 500, 'error' => $validator->messages()]);
            }  

            $donor = new Donor();
            $img_array = array('donor_picture','covid_postive_report','covid_negtive_report','diet_status','health_status','other');
            foreach($data as $key => $value){
                if(!in_array($key,$img_array)){
                    $donor->$key = $value;
                }
            }
            
            
            $health_status = $data['health_status'];
            
            $hstatus = explode(',', $health_status);
            
            if(in_array('other', $hstatus)){
                $donor->other = 1;
            }
            
            if(in_array('blood_pressure', $hstatus)){
                $donor->blood_pressure = 1;
            }
            
            if(in_array('diabetes', $hstatus)){
                $donor->diabetes = 1;
            }
            
            if(in_array('thyroid', $hstatus)){
                $donor->thyroid = 1;
            }
            
            $donor->health_status = empty($health_status)?1:0;
            
            
            $diet_status = $data['diet_status'];
            
            $Dstatus = explode(',', $diet_status);
            
            
            if(in_array('smoking', $Dstatus)){
                $donor->smoking = 1;
            }
            
            if(in_array('alchohal', $Dstatus)){
                $donor->alchohal = 1;
            }
            
            if(in_array('vegetarian', $Dstatus)){
                $donor->vegetarian = 1;
            }

            $donor->diet_status = empty($diet_status)?1:0; 
            $user = $req->user();
             // print_r($user); die;  
    
             $donor->user_id = $user->id;
            if($req->file()) {
    
            // code for display picture 
                if($req->hasFile('donor_picture')) {
                    
                    $input = $req->only('donor_picture');        
                  
                    $rules = array('donor_picture' => 'mimes:jpeg,jpg,png|max:2048',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $donor_picture = time().'_'.$req->donor_picture->getClientOriginalName();
                    $dp = $req->file('covid_postive_report')->storeAs('donors', $donor_picture, 'public');
                    
                    $donor->donor_picture = 'storage/'.$dp;
                    
                    
                }
    
    
             // code for positive report picture 
                if($req->hasFile('covid_postive_report')) {
                    
                    $input = $req->only('covid_postive_report');        
                  
                    $rules = array('covid_postive_report' => 'mimes:csv,txt,xlx,xls,pdf,jpg,png|max:2048',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $covid_postive_report = time().'_'.$req->covid_postive_report->getClientOriginalName();
                    $PostivefilePath = $req->file('covid_postive_report')->storeAs('donors_covid_postive', $covid_postive_report, 'public');
                    
                    $donor->covid_postive_report = 'storage/'.$PostivefilePath;
                    
                    
                }
               
                // code for negative report picture 
                if($req->hasFile('covid_negtive_report')) {
                   
         
                    $input = $req->only('covid_negtive_report');        
                  
                    $rules = array('covid_negtive_report' => 'mimes:csv,txt,xlx,xls,pdf,jpg,png|max:2048',);

                    $validator = Validator::make($input, $rules);

                    if ($validator->fails()) {
                      return response()->json($validator->errors());
                    }
                    
                    $covid_negtive_report = time().'_'.$req->covid_negtive_report->getClientOriginalName();
                    $NegtivefilePath = $req->file('covid_negtive_report')->storeAs('donors_covid_negtive', $covid_negtive_report, 'public');
                    
                    $donor->covid_negtive_report = 'storage/'.$NegtivefilePath;
                }
                
                $donor->covid_status = 1;
            }
        
            $donor->save();
        
			return response()->json([
			  'status_code' => 200,
			  'message' => 'Donor Created successfuly',
			]);
		}catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in creating new donor!',
			  'error' => $error,
			]);
		}
    }
    
    
    public function list(Request $req){
    
    // DB::enableQueryLog();

        $list = Donor::with(['user','blood','country','state','district', 'city'])
        ->where('city_id', '=', Auth::user()->city)
        ->get();
      
    //return  $query = DB::getQueryLog();
        if($list->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No donor you have in your city!",
        	]);
        }else{
             return response()->json([
        	  'status_code' => 200,
        	  'donors' => $list,
        	]);
        }
         
    }
    
    
}
