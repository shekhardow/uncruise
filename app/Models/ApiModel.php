<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiModel extends Model
{
    use HasFactory;
    
    public function getUserByToken($token)
    {
        return DB::table('users')
            ->select('users.user_id', DB::raw("substr(users.first_name, 1, 1) as firstletter"), 'users.first_name', 'users.email', 'users.country_code', 'users.is_verified', 'users.phone', 'users.source', 'users.otp', 'users.status', 'users_authentication.user_token', 'users_authentication.firebase_token')
            ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
            ->where('users_authentication.user_token', $token)->get()->first();
    }

    public function getUserByPhone($phone){
        return DB::table('users')
            ->select('users.*', 'users_authentication.user_token', 'users_authentication.firebase_token')
            ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
            ->where("users.phone", $phone)->where('users.source', 'self')->get()->first();
    }

    public function doRegister($data, $device_type=null, $device_id=null){
        DB::beginTransaction();
        try {
            DB::table('users')->insert($data);
            $id = DB::getPdo()->lastInsertId();
            if (!empty($id)) {
                $token = array(
                    'user_id' => $id,
                    'user_token' => generateToken(),
                    'device_type' => $device_type,
                    'device_id' => $device_id,
                );
                DB::table('users_authentication')->insert($token);
            }
            DB::commit();
            return $id;
        } catch (\Exception $e) {
            DB::rollback();
            $e->getMessage();
            return false;
        }
    }

    public function getUserByID($user_id){
        return DB::table('users')
            ->select('users.*', 'users_authentication.user_token', 'users_authentication.firebase_token', 'countries.name as country_name', 'cities.city_name')
            ->leftjoin('countries', 'countries.id', '=', 'users.country')
            ->leftjoin('cities', 'cities.city_id', '=', 'users.city')
            ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
            ->where('users.user_id', $user_id)->get()->first();
    }

    public function updateOTP($user_id, $otp){
        DB::table('users')->where('user_id', $user_id)->update(['otp' => $otp]);
        return true;
    }

    public function updateToken($user_id, $token){
        DB::table('users_authentication')->where('user_id', $user_id)->update(['user_token' => $token]);
        return true;
    }
    
    public function updateProfile($user_id, $data){
        DB::table('users')->where('user_id', $user_id)->update($data);
        return true;
    }
    
    public function getAllCountries(){
        try {
            DB::beginTransaction();
            $data = DB::table('countries')->select('*')->get();
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return false;
        }
    }
    
    public function getProfile($user_id){
        try {
            DB::beginTransaction();
            $data = DB::table('users')->select('first_name','last_name','profile_image','email','country_code','phone','gender','age','country')
            ->where('user_id', $user_id)->get()->first();
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return false;
        }
    }


}
