<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipModel extends Model
{
    use HasFactory;

    public function getAllCruises(){
        return DB::table('ships')->select('*')->where('status','!=','Deleted')->orderByDesc('ship_id')->get();
    }

    public function getCruiseTypes(){
        return DB::table('ship_types')->select('*')->where('status','!=','Deleted')->get();
    }

    public function getCruiseDetail($id){
        return DB::table('ships')->where('ship_id', $id)->get()->first();
    }

    public function getCruiseImages($id){
        return DB::table('ship_images')->where('ship_id', $id)->get();
    }

    public function addShip($requestdata, $thumbnail_image) {
        $data = array(
            'ship_name'  => $requestdata['ship_name'],
            // 'ship_type'    => $requestdata['ship_type'],
            // 'brief_description'    => $requestdata['brief_description'],
            'detailed_description'    => $requestdata['detailed_description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('ships')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateShip($requestdata, $thumbnail_image, $id) {
        $data = array(
            'ship_name'  => $requestdata['ship_name'],
            // 'ship_type'    => $requestdata['ship_type'],
            // 'brief_description'    => $requestdata['brief_description'],
            'detailed_description'    => $requestdata['detailed_description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('ships')->where('ship_id', $id)->update($data);
        return true;
    }

    public function insertCruiseImages($data){
        return DB::table('ship_images')->insert($data);
    }

}
