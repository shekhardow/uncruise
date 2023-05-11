<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReviewModel extends Model
{
    use HasFactory;

    public function getAllReviews(){
        return DB::table('reviews')->select('*')->where('status','!=','Deleted')->orderByDesc('review_id')->get();
    }

    public function getReviewDetails($id){
        return DB::table('reviews')->select('reviews.*','destinations.name as destination_name','cruises.cruise_name','adventures.adventure_name')
        ->leftJoin('destinations', 'destinations.destination_id', '=', 'reviews.destination_id')
        ->leftJoin('cruises', 'cruises.cruise_id', '=', 'reviews.cruise_id')
        ->leftJoin('adventures', 'adventures.adventure_id', '=', 'reviews.adventures')
        ->where('reviews.review_id', $id)->get()->first();
    }

    public function getAllTestimonials(){
        return DB::table('reviews')->select('*')->where('mark_as_testimonial', '=', 'Testimonial')
        ->where('status', '!=', 'Deleted')->orderByDesc('review_id')->get();
    }

}
