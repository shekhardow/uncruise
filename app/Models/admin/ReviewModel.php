<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReviewModel extends Model
{
    use HasFactory;

    public function getAllReviews(){
        return DB::table('journey_reviews')->select('*')->where('status','!=','Deleted')->orderByDesc('journey_review_id')->get();
    }

    public function getReviewDetails($id){
        return DB::table('journey_reviews')->select('journey_reviews.*','review_details.*')
        ->leftJoin('review_details', 'review_details.journey_review_id', '=', 'journey_reviews.journey_review_id')
        ->where('journey_reviews.journey_review_id', $id)->get()->first();
    }

    public function getAllTestimonials(){
        return DB::table('journey_reviews')->select('*')->where('mark_as_testimonial', '=', 'Yes')->where('status', '!=', 'Deleted')->orderByDesc('journey_review_id')->get();
    }

}
