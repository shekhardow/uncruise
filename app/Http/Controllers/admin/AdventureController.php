<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdventureModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdventureController extends Controller
{
    // DB Instance
    private $adventure_model;

    public function __construct(){
        $this->adventure_model = new AdventureModel();
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
        // dd($data['adventures']);
        return $this->loadview('adventures/adventures', $data);
    }

    public function adventureForm($adventure_id=null) {
        $adventure_id = decryptionID($adventure_id);
        $data['destinations'] = $this->adventure_model->getAllDestinationsName();
        if(empty($adventure_id)){
            $data['title'] = "Add Adventure";
        }else{
            $data['title'] = "Edit Adventure";
            $data['adventure_detail'] = $this->adventure_model->getAdventureDetail($adventure_id);
            $data['adventure_images'] = $this->adventure_model->getAdventureImages($adventure_id);
        }
        return $this->loadview('adventures/adventure-form', $data);
    }

    public function addAdventure(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'adventure_name' => 'required',
            'destination'   => 'required',
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
        $result = $this->adventure_model->addAdventure($requestdata, $thumbnail_image);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['adventure_id'] = $result;
                    $data['image_url'] = $value;
                    $this->adventure_model->insertAdventureImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/adventures'), 'msg' => 'Adventures added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateAdventure(Request $request, $adventure_id){
        $requestdata = $request->all();
        $adventure_id = decryptionID($adventure_id);
        $validator = Validator::make($requestdata, $rules = [
            'adventure_name' => 'required',
            'destination'   => 'required',
            'description'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $destination_detail = $this->adventure_model->getDestinationDetail($adventure_id);
        $other_images = $this->adventure_model->getAdventureImages($adventure_id);
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
        $result = $this->adventure_model->updateAdventure($requestdata, $thumbnail_image, $adventure_id);
        if($result){
            $other_images = multipleCloudinaryUploads($request, 'other_images');
            if(!empty($other_images)){
                foreach($other_images as $value){
                    $data['adventure_id'] = $adventure_id;
                    $data['image_url'] = $value;
                    $this->adventure_model->insertAdventureImages($data);
                }
            }
            return response()->json(['result' => 1, 'url' => route('admin/adventures'), 'msg' => 'Adventures updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    public function deleteAdventure($id){
        DB::table('destination_images')->where('id', $id)->delete();
        if(true){
            return response()->json(['result' => 1, 'msg' => 'Image deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
}
