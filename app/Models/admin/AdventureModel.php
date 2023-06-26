<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdventureModel extends Model
{
    use HasFactory;

    public function getAllAdventures(){
        return DB::table('adventures')->select('*')->where('status','!=','Deleted')->orderByDesc('adventure_id')->get();
    }

    public function getAdventureDetails($id){
        return DB::table('adventures')->where('adventure_id', $id)->get()->first();
    }

    public function getAdventureImages($id){
        return DB::table('adventure_images')->where('adventure_id', $id)->get();
    }

    public function addAdventure($requestdata, $thumbnail_image) {
        $data = array(
            'journey'  => $requestdata['name'],
            'journey_date'    => date( 'Y-m-d H:i:s', strtotime($requestdata['journey_date'])),
            'duration'    => $requestdata['duration'],
            // 'cruise_id'    => $requestdata['cruise'],
            // 'destination_id'    => $requestdata['destination'],
            // 'adventure_id'    => json_encode($requestdata['adventure']),
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('adventures')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateAdventure($requestdata, $thumbnail_image, $id) {
        $data = array(
            'journey'  => $requestdata['name'],
            'journey_date'    => date('Y-m-d H:i:s', strtotime($requestdata['journey_date'])),
            'duration'    => $requestdata['duration'],
            // 'cruise_id'    => $requestdata['cruise'],
            // 'destination_id'    => $requestdata['destination'],
            // 'adventure_id'    => json_encode($requestdata['adventure']),
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('adventures')->where('adventure_id', $id)->update($data);
        return true;
    }

    public function insertAdventureImages($data){
        return DB::table('adventure_images')->insert($data);
    }

}
