<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdminModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\CruiseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CruiseController extends Controller
{
    // DB Instance
    private $admin_model;
    private $cruise_model;
    private $destination_model;

    public function __construct(){
        $this->admin_model = new AdminModel();
        $this->cruise_model = new CruiseModel();
        $this->destination_model = new DestinationModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ---------------------- Destinations ---------------------------
    public function cruise() {
        $data['title'] = "Cruise";
        $data['cruises'] = $this->cruise_model->getAllCruises();
        return $this->loadview('cruise/cruise', $data);
    }

    public function cruiseForm($cruise_id=null) {
        $cruise_id = decryptionID($cruise_id);
        //$data['cruise_types'] = $this->cruise_model->getCruiseTypes();
        $data['destinations'] = $this->destination_model->getAllDestinations();
        $data['seleted_destination'] = @select('cruise_details','*',[['cruise_id','=',$cruise_id],['cruise_value_type','=','destination']])->map(function($item){return $item->cruise_value;})->toArray();;
        $data['seleted_crew'] = @select('cruise_details','*',[['cruise_id','=',$cruise_id],['cruise_value_type','=','crew']])->map(function($item){return $item->cruise_value;})->toArray();;
        $data['seleted_size'] = @select('cruise_details','*',[['cruise_id','=',$cruise_id],['cruise_value_type','=','size']])->map(function($item){return $item->cruise_value;})->toArray();;
        $data['seleted_guest'] = @select('cruise_details','*',[['cruise_id','=',$cruise_id],['cruise_value_type','=','guest']])->map(function($item){return $item->cruise_value;})->toArray();;

        if(empty($cruise_id)){
            $data['title'] = "Add Cruise";
        }else{
            $data['title'] = "Edit Cruise";
            $data['cruise_detail'] = $this->cruise_model->getCruiseDetail($cruise_id);
            $data['cruise_images'] = $this->cruise_model->getCruiseImages($cruise_id);
        }
        return $this->loadview('cruise/cruise-form', $data);
    }


    public function addCruise(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
             'cruise_name' => 'required',
            // // 'cruise_type'   => 'required',
            // // 'brief_description'   => 'required',
            'detailed_description'   => 'required',
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
        // size and year
        $size = $request->post('sizeyear');
        
        if(empty($size)){
            return response()->json(['result' => -1, 'msg' => 'Please add Size and Year Field']);
            return false;
        }
        $guest = $request->post('guest');
        if(empty($guest)){
            return response()->json(['result' => -1, 'msg' => 'Please add Guest  Field']);
            return false;
        }
        $crew = $request->post('crew');
        if(empty($crew)){
            return response()->json(['result' => -1, 'msg' => 'Please add Crew  Field']);
            return false;
        }
        $destinatios = $request->post('destinatios');
        if(empty($destinatios)){
            return response()->json(['result' => -1, 'msg' => 'Please add destinatios  Field']);
            return false;
        }

        $result = $this->cruise_model->addCruise($requestdata, $thumbnail_image);
        if($result){
            if(!empty($size)){
                $sizetemp = [];
                foreach($size as $s){
                    $sizetemp['cruise_value_type'] = 'size';
                    $sizetemp['cruise_value'] = $s;
                    $sizetemp['cruise_id'] = $result;
                    insert('cruise_details',$sizetemp);
                    $sizetemp=[];
                }
            }
            if(!empty($guest)){
                $guesttemp = [];
                foreach($guest as $g){
                    $guesttemp['cruise_value_type'] = 'guest';
                    $guesttemp['cruise_value'] = $g;
                    $guesttemp['cruise_id'] = $result;
                    insert('cruise_details',$guesttemp);
                    $sizetemp=[];
                }
            }
            if(!empty($crew)){
                $crewtemp = [];
                foreach($crew as $c){
                    $crewtemp['cruise_value_type'] = 'crew';
                    $crewtemp['cruise_value'] = $c;
                    $crewtemp['cruise_id'] = $result;
                    insert('cruise_details',$crewtemp);
                    $crewtemp=[];
                }
            }
            if(!empty($destinatios)){
                $destinatiostemp = [];
                foreach($destinatios as $s){
                    $destinatiostemp['cruise_value_type'] = 'destination';
                    $destinatiostemp['cruise_value'] = $s;
                    $destinatiostemp['cruise_id'] = $result;
                    insert('cruise_details',$destinatiostemp);
                    $destinatiostemp=[];
                }
            }

            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['cruise_id'] = $result;
                    $data['image_url'] = $value;
                    $this->cruise_model->insertCruiseImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/cruise'), 'msg' => 'Cruise added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateCruise(Request $request, $cruise_id){
        $requestdata = $request->all();
        $cruise_id = decryptionID($cruise_id);
        $validator = Validator::make($requestdata, $rules = [
            'cruise_name' => 'required',
            // 'cruise_type'   => 'required',
            // 'brief_description'   => 'required',
            'detailed_description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->cruise_model->getCruiseDetail($cruise_id);
        $other_images = $this->cruise_model->getCruiseImages($cruise_id);

        if (!$request->hasfile('other_images') && $other_images->isEmpty()) {
            return response()->json(['result' => -1, 'msg' => 'Please upload at least one other image.']);
        }
        if(empty($destination_detail->thumbnail_image)){
            return response()->json(['result' => -1, 'msg' => 'Please add Thumbnail Image']);
            return false;
        }elseif($request->hasfile('thumbnail_image')){
            $thumbnail_image = singleCloudinaryUpload($request, 'thumbnail_image');
        }else{
            $thumbnail_image = $destination_detail->thumbnail_image;
        }
         // size and year
         $size = $request->post('sizeyear');
        
         if(empty($size)){
             return response()->json(['result' => -1, 'msg' => 'Please add Size and Year Field']);
             return false;
         }
         $guest = $request->post('guest');
         if(empty($guest)){
             return response()->json(['result' => -1, 'msg' => 'Please add Guest  Field']);
             return false;
         }
         $crew = $request->post('crew');
         if(empty($crew)){
             return response()->json(['result' => -1, 'msg' => 'Please add Crew  Field']);
             return false;
         }
         $destinatios = $request->post('destinatios');
         if(empty($destinatios)){
             return response()->json(['result' => -1, 'msg' => 'Please add destinatios  Field']);
             return false;
         }
        $result = $this->cruise_model->updateCruise($requestdata, $thumbnail_image, $cruise_id);
        if($result){
            delete('cruise_details','cruise_id',$cruise_id);
            if(!empty($size)){
                $sizetemp = [];
                foreach($size as $s){
                    $sizetemp['cruise_value_type'] = 'size';
                    $sizetemp['cruise_value'] = $s;
                    $sizetemp['cruise_id'] = $result;
                    insert('cruise_details',$sizetemp);
                    $sizetemp=[];
                }
            }
            if(!empty($guest)){
                $guesttemp = [];
                foreach($guest as $g){
                    $guesttemp['cruise_value_type'] = 'guest';
                    $guesttemp['cruise_value'] = $g;
                    $guesttemp['cruise_id'] = $result;
                    insert('cruise_details',$guesttemp);
                    $sizetemp=[];
                }
            }
            if(!empty($crew)){
                $crewtemp = [];
                foreach($crew as $c){
                    $crewtemp['cruise_value_type'] = 'crew';
                    $crewtemp['cruise_value'] = $c;
                    $crewtemp['cruise_id'] = $result;
                    insert('cruise_details',$crewtemp);
                    $crewtemp=[];
                }
            }
            if(!empty($destinatios)){
                $destinatiostemp = [];
                foreach($destinatios as $s){
                    $destinatiostemp['cruise_value_type'] = 'destination';
                    $destinatiostemp['cruise_value'] = $s;
                    $destinatiostemp['cruise_id'] = $result;
                    insert('cruise_details',$destinatiostemp);
                    $destinatiostemp=[];
                }
            }

            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['cruise_id'] = $cruise_id;
                    $data['image_url'] = $value;
                    $this->cruise_model->insertCruiseImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/cruise'), 'msg' => 'Cruise updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteCruise($id){
        $id = decryptionID($id);
        DB::table('cruise_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
}
