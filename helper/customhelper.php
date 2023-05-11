<?php

/**
 * Here You Can Write Your Custom Helper
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

    function admin_detail(){
        return DB::table('admin')->where('id', session()->get('admin_id'))->first();
    }

    function generateOtp(){
        // return rand(1111, 9999);
        return 1234;
    }

    // For generating random token
    function genrateToken(){
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);
        return $token;
    }

    function footerContent(){
        return "Copyright © ".date('Y')." UnCruise Adventures, All Rights Reserved";
    }

    // For Id Encryption
    function encryptionID($id){
        $result = substr(uniqid(), 0, 10).$id.substr(uniqid(), 0, 10);
        return $result;
    }

   // For Id Decryption
    function decryptionID($result_id){
        $id = substr($result_id, 10);
        $result_id = substr($id, 0, -10);
        return $result_id;
    }

    // Common function to insert the data into database
    function insert($table,$data=[]){
        DB::table($table)->insert($data);
        return DB::getPdo()->lastInsertId();
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
            $path = \Storage::disk('s3')->put('images', $request->file($file_name));
            $path = \Storage::disk('s3')->url($path);
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

    function singleCloudinaryUpload($request, $file_name){
        if ($request->hasfile($file_name)) {
            $fileName = $request->file($file_name);
            $uploadedFileUrl = Cloudinary::upload($fileName->getRealPath())->getSecurePath();
            return $uploadedFileUrl;
        }else{
            return false;
        }
    }

    function multipleCloudinaryUploads($request, $file_name){
        if ($request->hasFile($file_name)) {
            $uploadedFileUrl = []; // initialize the variable here
            foreach ($request->file($file_name) as $file){
                $uploadedFileUrl[] = Cloudinary::upload($file->getRealPath(), array("quality" => "auto"))->getSecurePath();
            }
            return $uploadedFileUrl;
        } else {
            return false;
        }
    }

    /**
     * Generates a slug from a given string
     *
     * @param string $string The input string to generate a slug from
     * @param string $separator (optional) The character to use as a separator between words (defaults to '-')
     * @param int $maxLength (optional) The maximum length of the slug (defaults to 100 characters)
     * @return string The generated slug
     */
    function generateSlug($string, $separator = '-', $maxLength = 100) {
        $slug = strtolower($string);  // Convert the input string to lowercase
        $slug = preg_replace("/[^a-z0-9]+/", $separator, $slug);  // Replace all non-alphanumeric characters with the specified separator character
        $slug = trim($slug, $separator);  // Remove any leading or trailing separator characters
        $slug = substr($slug, 0, $maxLength);  // Truncate the slug to the specified maximum length
        return $slug;  // Return the generated slug
    }

    /**
     * Limits the number of words in a given string and adds an ellipsis at the end
     *
     * @param string $description The input string to limit
     * @param int $max_words The maximum number of words to include in the limited string
     * @param string $ellipsis (optional) The ellipsis to append to the end of the limited string (defaults to '...')
     * @param string $separator (optional) The separator used to split the input string into words (defaults to ' ')
     * @return string The limited string with the ellipsis appended to the end
     */
    function limitWords($description, $max_words, $ellipsis = '...', $separator = ' ') {
        $description = strip_tags($description);  // Remove any HTML tags from the description
        $words = explode($separator, $description);  // Split the description into an array of words using the specified separator
        $limited_words = array_slice($words, 0, $max_words);  // Slice the array to the desired number of words
        $limited_description = implode($separator, $limited_words) . $ellipsis;  // Join the limited words back together with the specified ellipsis at the end
        return $limited_description;  // Return the limited string with the ellipsis appended to the end
    }

?>
