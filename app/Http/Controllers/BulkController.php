<?php

namespace App\Http\Controllers;
use App\Models\Bulk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BulkController extends Controller
{
    
    
    public function Alist(Request $req){
    
    // DB::enableQueryLog();

        $all = Bulk::with(['user'])
        ->get();
      
    //return  $query = DB::getQueryLog();
        if($all->isEmpty()){
            return response()->json([
        	  'status_code' => 500,
        	  'message' => "No donor you have in your city!",
        	]);
        }else{
             return view('donor.Elist',compact('all'));
        }
         
    }
    
    

    
//end class;
}
