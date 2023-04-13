<?php 

// Here You Can Write Your Custom Helper

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;

    function admin_detail(){
        return DB::table('admin')->where('id', session()->get('admin_id'))->first();
    }

    // For Id Encryption 
    function encryptionID($id){
        $res=substr(uniqid(), 0, 10).$id.substr(uniqid(), 0, 10);
        return $res;
    }

   // For Id Decryption 
    function decryptionID($result_id){
        $id = substr($result_id, 10);
        $result_id = substr($id, 0, -10);
        return $result_id;
    }

    // Common function to insert the data into database
    function insert($table,$data=[]){
        $result=DB::table($table)->insert($data);
        return  DB::getPdo()->lastInsertId();
    }

    // Common function to update the data into database
    function update($table,$wherecol,$wherevalue,$data,$wherecondition='='){
        $affected_row = DB::table($table)->where($wherecol, $wherecondition, $wherevalue)->update($data);
        return $affected_row;
    }

    // Common function to Delete From  database
    function delete($table,$wherecol,$wherevalue){
        $affected_row = DB::table($table)->where($wherecol, $wherevalue)->delete();
        return $affected_row;
    }

    // Common function to Change The Status
    function change_status($id,$status,$table,$wherecol,$status_variable,$wherecondition='='){
        $data = array(
            $status_variable    =>  $status,
        );
        $affected_row = DB::table($table)->where($wherecol, $wherecondition, $id)->update($data);
        return $affected_row;
    }

    // Common function to Change The Multiple data 
    function change_status_version2($id,$status,$table,$wherecondition,$status_variable){
        $data = array(
            $status_variable    =>  $status,
        );
        $affected_row = DB::table($table)->where($wherecondition)->update($data);
        return $affected_row;
    }

    function select($table,$col='*',$where=null){
        $data=DB::table($table);
        if(!empty($col)){
            $data->addSelect($col);
        }
        if(!empty($where)){
            $data->where($where);
        }
        return $data->get();
    }

    // Common function to Convert date to readable form
    function convertToHoursMinsSec($end,$full = false) {
        $now = new DateTime;
        $ago = new DateTime($end);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    // Common function to Calculate Days b/w Two Date
    function dayBytwodates($date){
        $date=strtotime($date);       
        $date2=time();
        $datediff=$date2-$date; 
        $days=floor(($datediff)/(60*60*24));
        if($days<0){
            return 0;
        }
        return $days;
    }

    function mintusCalculataion($date1,$date2){
        $timestamp1 = strtotime($date1);
        $timestamp2 = strtotime($date2);
        $datetime1 = new DateTime("@$timestamp1");
        $datetime2 = new DateTime("@$timestamp2");
        $interval = $datetime1->diff($datetime2);
        $total_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        return $total_minutes;
    }
    

    function startWithNumber($str){
        return preg_match("~^(\d+)~", $str, $m)===1;
    }

    //--------------------- Logic for Currency conversion ---------------------------
    function number_format_short( $n, $precision = 1 ) {
    	if ($n < 900) {
    		// 0 - 900
    		$n_format = number_format($n, $precision);
    		$suffix = '';
    	} else if ($n < 900000) {
    		// 0.9k-850k
    		$n_format = number_format($n / 1000, $precision);
    		$suffix = ' k';
    	} else if ($n < 900000000) {
    		// 0.9m-850m
    		$n_format = number_format($n / 1000000, $precision);
    		$suffix = ' m';
    	} else if ($n < 900000000000) {
    		// 0.9b-850b
    		$n_format = number_format($n / 1000000000, $precision);
    		$suffix = ' b';
    	} else {
    		// 0.9t+
    		$n_format = number_format($n / 1000000000000, $precision);
    		$suffix = ' t';
    	}
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
    	if ( $precision > 0 ) {
    		$dotzero = '.' . str_repeat( '0', $precision );
    		$n_format = str_replace( $dotzero, '', $n_format );
    	}
    	return $n_format . $suffix;
    }

    // Financial Year
    function get_finacial_year_range($year, $month) {
        $year = $year;
        $month = $month;
        if($month < 4){
            $year = $year - 1;
        }
        $start_date = date('y',strtotime(($year).'-04-01'));
        $end_date = date('y',strtotime(($year+1).'-03-31'));
        $response = $start_date.'-'.$end_date;
        return $response;
    }

    // Encryption String
    function encryptionString($string){ 
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options   = 0;  
        $encryption_iv = '1234567891011121';
        $encryption_key = "DESIGNOWEB";
        $encryption = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);
        return $encryption ;
    }

    // Decryption String
    function decryptionString($encryption){
        $options   = 0;  
        $ciphering = "AES-128-CTR";
        $decryption_iv = '1234567891011121';
        $decryption_key = "DESIGNOWEB";
        $decryption = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $decryption_iv);
        return $decryption;
    }

    // Last 7 days
    function getLastNDays($days, $format = 'd-m'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
        }
        return array_reverse($dateArray);
    }

    // Change Date Format
    function changeDateFormat($date,$type=''){
        if($type=='short'){
            $format='d-m-Y';
        }else{
            $format='d-m-Y | H:i a';
        }
        return date($format,strtotime($date));
    }

    // Date Validation
    function validateDate($mystring){
        $invaliddate = "1970"; 
        if(strpos($mystring, $invaliddate) !== false){
           return true;
        } else{
           return false;
        }
    }

    function siteCurrency(){
        return "€ ";
    }

    // For Single AWS Upload
    function singleAwsUpload($request, $file_name, $path){
        if ($request->hasfile($file_name)) {
            $imageName = time() . '.' . $request->file($file_name)->extension();
            $path = Storage::disk('s3')->put('images', $request->file($file_name));
            $path = Storage::disk('s3')->url($path);
            if (!empty($path)) {
                return $path;
            } else {
                return FALSE;
            }
        } else {
            return false;
        }
    }

    // For Multiple AWS Upload
    function multipleAwsUploads($request, $file_name, $path){
        if ($request->hasfile($file_name)) {
            foreach ($request->file($file_name) as $file) {
                $imageName = time() . '.' . $file->extension();
                $path = Storage::disk('s3')->put('images', $file);
                $path = Storage::disk('s3')->url($path);
                $data[] = $path;
            }
            if (!empty($data)) {
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    // For Single Upload
    function singleUpload($request, $file_name, $path){
        if ($request->hasfile($file_name)) {
            $file = $request->file($file_name);
            $name = time() . '.' . $file->extension();
            sleep(1);
            $file->move(base_path('public/') . $path, $name);
            return $name;
        } else {
            return false;
        }
    }

    // For Multiple Upload
    function multipleUploads($request, $file_name, $path){
        if ($request->hasfile($file_name)) {
            $data = [];
            foreach ($request->file($file_name) as $file) {
                $name = time() . '.' . $file->extension();
                sleep(1);
                $file->move(base_path('uploads/') . $path, $name);
                $data[] = $name;
            }
            return $data;
        } else {
            return false;
        }
    }

    function calculateAge($birthdate) {
        $today = new DateTime();
        $diff = $today->diff(new DateTime($birthdate));
        return $diff->y;
    }
      
    

?>