<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\JourneyModel;
use App\Models\admin\CruiseModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\AdventureModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JourneyController extends Controller
{
    // DB Instance
    private $journey_model;
    private $cruise_model;
    private $destination_model;
    private $adventure_model;

    public function __construct(){
        $this->journey_model = new JourneyModel();
        $this->cruise_model = new CruiseModel();
        $this->destination_model = new DestinationModel();
        $this->adventure_model = new AdventureModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ---------------------- Journeys ---------------------------
    public function journeys() {
        $data['title'] = "Journeys";
        $data['journeys'] = $this->journey_model->getAllJourneys();
        return $this->loadview('journeys/journeys', $data);
    }

    public function journeyForm($journey_id=null) {
        $journey_id = decryptionID($journey_id);
        $data['cruise_details'] = $this->cruise_model->getAllCruises();
        $data['destination_details'] = $this->destination_model->getAllDestinations();
        $data['adventure_details'] = $this->adventure_model->getAllAdventures();
        if(empty($journey_id)){
            $data['title'] = "Add Journey";
        }else{
            $data['title'] = "Edit Journey";
            $data['journey_details'] = $this->journey_model->getJourneyDetails($journey_id);
            $data['journey_images'] = $this->journey_model->getJourneyImages($journey_id);
            // dd($data['journey_details']);
        }
        return $this->loadview('journeys/journey-form', $data);
    }

    public function addJourney(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'journey_date'   => 'required',
            'duration'   => 'required',
            'cruise'   => 'required',
            'destination'   => 'required',
            'adventure'   => 'required',
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
        if(empty($request->hasfile('other_images'))){
            return response()->json(['result' => -1, 'msg' => 'Please add atleast one other Image']);
            return false;
        }
        if(!empty($thumbnail_image)){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }
        $result = $this->journey_model->addJourney($requestdata, $thumbnail_image);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['journey_id'] = $result;
                    $data['image_url'] = $value;
                    $this->journey_model->insertJourneyImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/journeys'), 'msg' => 'Journey added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateJourney(Request $request, $journey_id){
        $requestdata = $request->all();
        $journey_id = decryptionID($journey_id);
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'journey_date'   => 'required',
            'duration'   => 'required',
            'cruise'   => 'required',
            'destination'   => 'required',
            'adventure'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->journey_model->getJourneyDetails($journey_id);
        $other_images = $this->journey_model->getJourneyImages($journey_id);
        if (!$request->hasfile('other_images') && $other_images->isEmpty()) {
            return response()->json(['result' => -1, 'msg' => 'Please upload at least one other image.']);
        }
        // if((($request->hasfile('other_images') == true) || ($other_images->isEmpty() == true))){
        //     return response()->json(['result' => -1, 'msg' => 'Please upload atleast one other Image.']);
        //     return false;
        // }
        if(empty($destination_detail->thumbnail_image)){
            return response()->json(['result' => -1, 'msg' => 'Please add Thumbnail Image']);
            return false;
        }elseif($request->hasfile('thumbnail_image')){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }else{
            $thumbnail_image = $destination_detail->thumbnail_image;
        }
        $result = $this->journey_model->updateJourney($requestdata, $thumbnail_image, $journey_id);
        if($result){
            //$this->journey_model->deleteDestinationImages($journey_id);
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['journey_id'] = $journey_id;
                    $data['image_url'] = $value;
                    $this->journey_model->insertJourneyImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/journeys'), 'msg' => 'Journey updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteJourney($id){
        $id = decryptionID($id);
        DB::table('journey_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
}
