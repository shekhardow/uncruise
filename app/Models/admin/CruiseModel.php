<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CruiseModel extends Model
{
    use HasFactory;

    public function getAllCruises(){
        return DB::table('cruises')->select('*')->where('status','!=','Deleted')->orderByDesc('cruise_id')->get();
    }

    public function getCruiseTypes(){
        return DB::table('cruise_types')->select('*')->where('status','!=','Deleted')->get();
    }

    public function getCruiseDetail($id){
        return DB::table('cruises')->where('cruise_id', $id)->get()->first();
    }

    public function getCruiseImages($id){
        return DB::table('cruise_images')->where('cruise_id', $id)->get();
    }

    public function addCruise($requestdata, $thumbnail_image) {
        $data = array(
            'cruise_name'  => $requestdata['cruise_name'],
            'cruise_type'    => $requestdata['cruise_type'],
            'brief_description'    => $requestdata['brief_description'],
            'detailed_description'    => $requestdata['detailed_description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('cruises')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateCruise($requestdata, $thumbnail_image, $id) {
        $data = array(
            'cruise_name'  => $requestdata['cruise_name'],
            'cruise_type'    => $requestdata['cruise_type'],
            'brief_description'    => $requestdata['brief_description'],
            'detailed_description'    => $requestdata['detailed_description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('cruises')->where('cruise_id', $id)->update($data);
        return true;
    }

    public function insertCruiseImages($data){
        return DB::table('cruise_images')->insert($data);
    }

}
