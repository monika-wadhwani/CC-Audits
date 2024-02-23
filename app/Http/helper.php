<?php

use Illuminate\Support\Facades\Crypt;

use Illuminate\Database\Eloquent\Collection;



use App\PartnerMapping;

use App\LocationMapping;

use App\ProcessMapping;

//use App\ClusterClientMapping;

use App\LobMapping;
use App\User;

use App\Holiday;
use Carbon\Carbon;

function all_non_scoring_obs_options($selected_group)

{

	$temp[1] = [1=>"Yes",2=>"No",15=>"NA"];

	$temp[2] = [3=>"Promoter","Passive","Detractor"];

	$temp[3] = [6=>"Excellent","Good","Average","Poor"];

	$temp[4] = [10=>"Excellent","Good","Average","Poor","Un-exceptable"];



	$temp[5] = [8=>"Average",7=>"Good",16=>"Bad",15=>"NA"];

	$temp[6] = [17=>"Happy",18=>"Neutral",19=>"Angry",15=>"NA"];

	$temp[7] = [20=>"Agent",21=>"Customer",22=>"Process & Product",23=>"Technology"];



	return $temp[$selected_group];

}

function list_non_scoring_obs_options()

{

	$temp_b[1] = "Yes,No,NA";

	$temp_b[2] = "Promoter,Passive,Detractor";

	$temp_b[3] = "Excellent,Good,Average,Poor";

	$temp_b[4] = "Excellent,Good,Average,Poor,Un-exceptable";



	$temp_b[5] = "Average,Good,Bad,NA";

	$temp_b[6] = "Happy,Neutral,Angry,NA";

	$temp_b[7] = "Agent,Customer,Process & Product,Technology";

	$temp_b[8] = "Promoter,Passive,Detractor,NA";

	$temp_b[9] = "Agent,Customer,Process & Product,Technology,NA";

	return $temp_b;

}

function return_non_scoring_observation($index)

{	

	$temp_d[0] = "-";

	$temp_d[1] = "Yes";

	$temp_d[2] = "No";

	$temp_d[3] = "Promoter";

	$temp_d[4] = "Passive";

	$temp_d[5] = "Detractor";

	$temp_d[6] = "Excellent";

	$temp_d[7] = "Good";

	$temp_d[8] = "Average";

	$temp_d[9] = "Poor";

	$temp_d[10] = "Excellent";

	$temp_d[11] = "Good";

	$temp_d[12] = "Average";

	$temp_d[13] = "Poor";

	$temp_d[14] = "Un-exceptable";

	$temp_d[15] = "NA";

	

	$temp_d[16] = "Bad";

	$temp_d[17] = "Happy";

	$temp_d[18] = "Neutral";

	$temp_d[19] = "Angry";

	$temp_d[20] = "Agent";

	$temp_d[21] = "Customer";

	$temp_d[22] = "Process & Product";

	$temp_d[23] = "Technology";

	

	return $temp_d[$index];

}



function return_general_observation($index)

{

	$temp_c[0] = "-";

	$temp_c[1] = "Pass";

	$temp_c[2] = "Fail";

	$temp_c[3] = "Critical";

	$temp_c[4] = "N/A";

	$temp_c[5] = "PWD";



	return $temp_c[$index];

}
// for porter changes external client
function auditDetails($index) {
	$temp_c[0] = "-";
	$temp_c[1] = "Yes";
	$temp_c[2] = "No";
	$temp_c[3] = "Critical";
	$temp_c[4] = "N/A";
	$temp_c[5] = "PWD";
	return $temp_c[$index];
}

function audit_rebuttal_status($index)

{

	$temp_e[] = "Un-seen";

	$temp_e[] = "Seen";

	$temp_e[] = "Raised";



	return $temp_e[$index];

}

function rebuttal_status($index)

{

	$temp_e[] = "Raised";

	$temp_e[] = "Accepted";

	$temp_e[] = "Rejected";



	return $temp_e[$index];

}



function date_to_db($value)

 {

 	return $newDate = date("Y-m-d", strtotime($value));

 }

 function date_to_picker($value)

 {

 	return $newDate = date("m/d/Y", strtotime($value));

 }

 function date_to_user($value)

 {

 	if($value)

 		return $newDate = date("d-m-Y", strtotime($value));

 	else

 		return null;

 }



 function date_time_to_user($value)

 {

 	if($value)

 		return $newDate = date("d-m-Y H:i:s", strtotime($value));

 	else

 		return null;

 }



 function searchForId($id, $array) {

   foreach ($array as $key => $val) {

       if ($val['name'] === $id) {

           return $key;

       }

   }

   return null;

}

function return_feedback_status($status)

{

	$fs[0] = "Not Given";

	$fs[1] = "Feedback by QA";

	$fs[] = "Accepted";

	$fs[] = "Rejected";

	$fs[] = "Pending";



	return $fs[$status];

}

function return_qc_status($status)

{

	$d[0] = 'N/A';

	$d[] = 'Pass with edit';

	$d[] = 'Pass';

	$d[] = 'Failed';



	return $d[$status];

}



function encrypt1($val) {

	//return Crypt::decrypt($val);

	return Crypt::encrypt($val);

}



