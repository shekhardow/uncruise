<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\ReviewModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // DB Instance
    private $review_model;

    public function __construct(){
        $this->review_model = new ReviewModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ---------------------- Reviews ---------------------------
    public function reviews() {
        $data['title'] = "Reviews";
        $data['reviews'] = $this->review_model->getAllReviews();
        if(($data['reviews']->isNotEmpty())){
            foreach($data['reviews'] as $row){
                $row->review = @select('review_details',['review'],[['journey_review_id','=',$row->journey_review_id]])->first()->review;
                $row->review_type = @select('review_details',['review_type'],[['journey_review_id','=',$row->journey_review_id]])->first()->review_type;
                $row->first_name = @select('users',['first_name'],[['user_id','=',$row->user_id]])->first()->first_name;
                $row->last_name = @select('users',['last_name'],[['user_id','=',$row->user_id]])->first()->last_name;
                $row->journey = @select('journeys',['journey'],[['journey_id','=',$row->journey_id]])->first()->journey;
                $row->journey_date = @select('journeys',['journey_date'],[['journey_id','=',$row->journey_id]])->first()->journey_date;
                $row->booking_id = @select('review_document_verification',['booking_id'],[['journey_id','=',$row->journey_id]])->first()->booking_id;
                $row->personal_id = @select('review_document_verification',['personal_id'],[['journey_id','=',$row->journey_id]])->first()->personal_id;
            }
        }
        // dd($data['reviews']);
        return $this->loadview('reviews/reviews', $data);
    }

    public function reviewDetails($review_id = null) {
        $data['title'] = "Review Details";
        $review_id = decryptionID($review_id);
        $data['review_details'] = $this->review_model->getReviewDetails($review_id);
        // dd($data['review_details']);
        return $this->loadview('reviews/review-details', $data);
    }

    // -------------------- Testimonials -------------------------
    public function testimonials() {
        $data['title'] = "Testimonials";
        $data['testimonials'] = $this->review_model->getAllReviews();
        if(($data['testimonials']->isNotEmpty())){
            foreach($data['testimonials'] as $row){
                $row->review = @select('review_details',['review'],[['journey_review_id','=',$row->journey_review_id]])->first()->review;
                $row->review_type = @select('review_details',['review_type'],[['journey_review_id','=',$row->journey_review_id]])->first()->review_type;
            }
        }
        // dd($data['testimonials']);
        return $this->loadview('reviews/testimonials', $data);
    }

    public function testimonialDetails($review_id = null) {
        $data['title'] = "Testimonial Details";
        $review_id = decryptionID($review_id);
        $data['review_details'] = $this->review_model->getReviewDetails($review_id);
        // dd($data['review_details']);
        return $this->loadview('reviews/testimonial-details', $data);
    }

}
