<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiModel extends Model
{
    use HasFactory;

    public function getUserByIdentifier($identifier, $type){
        return DB::table('users')
            ->select('users.*', 'users_authentication.user_token', 'users_authentication.firebase_token')
            ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
            ->where("users.$type", $identifier)->where('users.source', 'self')->get()->first();
    }

    public function doRegister($data, $device_type=null, $device_id=null){
        DB::beginTransaction();
        try {
            DB::table('users')->insert($data);
            $id = DB::getPdo()->lastInsertId();
            if (!empty($id)) {
                $token = array(
                    'user_token' => genrateToken(),
                    'user_id' => $id,
                    'device_id' => $device_id,
                    'device_type' => $device_type,
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

    public function updateOtp($user_id, $otp){
        DB::table('users')->where('user_id', $user_id)->update(['otp' => $otp]);
        return true;
    }

    public function updateToken($user_id, $token){
        DB::table('users_authentication')->where('user_id', $user_id)->update(['user_token' => $token]);
        return true;
    }

}
