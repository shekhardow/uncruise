<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityModel extends Model
{
    use HasFactory;

    public function getAllActivities(){
        return DB::table('activities')->select('activities.*')
        // ->leftJoin('destinations', 'destinations.destination_id', '=', 'activities.destination_id')
        ->where('activities.status','!=','Deleted')->orderByDesc('activities.activity_id')->get();
    }

    public function getAllDestinationsName(){
        return DB::table('destinations')->select('name', 'destination_id')->where('status','!=','Deleted')->get();
    }

    public function getActivityDetail($id){
        return DB::table('activities')->where('activity_id', $id)->get()->first();
    }

    public function getActivityImages($id){
        return DB::table('activity_images')->where('activity_id', $id)->get();
    }

    public function addActivity($requestdata, $thumbnail_image) {
        $data = array(
            'activity_name'  => $requestdata['activity_name'],
            // 'destination_id'    => $requestdata['destination'],
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('activities')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateActivity($requestdata, $thumbnail_image, $id) {
        $data = array(
            'activity_name'  => $requestdata['activity_name'],
            // 'destination_id'    => $requestdata['destination'],
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('activities')->where('activity_id', $id)->update($data);
        return true;
    }

    public function insertAdventureImages($data){
        return DB::table('activity_images')->insert($data);
    }

}
