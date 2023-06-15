<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\UserModel;
use App\Models\admin\AdminModel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // DB Instance
    private $user_model;
    private $admin_model;

    public function __construct(){
        $this->user_model = new UserModel();
        $this->admin_model = new AdminModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ------------------------- Users ------------------------------
    public function users() {
        $data['title'] ='Users';
        $data['basic_datatable']='1';
        $data['users'] = $this->user_model->getAllUsers();
         if(($data['users']->isNotEmpty())){
            foreach($data['users'] as $row){
                $row->reviewcount=count(select('journey_reviews',['journey_review_id'],[['user_id','=',$row->user_id]]));
            }
        }
        return $this->loadview('users/user', $data);
    }

    public function userDetails($user_id) {
        $data['title'] ='User Details';
        $user_id = decryptionID($user_id);
        $data['user_details'] = $this->user_model->getUserByUserId($user_id);
        return $this->loadview('users/user_details', $data);
    }

    // ------------------------- Send Notification To All ------------------------------
    public function notification(Request $request){
        $data['user_id'] = $request->post('user_id');
        $htmlwrapper = view('admin/users/notification', $data)->render();
        return response()->json(['result' => 1, 'htmlwrapper' => $htmlwrapper]);
    }

    public function sendNotification(Request $request){
        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules = [
            'subject' => 'required',
            'message' => 'required|min:6',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $user_id = $request->post('user_id');
        $message = strip_tags($request->post('message'));
        $subject = strip_tags($request->post('subject'));
        foreach($user_id as $id){
            $this->admin_model->sendNotification($id, $message, $subject);
        }
        return response()->json(['result' => 1, 'msg' => 'Notification sent successfully', 'url' => route('admin/users')]);
    }

}
