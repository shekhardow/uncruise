<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\ActivityModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    // DB Instance
    private $activity_model;

    public function __construct(){
        $this->activity_model = new ActivityModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ---------------------- Adventures ---------------------------
    public function activities() {
        $data['title'] = "Activities";
        $data['activities'] = $this->activity_model->getAllActivities();
        // dd($data['activities']);
        return $this->loadview('activities/activities', $data);
    }

    public function activityForm($activity_id=null) {
        $activity_id = decryptionID($activity_id);
        $data['destinations'] = $this->activity_model->getAllDestinationsName();
        if(empty($activity_id)){
            $data['title'] = "Add Activity";
        }else{
            $data['title'] = "Edit Activity";
            $data['adventure_detail'] = $this->activity_model->getActivityDetail($activity_id);
            $data['adventure_images'] = $this->activity_model->getActivityImages($activity_id);
        }
        return $this->loadview('activities/activity-form', $data);
    }

    public function addActivity(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'activity_name' => 'required',
            // 'destination'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $thumbnail_image = $request->hasfile('thumbnail_image');
        if(empty($thumbnail_image)){
            return response()->json(['result' => -1, 'msg' => 'Please add Thumbnail Image']);
            return false;
        }
        if(!empty($thumbnail_image)){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }
        $result = $this->activity_model->addActivity($requestdata, $thumbnail_image);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['activity_id'] = $result;
                    $data['image_url'] = $value;
                    $this->activity_model->insertAdventureImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/activities'), 'msg' => 'Activity added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateActivity(Request $request, $activity_id){
        $requestdata = $request->all();
        $activity_id = decryptionID($activity_id);
        $validator = Validator::make($requestdata, $rules = [
            'activity_name' => 'required',
            // 'destination'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->activity_model->getActivityDetail($activity_id);
        $other_images = $this->activity_model->getActivityImages($activity_id);
        if(empty($destination_detail->thumbnail_image)){
            return response()->json(['result' => -1, 'msg' => 'Please add Thumbnail Image']);
            return false;
        }elseif($request->hasfile('thumbnail_image')){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }else{
            $thumbnail_image = $destination_detail->thumbnail_image;
        }
        $result = $this->activity_model->updateActivity($requestdata, $thumbnail_image, $activity_id);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['activity_id'] = $activity_id;
                    $data['image_url'] = $value;
                    $this->activity_model->insertAdventureImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/activities'), 'msg' => 'Activity updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteActivity($id){
        $id = decryptionID($id);
        DB::table('activity_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

}
