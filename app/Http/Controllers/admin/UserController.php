<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    // DB Instance
    private $user_model;

    public function __construct(){
        $this->user_model = new UserModel();
    }

    private function loadview($view, $data = NULL){
        $admin_detail = admin_detail();
        if (empty($admin_detail)) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ------------------------- Users ------------------------------
    public function users() {
        $data['title'] ='Users';
        $data['basic_datatable']='1';
        $data['users'] = $this->user_model->getAllUsers();
        return $this->loadview('users/user', $data);
    }
    
    public function userDetails($user_id) {
        $data['title'] ='User Details';
        $user_id = decryptionID($user_id);
        $data['user_details'] = $this->user_model->getUserByUserId($user_id);
        return $this->loadview('users/user_details', $data);
    }

}
