<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DestinationModel extends Model
{
    use HasFactory;

    public function getAllDestinations(){
        return DB::table('destinations')->select('*')->where('status','!=','Deleted')->orderByDesc('destination_id')->get();
    }

    public function getDestinationDetail($id){
        return DB::table('destinations')->where('destination_id', $id)->get()->first();
    }

    public function getDestinationImages($id){
        return DB::table('destination_images')->where('destination_id', $id)->get();
    }

    public function addDestination($requestdata, $thumbnail_image) {
        $data = array(
            'name'  => $requestdata['name'],
            'location'    => $requestdata['location'],
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('destinations')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateDestination($requestdata, $thumbnail_image, $id) {
        $data = array(
            'name'  => $requestdata['name'],
            'location'    => $requestdata['location'],
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('destinations')->where('destination_id', $id)->update($data);
        return true;
    }

    public function deleteDestinationImages($id){
        DB::table('destination_images')->where('destination_id', $id)->delete();
    }

    public function insertDestinationImages($data){
        return DB::table('destination_images')->insert($data);
    }

}
