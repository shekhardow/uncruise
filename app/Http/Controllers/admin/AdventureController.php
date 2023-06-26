<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdventureModel;
use App\Models\admin\CruiseModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\ActivityModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdventureController extends Controller
{
    // DB Instance
    private $adventure_model;
    private $cruise_model;
    private $destination_model;
    private $activity_model;

    public function __construct(){
        $this->adventure_model = new AdventureModel();
        $this->cruise_model = new CruiseModel();
        $this->destination_model = new DestinationModel();
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
    public function adventures() {
        $data['title'] = "Adventures";
        $data['adventures'] = $this->adventure_model->getAllAdventures();
        return $this->loadview('adventures/adventures', $data);
    }

    public function adventureForm($adventure_id=null) {
        $adventure_id = decryptionID($adventure_id);
        $data['cruise_details'] = $this->cruise_model->getAllCruises();
        $data['destination_details'] = $this->destination_model->getAllDestinations();
        $data['activity_details'] = $this->activity_model->getAllActivities();
        $data['seleted_destination'] = @select('adventure_details',['journey_value'],[['adventure_id','=',$adventure_id],['journey_type','=','destination']])->map(function($item){return $item->journey_value;})->toArray();
        $data['seleted_adventures'] = @select('adventure_details',['journey_value'],[['adventure_id','=',$adventure_id],['journey_type','=','adventure']])->map(function($item){return $item->journey_value;})->toArray();
        $data['seleted_crew'] = @select('adventure_details',['journey_value'],[['adventure_id','=',$adventure_id],['journey_type','=','crew']])->map(function($item){return $item->journey_value;})->toArray();

        if(empty($adventure_id)){
            // dd($data['adventure_details']);
            $data['title'] = "Add Adventure";
        }else{
            $data['title'] = "Edit Adventure";
            $data['adventure_details'] = $this->adventure_model->getAdventureDetails($adventure_id);
            $data['adventure_images'] = $this->adventure_model->getAdventureImages($adventure_id);
            // dd($data['adventure_details']);
        }
        return $this->loadview('adventures/adventure-form', $data);
    }

    public function addAdventure(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'journey_date'   => 'required',
            'duration'   => 'required',
            // 'cruise'   => 'required',
            // 'destination'   => 'required',
            // 'adventure'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }

        //-------------------------------------------cruise-------------------------------------------------------
        $cruise = $request->post('cruise');
        $destinations = $request->post('destination');
        $adventures = $request->post('adventure');

        if(empty($cruise)){
            return response()->json(['result' => -1, 'msg' => 'Please add Cruise.']);
            return false;
        }

        if(empty($destinations)){
            return response()->json(['result' => -1, 'msg' => 'Please add Destination.']);
            return false;
        }

        if(empty($adventures)){
            return response()->json(['result' => -1, 'msg' => 'Please add Adventures.']);
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
        $result = $this->adventure_model->addAdventure($requestdata, $thumbnail_image);
        if($result){

            // multiple adventures
            if(!empty($adventures)){
                $temp = [];
                foreach($adventures as $adv){
                    $temp['journey_type'] = 'adventure';
                    $temp['journey_value'] = $adv;
                    $temp['adventure_id'] = $result;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }

            // multiple Destination
            if(!empty($destinations)){
                $temp = [];
                foreach($destinations as $des){
                    $temp['journey_type'] = 'destination';
                    $temp['journey_value'] = $des;
                    $temp['adventure_id'] = $result;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }
            // multiple adventures
            if(!empty($cruise)){
                $temp = [];
                foreach($cruise as $crew){
                    $temp['journey_type'] = 'crew';
                    $temp['journey_value'] = $crew;
                    $temp['adventure_id'] = $result;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }


            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['adventure_id'] = $result;
                    $data['image_url'] = $value;
                    $this->adventure_model->insertAdventureImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/adventures'), 'msg' => 'Journey added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateAdventure(Request $request, $adventure_id){
        $requestdata = $request->all();
        $adventure_id = decryptionID($adventure_id);
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'journey_date'   => 'required',
            'duration'   => 'required',
            // 'cruise'   => 'required',
            // 'destination'   => 'required',
            // 'adventure'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->adventure_model->getAdventureDetails($adventure_id);
        $other_images = $this->adventure_model->getAdventureImages($adventure_id);
        if (!$request->hasfile('other_images') && $other_images->isEmpty()) {
            return response()->json(['result' => -1, 'msg' => 'Please upload at least one other image.']);
        }
        // if((($request->hasfile('other_images') == true) || ($other_images->isEmpty() == true))){
        //     return response()->json(['result' => -1, 'msg' => 'Please upload atleast one other Image.']);
        //     return false;
        // }
        //-------------------------------------------cruise-------------------------------------------------------
        $cruise = $request->post('cruise');
        $destinations = $request->post('destination');
        $adventures = $request->post('adventure');

        if(empty($cruise)){
            return response()->json(['result' => -1, 'msg' => 'Please add Cruise.']);
            return false;
        }

        if(empty($destinations)){
            return response()->json(['result' => -1, 'msg' => 'Please add Destination.']);
            return false;
        }

        if(empty($adventures)){
            return response()->json(['result' => -1, 'msg' => 'Please add Adventures.']);
            return false;
        }

        if(empty($destination_detail->thumbnail_image)){
            return response()->json(['result' => -1, 'msg' => 'Please add Thumbnail Image']);
            return false;
        }elseif($request->hasfile('thumbnail_image')){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }else{
            $thumbnail_image = $destination_detail->thumbnail_image;
        }
        $result = $this->adventure_model->updateAdventure($requestdata, $thumbnail_image, $adventure_id);
        if($result){
            //$this->adventure_model->deleteDestinationImages($adventure_id);
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['adventure_id'] = $adventure_id;
                    $data['image_url'] = $value;
                    $this->adventure_model->insertAdventureImages($data);
                }
            }

            delete('adventure_details','adventure_id',$adventure_id);
            // multiple adventures
            if(!empty($adventures)){
                $temp = [];
                foreach($adventures as $adv){
                    $temp['journey_type'] = 'adventure';
                    $temp['journey_value'] = $adv;
                    $temp['adventure_id'] = $adventure_id;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }

            // multiple Destination
            if(!empty($destinations)){
                $temp = [];
                foreach($destinations as $des){
                    $temp['journey_type'] = 'destination';
                    $temp['journey_value'] = $des;
                    $temp['adventure_id'] = $adventure_id;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }
            // multiple adventures
            if(!empty($cruise)){
                $temp = [];
                foreach($cruise as $crew){
                    $temp['journey_type'] = 'crew';
                    $temp['journey_value'] = $crew;
                    $temp['adventure_id'] = $adventure_id;
                    insert('adventure_details',$temp);
                    $temp=null;
                }
            }

            return response()->json(['result' => 1, 'url' => route('admin/adventures'), 'msg' => 'Journey updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteJourney($id){
        $id = decryptionID($id);
        DB::table('adventure_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
}
