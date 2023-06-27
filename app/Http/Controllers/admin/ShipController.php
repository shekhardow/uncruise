<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdminModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\ShipModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ShipController extends Controller
{
    // DB Instance
    private $admin_model;
    private $cruise_model;
    private $destination_model;

    public function __construct(){
        $this->admin_model = new AdminModel();
        $this->cruise_model = new ShipModel();
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
    public function ships() {
        $data['title'] = "Ships";
        $data['ships'] = $this->cruise_model->getAllCruises();
        return $this->loadview('ships/ships', $data);
    }

    public function shipForm($ship_id=null) {
        $ship_id = decryptionID($ship_id);
        //$data['ship_types'] = $this->cruise_model->getCruiseTypes();
        $data['destinations'] = $this->destination_model->getAllDestinations();
        $data['seleted_destination'] = @select('ship_details','*',[['ship_id','=',$ship_id],['ship_value_type','=','destination']])->map(function($item){return $item->ship_value;})->toArray();;
        $data['seleted_crew'] = @select('ship_details','*',[['ship_id','=',$ship_id],['ship_value_type','=','crew']])->map(function($item){return $item->ship_value;})->toArray();;
        $data['seleted_size'] = @select('ship_details','*',[['ship_id','=',$ship_id],['ship_value_type','=','size']])->map(function($item){return $item->ship_value;})->toArray();;
        $data['seleted_guest'] = @select('ship_details','*',[['ship_id','=',$ship_id],['ship_value_type','=','guest']])->map(function($item){return $item->ship_value;})->toArray();;

        if(empty($ship_id)){
            $data['title'] = "Add Ship";
        }else{
            $data['title'] = "Edit Ship";
            $data['ship_detail'] = $this->cruise_model->getCruiseDetail($ship_id);
            $data['ship_images'] = $this->cruise_model->getCruiseImages($ship_id);
        }
        return $this->loadview('ships/ship-form', $data);
    }


    public function addShip(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'ship_name' => 'required',
            // // 'ship_type'   => 'required',
            // // 'brief_description'   => 'required',
            'detailed_description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }

        // size and year
        $size = $request->post('sizeyear');
        if(empty($size)){
            return response()->json(['result' => -1, 'msg' => 'Please add Size and Year Field']);
            return false;
        }
        $guest = $request->post('guest');
        if(empty($guest)){
            return response()->json(['result' => -1, 'msg' => 'Please add Guest Field']);
            return false;
        }
        $crew = $request->post('crew');
        if(empty($crew)){
            return response()->json(['result' => -1, 'msg' => 'Please add Crew Field']);
            return false;
        }
        $destinatios = $request->post('destinatios');
        if(empty($destinatios)){
            return response()->json(['result' => -1, 'msg' => 'Please add destinatios Field']);
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
        $result = $this->cruise_model->addShip($requestdata, $thumbnail_image);
        if($result){
            if(!empty($size)){
                $sizetemp = [];
                foreach($size as $s){
                    $sizetemp['ship_value_type'] = 'size';
                    $sizetemp['ship_value'] = $s;
                    $sizetemp['ship_id'] = $result;
                    insert('ship_details',$sizetemp);
                    $sizetemp=[];
                }
            }
            if(!empty($guest)){
                $guesttemp = [];
                foreach($guest as $g){
                    $guesttemp['ship_value_type'] = 'guest';
                    $guesttemp['ship_value'] = $g;
                    $guesttemp['ship_id'] = $result;
                    insert('ship_details',$guesttemp);
                    $sizetemp=[];
                }
            }
            if(!empty($crew)){
                $crewtemp = [];
                foreach($crew as $c){
                    $crewtemp['ship_value_type'] = 'crew';
                    $crewtemp['ship_value'] = $c;
                    $crewtemp['ship_id'] = $result;
                    insert('ship_details',$crewtemp);
                    $crewtemp=[];
                }
            }
            if(!empty($destinatios)){
                $destinatiostemp = [];
                foreach($destinatios as $s){
                    $destinatiostemp['ship_value_type'] = 'destination';
                    $destinatiostemp['ship_value'] = $s;
                    $destinatiostemp['ship_id'] = $result;
                    insert('ship_details',$destinatiostemp);
                    $destinatiostemp=[];
                }
            }

            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['ship_id'] = $result;
                    $data['image_url'] = $value;
                    $this->cruise_model->insertCruiseImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/ships'), 'msg' => 'Ship added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateShip(Request $request, $ship_id){
        $requestdata = $request->all();
        $ship_id = decryptionID($ship_id);
        $validator = Validator::make($requestdata, $rules = [
            'ship_name' => 'required',
            // 'ship_type'   => 'required',
            // 'brief_description'   => 'required',
            'detailed_description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->cruise_model->getCruiseDetail($ship_id);
        $other_images = $this->cruise_model->getCruiseImages($ship_id);

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
        $result = $this->cruise_model->updateShip($requestdata, $thumbnail_image, $ship_id);
        if($result){
            delete('ship_details','ship_id',$ship_id);
            if(!empty($size)){
                $sizetemp = [];
                foreach($size as $s){
                    $sizetemp['ship_value_type'] = 'size';
                    $sizetemp['ship_value'] = $s;
                    $sizetemp['ship_id'] = $result;
                    insert('ship_details',$sizetemp);
                    $sizetemp=[];
                }
            }
            if(!empty($guest)){
                $guesttemp = [];
                foreach($guest as $g){
                    $guesttemp['ship_value_type'] = 'guest';
                    $guesttemp['ship_value'] = $g;
                    $guesttemp['ship_id'] = $result;
                    insert('ship_details',$guesttemp);
                    $sizetemp=[];
                }
            }
            if(!empty($crew)){
                $crewtemp = [];
                foreach($crew as $c){
                    $crewtemp['ship_value_type'] = 'crew';
                    $crewtemp['ship_value'] = $c;
                    $crewtemp['ship_id'] = $result;
                    insert('ship_details',$crewtemp);
                    $crewtemp=[];
                }
            }
            if(!empty($destinatios)){
                $destinatiostemp = [];
                foreach($destinatios as $s){
                    $destinatiostemp['ship_value_type'] = 'destination';
                    $destinatiostemp['ship_value'] = $s;
                    $destinatiostemp['ship_id'] = $result;
                    insert('ship_details',$destinatiostemp);
                    $destinatiostemp=[];
                }
            }

            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['ship_id'] = $ship_id;
                    $data['image_url'] = $value;
                    $this->cruise_model->insertCruiseImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/ships'), 'msg' => 'Ship updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteShip($id){
        $id = decryptionID($id);
        DB::table('ship_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
}
