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
        $data['testimonials'] = $this->review_model->getAllTestimonials();
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
