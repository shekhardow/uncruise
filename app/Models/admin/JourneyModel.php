<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JourneyModel extends Model
{
    use HasFactory;

    public function getAllJourneys(){
        return DB::table('journeys')->select('*')->where('status','!=','Deleted')->orderByDesc('journey_id')->get();
    }

    public function getJourneyDetails($id){
        return DB::table('journeys')->where('journey_id', $id)->get()->first();
    }

    public function getJourneyImages($id){
        return DB::table('journey_images')->where('journey_id', $id)->get();
    }

    public function addJourney($requestdata, $thumbnail_image) {
        $data = array(
            'journey'  => $requestdata['name'],
            'journey_date'    => date( 'Y-m-d H:i:s', strtotime($requestdata['journey_date'])),
            'duration'    => $requestdata['duration'],
            'cruise_id'    => $requestdata['cruise'],
            'destination_id'    => $requestdata['destination'],
            'adventure_id'    => json_encode($requestdata['adventure']),
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('journeys')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateJourney($requestdata, $thumbnail_image, $id) {
        $data = array(
            'journey'  => $requestdata['name'],
            'journey_date'    => date('Y-m-d H:i:s', strtotime($requestdata['journey_date'])),
            'duration'    => $requestdata['duration'],
            'cruise_id'    => $requestdata['cruise'],
            'destination_id'    => $requestdata['destination'],
            'adventure_id'    => json_encode($requestdata['adventure']),
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('journeys')->where('journey_id', $id)->update($data);
        return true;
    }

    public function insertJourneyImages($data){
        return DB::table('journey_images')->insert($data);
    }

}
