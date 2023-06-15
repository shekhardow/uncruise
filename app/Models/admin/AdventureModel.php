<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdventureModel extends Model
{
    use HasFactory;

    public function getAllAdventures(){
        return DB::table('adventures')->select('adventures.*')
        // ->leftJoin('destinations', 'destinations.destination_id', '=', 'adventures.destination_id')
        ->where('adventures.status','!=','Deleted')->orderByDesc('adventures.adventure_id')->get();
    }

    public function getAllDestinationsName(){
        return DB::table('destinations')->select('name', 'destination_id')->where('status','!=','Deleted')->get();
    }

    public function getAdventureDetail($id){
        return DB::table('adventures')->where('adventure_id', $id)->get()->first();
    }

    public function getAdventureImages($id){
        return DB::table('adventure_images')->where('adventure_id', $id)->get();
    }

    public function addAdventure($requestdata, $thumbnail_image) {
        $data = array(
            'adventure_name'  => $requestdata['adventure_name'],
            // 'destination_id'    => $requestdata['destination'],
            'description'    => $requestdata['description'],
            'thumbnail_image'    => $thumbnail_image,
        );
        DB::table('adventures')->insert($data);
        return DB::getPdo()->lastInsertId();
    }

    public function updateAdventure($requestdata, $thumbnail_image, $id) {
        $data = array(
            'adventure_name'  => $requestdata['adventure_name'],
            // 'destination_id'    => $requestdata['destination'],
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
