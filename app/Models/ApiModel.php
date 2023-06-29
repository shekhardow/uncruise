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
        try {
            $data = DB::table('users')
                ->select('users.user_id', DB::raw("substr(users.first_name, 1, 1) as firstletter"), 'users.first_name', 'users.email', 'users.country_code', 'users.is_verified', 'users.phone', 'users.source', 'users.otp', 'users.status', 'users_authentication.user_token', 'users_authentication.firebase_token')
                ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
                ->where('users_authentication.user_token', $token)
                ->first();

            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getUserByPhone($phone)
    {
        try {
            $data = DB::table('users')
                ->select('users.*', 'users_authentication.user_token', 'users_authentication.firebase_token')
                ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
                ->where('users.phone', $phone)
                ->where('users.source', 'self')
                ->first();

            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function doRegister($data, $device_type = null, $device_id = null)
    {
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

    public function getUserByID($user_id)
    {
        try {
            $data = DB::table('users')
                ->select('users.*', 'users_authentication.user_token', 'users_authentication.firebase_token', 'countries.name as country_name', 'cities.city_name')
                ->leftJoin('countries', 'countries.id', '=', 'users.country')
                ->leftJoin('cities', 'cities.city_id', '=', 'users.city')
                ->join('users_authentication', 'users.user_id', '=', 'users_authentication.user_id')
                ->where('users.user_id', $user_id)
                ->first();

            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateOTP($user_id, $otp)
    {
        try {
            DB::beginTransaction();
            DB::table('users')->where('user_id', $user_id)->update(['otp' => $otp]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return false;
        }
    }

    public function updateToken($user_id, $token)
    {
        try {
            DB::beginTransaction();
            DB::table('users_authentication')->where('user_id', $user_id)->update(['user_token' => $token]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return false;
        }
    }

    public function updateProfile($user_id, $data)
    {
        try {
            DB::beginTransaction();
            DB::table('users')->where('user_id', $user_id)->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return false;
        }
    }

    public function getAllCountries()
    {
        try {
            $data = DB::table('countries')->select('*')->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getProfile($user_id)
    {
        try {
            $data = DB::table('users')
                ->select('first_name', 'last_name', 'profile_image', 'email', 'country_code', 'phone', 'gender', 'age', 'country')
                ->where('user_id', $user_id)
                ->first();

            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAllShips()
    {
        try {
            $data = DB::table('ships')->select('*')->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAllDestinations()
    {
        try {
            $data = DB::table('destinations')->select('*')->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAllAdventures()
    {
        try {
            $data = DB::table('adventures')->select('*')->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function searchShipByKeyword($keyword = null)
    {
        $data = DB::table('ships')
            ->select('ships.ship_name', 'ships.detailed_description', 'ships.thumbnail_image', 'review_likes.likes')
            ->leftJoin('review_likes', 'review_likes.ship_id', '=', 'ships.ship_id');
        if (!empty($keyword)) {
            $data->where('ships.ship_name', 'like', '%' . $keyword . '%')
                ->orWhere('ships.detailed_description', 'like', '%' . $keyword . '%');
        }
        return $data->get();
    }

    public function getAdventureDetailsInfo($id, $type)
    {
        return DB::table('adventure_details')->where('adventure_id', $id)->where('journey_type', $type)->get();
    }

    public function rateAdventure($data)
    {
        try {
            DB::table('review')->insert($data);
            $lastInsertId = DB::getPdo()->lastInsertId();
            return $lastInsertId;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getUserAdventureRating($user_id, $type)
    {
        try {
            $data = DB::table('review')->select('*')
                ->where('user_id', $user_id)
                ->where('review_type', $type)
                ->first();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateAdventureRating($review_id, $data, $type)
    {
        try {
            DB::table('review')
                ->where('review_id', $review_id)
                ->where('review_type', $type)
                ->update($data);
            $lastId = DB::table('review')
                ->latest('review_id')
                ->value('review_id');
            return $lastId;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getReviewDetails($review_id)
    {
        try {
            $data = DB::table('review')->select('review.*','review_images.image_url')
                ->leftJoin('review_images', 'review_images.review_id', '=', 'review.review_id')
                ->where('review.review_id', $review_id)
                ->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
