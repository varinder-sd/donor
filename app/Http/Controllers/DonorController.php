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
use Maatwebsite\Excel\Facades\Excel;
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
                    $dp = $req->file('donor_picture')->storeAs('donors', $donor_picture, 'public');
                    
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
       // ->where('city_id', '=', Auth::user()->city)
        ->where('user_id', '=', Auth::user()->id)
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
    
    
    public function Alist(Request $req){
    
    // DB::enableQueryLog();

        $all = Donor::with(['user','blood','country','state','district', 'city'])
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
    
    
    if(isset($data['blood_group_id']) && !empty($data['blood_group_id'])){
        $clause_blood = "=";
        $blood = $data['blood_group_id'];
    }else{
        $clause_blood = "!=";
        $blood = 0;
    }
    
    
    if(isset($data['isCovidWarrior']) && $data['isCovidWarrior'] != ''){
        $clause_covid = "=";
        $covid = $data['isCovidWarrior'];
    }else{
        $clause_covid = ">=";
        $covid = -1;
    }
    
    if(isset($data['search_type']) && $data['search_type'] == 'pincode'){
            $list = Donor::with(['user','blood','country','state','district', 'city'])
        ->where('blood_group_id', $clause_blood, $blood)
        ->where('pin_code', '=', $data['pincode'])
        ->where('covid_recovered_warrior', $clause_covid, $covid)
        ->where('donor_status','=',1)
         ->where('donate_blood_or_plasma','=',$data['require_blood_or_plasma'])
        ->get(); 
    }else{
        
        if(isset($data['district_id']) && $data['district_id'] != 0){
            $clause_distt = "=";
            $dist = $data['district_id'];
        }else{
            $clause_distt = "!=";
            $dist = 0;
        }
        
        if(isset($data['ciy_id']) && $data['ciy_id'] != 0){
            $clause_city = "=";
            $city = $data['ciy_id'];
        }else{
            $clause_city = "!=";
            $city = 0;
        }
        
        $list = Donor::with(['user','blood','country','state','district', 'city'])
        ->where('blood_group_id', $clause_blood, $blood)
        ->where('covid_recovered_warrior', $clause_covid, $covid)
        ->where('country_id', '=', $data['country_id'])
        ->where('state_id', '=', $data['state_id'])
        ->where('city_id', $clause_city, $city)
        ->where('district_id',  $clause_distt, $dist)
        ->where('donor_status','=',1)
         ->where('donate_blood_or_plasma','=',$data['require_blood_or_plasma'])
        ->get();
        
    }
    
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
    
    
    public function donor_status(Request $request){
    
    try{
        $data = $request->all();
    // DB::enableQueryLog();
    
        $donor = Donor::find($data['donor_id']);
     
        $donor->donor_status = $data['status'];
        
        $donor->save();
    //return  $query = DB::getQueryLog();

             return response()->json([
        	  'status_code' => 200,
        	  'donors' => "donor status updated successfully!",
        	]);

        
    }catch (Exception $error){
		
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Something went wrong!',
			  'error' => $error,
			]);
		}
         
    }
    
    
    public function import() 
    {
        Excel::import(new BulkImport,request()->file('file'));
           
        return  redirect()->route('admin.donor');
    }
    
    
    public function importView() 
    {
        
           
       return view('donor.import');
    }
    
    public function change_status(Request $request) 
    {
        $data = $request->all();
        
        $donor = Donor::find($data['id']);
        
        $donor->donor_status = $data['status'];
        
        $donor->save();
           
       return 1;
    }
    
//end class;
}
