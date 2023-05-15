<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    use HasFactory;

    public function getAllUsers(){
        return DB::table('users')->select('*')->orderByDesc('user_id')->get();
    }

    public function getUserByUserId($user_id){
        return DB::table('users')->select('users.*','countries.name as country','cities.city_name as city')
        ->leftJoin('countries', 'countries.id', '=', 'users.country')
        ->leftJoin('cities', 'cities.city_id', '=', 'users.city')
        ->where('users.user_id', $user_id)->get()->first();
    }

}
