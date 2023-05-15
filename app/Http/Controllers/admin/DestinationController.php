<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdminModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\AdventureModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    // DB Instance
    private $admin_model;
    private $destination_model;
    private $adventure_model;

    public function __construct(){
        $this->admin_model = new AdminModel();
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

    // ---------------------- Destinations ---------------------------
    public function destinations() {
        $data['title'] = "Destinations";
        $data['destinations'] = $this->destination_model->getAllDestinations();
        return $this->loadview('destinations/destinations', $data);
    }

    public function destinationForm($destination_id=null) {
        $destination_id = decryptionID($destination_id);
        $data['adventures'] = $this->adventure_model->getAllAdventures();
        $data['seleted_adventures'] = @select('destinations_adventures',['adventure_id'],[['destination_id','=',$destination_id]])->map(function($item){return $item->adventure_id;})->toArray();
        $data['seleted_amenities'] = @select('destinations_amenities','*',[['destination_id','=',$destination_id]]);
        if(empty($destination_id)){
            $data['title'] = "Add Destination";
        }else{
            $data['title'] = "Edit Destination";
            $data['destination_detail'] = $this->destination_model->getDestinationDetail($destination_id);
            $data['destination_images'] = $this->destination_model->getDestinationImages($destination_id);
        }
        return $this->loadview('destinations/destination-form', $data);
    }

    public function addDestination(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'location'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        
        //adventures
        $adventures = $request->post('adventures');
        if(empty($adventures)){
            return response()->json(['result' => -1, 'msg' =>'Select Adventures from the list']);
            return false;
        }

        //Anemity
        $title    = $request->post('title');
        $subtitle = $request->post('subtitle');
        
        if(empty(@$title[0])){
            return response()->json(['result' => -1, 'msg' => 'Title is required']);
            return false;
        }
        if(empty(@$subtitle[0])){
            return response()->json(['result' => -1, 'msg' => 'Description is required']);
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
        $result = $this->destination_model->addDestination($requestdata, $thumbnail_image);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['destination_id'] = $result;
                    $data['image_url'] = $value;
                    $this->destination_model->insertDestinationImages($data);
                }
            }
            // multiple adventures
            if(!empty($adventures)){
                $adventure = [];
                foreach($adventures as $adv){
                    $adventure['adventure_id'] = $adv;
                    $adventure['destination_id'] = $result;
                    insert('destinations_adventures',$adventure);
                    $adventure=null;
                }
            }
            // anemity
            if(!empty(@$title[0]) && !empty(@$subtitle[0]) ){
                $amenities = [];
                $i=0;
                foreach($title as $tl){
                    $amenities['amenitie_title'] = $tl;
                    $amenities['amenitie_descriptions'] = @$subtitle[$i];
                    $amenities['destination_id'] = $result;
                    insert('destinations_amenities',$amenities);
                    $i++;
                    $amenities=null;
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/destinations'), 'msg' => 'Destination added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateDestination(Request $request, $destination_id){
        $requestdata = $request->all();
        $destination_id = decryptionID($destination_id);
        $validator = Validator::make($requestdata, $rules = [
            'name' => 'required',
            'location'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->destination_model->getDestinationDetail($destination_id);
        $other_images = $this->destination_model->getDestinationImages($destination_id);
        if (!$request->hasfile('other_images') && $other_images->isEmpty()) {
            return response()->json(['result' => -1, 'msg' => 'Please upload at least one other image.']);
        }
        // if((($request->hasfile('other_images') == true) || ($other_images->isEmpty() == true))){
        //     return response()->json(['result' => -1, 'msg' => 'Please upload atleast one other Image.']);
        //     return false;
        // }

        //adventures
        $adventures = $request->post('adventures');
        if(empty($adventures)){
            return response()->json(['result' => -1, 'msg' =>'Select Adventures from the list']);
            return false;
        }

        //Anemity
        $title    = $request->post('title');
        $subtitle = $request->post('subtitle');
        
        if(empty(@$title[0])){
            return response()->json(['result' => -1, 'msg' => 'Title is required']);
            return false;
        }
        if(empty(@$subtitle[0])){
            return response()->json(['result' => -1, 'msg' => 'Description is required']);
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
        $result = $this->destination_model->updateDestination($requestdata, $thumbnail_image, $destination_id);
        if($result){
            //$this->destination_model->deleteDestinationImages($destination_id);
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['destination_id'] = $destination_id;
                    $data['image_url'] = $value;
                    $this->destination_model->insertDestinationImages($data);
                }
            }

            // multiple adventures
            if(!empty($adventures)){
                delete('destinations_adventures','destination_id',$destination_id);
                $adventure = [];
                foreach($adventures as $adv){
                    $adventure['adventure_id'] = $adv;
                    $adventure['destination_id'] = $destination_id;
                    insert('destinations_adventures',$adventure);
                    $adventure=null;
                }
            }
            // anemity
            if(!empty(@$title[0]) && !empty(@$subtitle[0]) ){
                delete('destinations_amenities','destination_id',$destination_id);
                $amenities = [];
                $i=0;
                foreach($title as $tl){
                    $amenities['amenitie_title'] = $tl;
                    $amenities['amenitie_descriptions'] = @$subtitle[$i];
                    $amenities['destination_id'] = $destination_id;
                    insert('destinations_amenities',$amenities);
                    $i++;
                    $amenities=null;
                }
            }

            return response()->json(['result' => 1, 'url' => route('admin/destinations'), 'msg' => 'Destination updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteDestination($id){
        $id = decryptionID($id);
        DB::table('destination_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

}
