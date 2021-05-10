<?php

namespace App\Imports;

use App\Models\Bulk;
use App\Models\Donor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
class BulkImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
       // echo "<pre>"; print_r($row);
        
        if(!empty($row['city'])){
            $cResult = DB::table('cities')->where([
             ['country_id',101],['name','like',$row['city']]
            ])->first();
        
            
            if(empty($cResult)){
                $city_key = 'city_name';
                $city = $row['city'];
            }else{
                $city_key = 'city_id';
                $city = $cResult->id;
                
                $country = $cResult->country_id;
                
                $state = $cResult->state_id;
                
                $district = $cResult->district_id;
                
                
            }
        }else{
            $city_key = 'city_id';
            $city = 0;
        }
        
        
       if(!empty($row['blood_group'])){
           
            $bl = str_replace(' ','',strtolower($row['blood_group']));
            
            $B_postive =  array('bpositive','b+','b+ve');
           
            $B_negtive =  array('bnegative','b-','b-ve');
           
            $A_postive =  array('apositive','a+','a+ve');
            
            $A_negtive =  array('anegative','a-','a-ve');
            
            $AB_postive =  array('abpositive','ab+','ab-');
            
            $AB_negtive =  array('abnegative','ab-','ab-ve');
            
            $O_postive =  array('opositive','o+','o+ve');
            
            $O_negtive =  array('onegative','o-','o-ve');
            
            if(in_array($bl,$B_postive)){
                $bname = 'B+';
            }elseif(in_array($bl,$B_negtive)){
                $bname = 'B-';
            }elseif(in_array($bl,$A_postive)){
                $bname = 'A+';
            }elseif(in_array($bl,$A_negtive)){
                $bname = 'A-'; 
            }elseif(in_array($bl,$AB_postive)){
                $bname = 'AB+';   
            }elseif(in_array($bl,$AB_negtive)){
                $bname = 'AB-';   
            }elseif(in_array($bl,$O_postive)){
                $bname = 'O+';   
            }elseif(in_array($bl,$O_negtive)){
                $bname = 'O-';   
            }else{
               $bname = 'other'; 
            }
            
            $cBlood_group = DB::table('blood_group')->where([
            ['name','like',$bname]
            ])->first();
        
            
            if(empty($cBlood_group)){
                $b_key = 'blood_name';
                $blood = $row['blood_group'];
            }else{
                $b_key = 'blood_group_id';
                $blood = $cBlood_group->id;
            }
        }else{
            $b_key = 'blood_group_id';
            $blood = 0;
        }

       
       
       if(isset($row['can_donate']) && ($row['can_donate'] == 'yes' || $row['can_donate'] == '1' || $row['can_donate'] == 'Y')){
           $can_donate = 1;
       }else{
           $can_donate = 0;
       }   
       
       if(empty($row['covid_postive_date'])){
           $row['covid_postive_date'] = '30/03/2021';
       }
       
        if (str_contains($row['covid_postive_date'], '/')) {

       }else{
           
$excel_date = $row['covid_postive_date']; 
$unix_date = ($excel_date - 25569) * 86400;
$excel_date = 25569 + ($unix_date / 86400);
$unix_date = ($excel_date - 25569) * 86400;
$row['covid_postive_date'] =  gmdate("d/m/Y", $unix_date);
       }
        
      
        $donor = new Donor(); 
        $donor->user_id = 4;
        $donor->name    = $row['name'];
        $donor->email    = 'groffer@groffer.com';
        $donor->donor_picture='storage/users/default.png';
        $donor->created_by='4';
        $donor->can_donate = $row['can_donate'];
        $donor->covid_postive_date     =  date('Y-m-d', strtotime(str_replace('/','-',$row['covid_postive_date'])));
        $donor->$b_key     = $blood;
        $donor->pin_code     = $row['pin_code'];
        $donor->$city_key     =  $city;
        
        if(isset($country)){
            $donor->country_id = $country;
        }
        
        if(isset($state)){
            $donor->state_id = $state;
        }
        
        if(isset($country)){
            $donor->district_id = $district;
        }
        
        $donor->alternate_mobile_number = $row['mobile'];
        $donor->donor_status = $can_donate;
        $donor->country_id = 101;
         
        $donor->save();
 
          
        
     //   if(isset($row['can_donate']) && ($row['can_donate'] == 'yes' || $row['can_donate'] == '1' || $row['can_donate'] == 'Y')){
        return new Bulk([
            'user_id' => 4,
            'name'     => $row['name'],
            'email'    => 'groffer@groffer.com',
            'donor_picture'=>'1',
            'created_by'=>'4',
            'can_donate' => $row['can_donate'],
            'covid_postive_date'     => $row['covid_postive_date'],
            'blood_group'     => $row['blood_group'],
            'pin_code'     => $row['pin_code'],
            'city_id'     => $row['city'],
            'alternate_mobile_number' => $row['mobile']
        ]); 
     //   }
        die;

    }
}