function skipHoliday($date,$holiday,$delay1,$delay2)
{ 
	
	if($date)
	{
		
		
		$array = [];
		$national_holidays = Holiday::select('holiday_date')->get();
		foreach($national_holidays as $values){
			array_push($array,$values->holiday_date);
		}
		
		for ($i=1; $i < $delay1+1; $i++) {

			
			$date1 = strtotime(date("Y-m-d H:i:s",strtotime("+$i day", "$date")));
			
			$day = date('D', $date1);
			
			$sun =0;
			$sat = 0;
			if ($holiday=='1' && $day=="Sun") {
				$sun =1;
				$delay1 += 1;
				
			}
			if ($holiday=='2' && $day=="Sat") {
				$sun =1;
				$sat = 1;
				$delay1 += 2;
				
			}

			if(gettype(array_search(date('Y-m-d',$date1),$array)) == 'integer'){
				if($sun == 1 || $sat == 1){

				}else {
					$delay1 += 1;
				}
			}
			
			

		}

		
	$date2 = strtotime(date("Y-m-d H:i:s",strtotime("+$delay1 day", "$date")));

	for ($i=1; $i < $delay2+1; $i++) { 
		$date3 = strtotime(date("Y-m-d H:i:s",strtotime("+$i day", "$date2")));
		$day = date('D', $date3);
		$sun =0;
		$sat = 0;
		if ($holiday=='1' && $day=="Sun") {
			$delay2 += 1;
			
		}
		if ($holiday=='2' && $day=="Sat") {
			$delay2 += 2;
			
		}

		if(gettype(array_search(date('Y-m-d',$date3),$array)) == 'integer'){
			if($sun == 1 || $sat == 1){

			}else {
				$delay2 += 1;
			}
		}

	}

	$date4 = date("Y-m-d H:i:s",strtotime("+$delay1 day", "$date"));
	$date5 = date("Y-m-d H:i:s",strtotime("+$delay2 day", "$date2"));

	return $audit_date = array($date4,$date5);
	}
} 



if (! function_exists('divnum')) {

	function divnum($numerator, $denominator)
	{
		return $denominator == 0 ? 0 : ($numerator / $denominator);
	}

}




function sendOtp($mobile,$otp)

{

	$curl = curl_init();

	curl_setopt_array($curl, array(

	  CURLOPT_URL => "http://control.msg91.com/api/sendotp.php?authkey=257165As9f7LO5ec5c407cbb&message=Your verification code is ".$otp."&sender=KAFBIZ&mobile=".$mobile."&otp=".$otp."&otp_expiry=3",

	  CURLOPT_RETURNTRANSFER => true,

	  CURLOPT_ENCODING => "",

	  CURLOPT_MAXREDIRS => 10,

	  CURLOPT_TIMEOUT => 30,

	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

	  CURLOPT_CUSTOMREQUEST => "POST",

	  CURLOPT_POSTFIELDS => "",

	  CURLOPT_SSL_VERIFYHOST => 0,

	  CURLOPT_SSL_VERIFYPEER => 0,

	));



	$response = curl_exec($curl);

	$err = curl_error($curl);



	curl_close($curl);



	if ($err) {

	  return "cURL Error #:" . $err;

	} else {

	  return $response;

	}

}



function get_helper_cluster_clients($user_id){

	$data = ClusterClientMapping::where('user_id',$user_id)->get();

	$emptyArray = []; 

	foreach($data as $value){

		array_push($emptyArray,$value->Client_id);

	}

	return $emptyArray;

}



function get_helper_cluster_processes($user_id){

	$data = ProcessMapping::where('client_id',$user_id)->get();

	$emptyArray = []; 

	foreach($data as $value){

		array_push($emptyArray,$value->process_id);

	}

	return $emptyArray;

}



function get_helper_cluster_partners($user_id){

	$data = PartnerMapping::where('client_id',$user_id)->get();

	$emptyArray = []; 

	foreach($data as $value){

		array_push($emptyArray,$value->partner_id);

	}

	return $emptyArray;

}



function get_helper_cluster_locations($user_id){
	$data = LocationMapping::where('client_id',$user_id)->get();

	$emptyArray = []; 

	foreach($data as $value){

		array_push($emptyArray,$value->location_id);

	}

	return $emptyArray;

}



function get_helper_cluster_lobs($user_id){

	$data = LobMapping::where('user_id',$user_id)->get();

	$emptyArray = []; 

	foreach($data as $value){

		array_push($emptyArray,$value->lob);

	}

	return $emptyArray;


}

function get_qtl_qa_list($qtl){
	$qa_list = User::where('reporting_user_id',$qtl)->pluck('id');
	return $qa_list;
}

function time_difference($future,$current){
	/* $date1 = date('Y-m-d H:i:s', strtotime($future));
	$date2 = date('Y-m-d H:i:s', strtotime($current)); */
	$date1 = Carbon::createFromFormat('Y-m-d H:i:s',$future);
	$date2 = Carbon::createFromFormat('Y-m-d H:i:s',$current);

	return $date1->diffInHours($date2);
	
}

function compair_dates($future,$current){
	$date1 = Carbon::createFromFormat('Y-m-d H:i:s',$future);
	$date2 = Carbon::createFromFormat('Y-m-d H:i:s',$current);
	if($date1->gt($date2)){
		return 0;
	} else {
		return 1;
	}
}

