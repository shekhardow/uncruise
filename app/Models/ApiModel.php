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
            $data = DB::table('ships')->select('ship_id', 'ship_name', 'thumbnail_image')->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getShipDetails($id)
    {
        try {
            $data = DB::table('ships')
                ->select('ship_id', 'ship_name', 'detailed_description')
                ->where('ship_id', $id)
                ->first();
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

    public function getDestinationDetails($id)
    {
        try {
            $data = DB::table('destinations')
                ->select('destination_id', 'name', 'location', 'description')
                ->where('destination_id', $id)
                ->first();
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

    public function getAllPost($user_id = null)
    {
        try {
            return DB::table('posts')
                ->select(
                    'posts.post_id',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' at ', users.city) AS title"),
                    'posts.description',
                    'post_likes.likes'
                )
                ->leftJoin('users', 'users.user_id', '=', 'posts.user_id')
                ->leftJoin('post_likes', 'post_likes.user_id', '=', 'posts.user_id')
                ->where('posts.user_id', '=', $user_id)
                ->get();
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

    public function getUserReviews($user_id, $type = null)
    {
        try {
            $data = DB::table('review');
            switch ($type) {
                case 'ships':
                    $data->select('ship_id', 'review_id', 'review', 'ratings', 'review_type');
                    break;
                case 'destinations':
                    $data->select('destination_id', 'review_id', 'review', 'ratings', 'review_type');
                    break;
                case 'activities':
                    $data->select('review_id', 'activity_id', 'review', 'ratings', 'review_type');
                    break;
                default:
                    $data->select('destination_id', 'ship_id', 'review_id', 'activity_id', 'review', 'ratings', 'review_type');
            }
            $data->where('user_id', $user_id);
            if ($type !== null) {
                $data->where('review_type', $type);
            }
            return $data->get();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
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

    public function getAdventureReview($userId, $uniqueId, $keyName)
    {
        try {
            $query = DB::table('review')
                ->where('user_id', $userId);

            if ($keyName === 'destinations') {
                $query->where('destination_id', $uniqueId);
            } elseif ($keyName === 'ships') {
                $query->where('ship_id', $uniqueId);
            } elseif ($keyName === 'activities') {
                $query->where('activity_id', $uniqueId);
            }

            $data = $query->first();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateAdventureReview($reviewId, $data)
    {
        try {
            DB::table('review')
                ->where('review_id', $reviewId)
                ->update($data);
            return true;
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
            $data = DB::table('review')->select('review.*', 'review_images.image_url')
                ->leftJoin('review_images', 'review_images.review_id', '=', 'review.review_id')
                ->where('review.review_id', $review_id)
                ->get();
            return $data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAdventureData($id = null, $type = null)
    {

        if ($type == 'Activities') {
            return DB::table('activities')
                ->select('activity_name', 'description', 'thumbnail_image')
                ->where('destination_id', $id)
                ->get();
        } elseif ($type == 'Ships') {
            return DB::table('destinations_amenities')
                ->select('amenitie_title', 'amenitie_descriptions')
                ->where('destination_id', $id)
                ->get();
        } elseif ($type == 'Destinations') {

            $data = DB::table('destinations')
                ->select(
                    'destinations.name',
                    'destinations.location',
                    'destinations.description',
                    'destinations.thumbnail_image',
                    'review_likes.likes'
                )
                ->leftJoin('review_likes', 'review_likes.destination_id', '=', 'destinations.destination_id');
            if (!empty($id)) {
                $data->where('destinations.destination_id', $id);
            }
            return $data->paginate();
        } else {
            $activities = DB::table('activities')->select('activity_name')->get();
            $adventures = DB::table('adventures')
                ->select(
                    'adventures.journey',
                    'adventures.description',
                    'adventures.thumbnail_image',
                    'review_likes.likes'
                )
                ->leftJoin('review_likes', 'review_likes.adventure_id', '=', 'adventures.adventure_id');
            if (!empty($id)) {
                $adventures->where('adventures.adventure_id', $id);
            }
            $paginatedDestinations = $adventures->paginate();

            return [
                'activities' => $activities,
                'adventures' => $paginatedDestinations
            ];
        }
    }
}
