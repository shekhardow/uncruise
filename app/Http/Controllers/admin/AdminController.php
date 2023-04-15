<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\AdminModel;
use App\Models\admin\UserModel;
use App\Models\admin\SurveyModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class AdminController extends Controller
{
    // DB Instance
    private $admin_model;
    private $user_model;
    private $survey_model;

    public function __construct(){
        $this->admin_model = new AdminModel();
        $this->user_model = new UserModel();
        $this->survey_model = new SurveyModel();
    }

    private function loadview($view, $data = NULL){
        $admin_detail = admin_detail();
        if (empty($admin_detail)) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    public function send_password_reset_mail($admin_detail){
        $encrypted_id = substr(uniqid(), 0, 10).$admin_detail->id.substr(uniqid(), 0, 10);
        $htmlContent = "<h3>Dear " . $admin_detail->email . ",</h3>";
        $htmlContent .= "<div style='padding-top:8px;'>Please click the following link to reset your password.</div>";
        $htmlContent .= "<a href='" . url('admin/reset-password/' . $encrypted_id) . "'> Click Here!!</a>";
        $from = "admin@uncruise.com";
        $to = $admin_detail->email;
        $subject = "[UnCruise Admin] Forgot Password";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        @mail($to, $subject, $htmlContent, $headers);
        return FALSE;
    }

    // ------------------------- Login ------------------------------
    public function login(){
        $data['title'] ='Login';
        return view('admin/login', $data);
    }

    public function check_login(Request $request){
        $form_data = $request->post();
        $admin_detail = $this->admin_model->getAdmin();
        if($admin_detail->email != $form_data['email'] && $admin_detail->password != hash('sha256',$form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email and Password']);
        }elseif($admin_detail->email != $form_data['email']){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email']);
        }elseif($admin_detail->password != hash('sha256',$form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Password']);
        }else{
            $request->session()->put(['admin_id' => $admin_detail->id]);
            return response()->json(['result' => 1, 'msg' => 'Loading... Please Wait', 'url' => route('admin/dashboard')]);
        }
    }

    // ------------------------- Logout ------------------------------
    public function logout(Request $Request){
        $Request->session()->forget('admin_id');
        return redirect('admin');
    }
    
    // ------------------------- Dashboard ------------------------------
    public function dashboard(Request $Request){
        $data['title'] ='Dashboard';
        $data['total_users'] = count($this->user_model->getAllUsers());
        $data['total_surveys'] = count($this->survey_model->getAllSurveys());
        return $this->loadview('dashboard',$data);
    }

    //------------------------- Profile ------------------------------
    public function profile(){
        $data['title'] = "Profile";
        $data['admin_detail'] = $this->admin_model->getAdminDetail(session()->get('admin_id'));
        return $this->loadview('profile', $data);
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'phone'   => 'required',
        ]);
        $form_data = $request->post();
        $admin_detail = admin_detail();
        $profile_pic = $request->file('profile_pic');
        $favicon = $request->file('favicon');
        $logo = $request->file('logo');
        if(empty($profile_pic)){
            $form_data['profile_pic'] = $admin_detail->profile_pic;
        }else{
            $profile_pic = singleUpload($request, 'profile_pic', 'assets/admin/adminimages/');
            $form_data['profile_pic'] = $profile_pic;
        }
        if(empty($favicon)){
            $form_data['favicon'] = $admin_detail->favicon;
        }else{
            $favicon = singleUpload($request, 'favicon', 'assets/admin/adminimages/');
            $form_data['favicon'] = $favicon;
        }
        if(empty($logo)){
            $form_data['logo'] = $admin_detail->logo;
        }else{
            $logo = singleUpload($request, 'logo', 'assets/admin/adminimages/');
            $form_data['logo'] = $logo;
        }
        $update_data = [
            'name'           => $form_data['name'],
            'email'          => $form_data['email'],
            'contact_no'     => $form_data['phone'],
            'profile_pic'    => $form_data['profile_pic'],
            'favicon'        => $form_data['favicon'],
            'logo'           => $form_data['logo'],
            'address'        => $form_data['address'],
        ];
        $result = DB::table('admin')->where('id', session()->get('admin_id'))->update($update_data);
        if($result){
            return response()->json(['result' => 1, 'msg' => 'Profile updated succesfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    // ------------------------- Change Password ------------------------------
    public function changePassword(Request $Request){
        $form_data = $Request->post();
        $admin_detail = $this->admin_model->getAdminDetail(session()->get('admin_id'));
        $result = DB::table('admin')->where(['password' => hash('sha256',$form_data['old_password'])])->first();
        if (!empty($result)) {
            if($form_data['new_password'] == $form_data['confirm_new_password']){
                $update_data = [
                    'password'  => hash('sha256',$form_data['new_password']),
                ];
                $changed = $result = DB::table('admin')->where('id', 1)->update($update_data);
                if($changed){
                    return response()->json(['result' => 1, 'url' => route('admin/dashboard'), 'msg' => 'Password changed successfully']);
                }else{
                    return response()->json(['result' => -1, 'msg' => 'Password change process failed!']);
                }
            }else{
                return response()->json(['result' => -1, 'msg' => 'New password and Confirm password should be same!']);
            }
        }else{
            return response()->json(['result' => -1, 'msg' => 'Old password did not matched current password!']);
        }
    }

    // ------------------------- Setting Pages (Terms, Privacy, About) ------------------------------
    public function siteSetting($key){
        if($key == 'terms-condition'){
            $type = 'Terms';
            $data['title'] = 'Terms & Condition';
        }elseif($key == 'privacy-policy'){
            $type = 'Privacy';
            $data['title'] = 'Privacy Policy';
        }elseif($key=='about-us'){
            $type = 'About';
            $data['title'] = 'About Us';
        }
        $data['admin_detail']= $this->admin_model->getAdminDetail(session()->get('admin_id'));
        $data['basic_datatable'] = '1';
        $data['type'] = $type;
        $data['site_setting'] = DB::table('settings')->where('type',$type)->first();
        return $this->loadview('setting',$data);
    }

    public function updateSiteSetting(Request $request){
        $form_data = $request->post();
        $update_data = [
            'description' => $form_data['description'],
        ];
        $key = $form_data['type'];
        $result = DB::table('settings')->where('type', $key)->update($update_data);
        if($key == 'terms'){
            $url = route('admin/siteSetting',['key' => 'terms-condition']);
            $title = 'Terms & Condition';
        }elseif($key == 'Privacy'){
            $url = route('admin/siteSetting',['key' => 'privacy-policy']);
            $title = 'Privacy Policy';
        }else{
            $url = route('admin/siteSetting',['key' => 'about-us']);
            $title = 'About Us';
        }
        if($result){
            return response()->json(['result' => 1, 'url' => $url, 'msg' => "$title updated successfully"]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    } 

    // ------------------------- Social ------------------------------
    public function social(){
        $data['title'] ='Social';
        $data['social_link'] = Config::get('constants.social_link');
        $data['social_data'] = $this->admin_model->get_social_link();
        return $this->loadview('social', $data);
    }

    public function update_social_link(Request $request){
        $Requestdata = $request->all();
        $result = $this->admin_model->update_social_link($Requestdata);
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Social Links Updated Successfully!!', 'url' => route('admin/social')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No Changes Were found!!']);
        }
        return FALSE;
    }

    // ------------------------- Faqs ------------------------------
    public function faqs() {
        $data['title'] = "FAQs";
        $data['admin_detail'] = $this->admin_model->getAdminDetail(session()->get('admin_id'));
        $data['faqs'] = $this->admin_model->getAllFaqs();
        return $this->loadview('faqs/faq', $data);
    }

    public function openFaqForm(Request $request){
        $faq_id = $request->faq_id;
        if(empty($faq_id)){
            $data['data'] = null;
            $htmlwrapper = view('admin/faqs/faq-form', $data)->render();
        }else{
            $data['faq_detail'] = $this->admin_model->getFaqById($faq_id);
            $htmlwrapper = view('admin/faqs/faq-form', $data)->render();
        }
        return response()->json(['result' => 1, 'htmlwrapper' => $htmlwrapper]);
    }

    public function addFaq(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, [
            'question' => 'required',
            'answer'   => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $insert_data = [
            'question'  => $requestdata['question'],
            'answer'    => $requestdata['answer'],
        ];
        $result = DB::table('faqs')->insert($insert_data);
        if($result){
            return response()->json(['result' => 1, 'url' => route('admin/faq'), 'msg' => 'Faq added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateFaq(Request $request){
        $form_data = $request->post();
        $update_data = [
            'question'  => $form_data['question'],
            'answer'    => $form_data['answer'],
        ];
        $result = DB::table('faqs')->where('faq_id', $form_data['faq_id'])->update($update_data);
        if($result){
            return response()->json(['result' => 1, 'url' => route('admin/faq'), 'msg' => 'Faq updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }
    
    public function deleteFaq($id){
        $result = DB::table('faqs')->where('faq_id', $id)->delete();
        if($result){
            return response()->json(['result' => 1, 'url' => route('admin/faq'), 'msg' => 'Faq deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    // ------------------------- Send Notification To All ------------------------------
    public function notifiction(Request $request){
        $data['user_id'] = $request->post('user_id');
        $data['data'] = NULL;
        $model_wrapper = view('admin/notification',$data)->render();
        return response()->json(['result' => 1, 'model_wrapper' => $model_wrapper]);
        return false;
    }

    public function sendNotficationToAll(Request $request){
        $RequestData=$request->all();
        $validator = Validator::make($RequestData, $rules = [
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
            $this->admin_model->sendNotfication($id,$message,$subject);
        }
        return response()->json(['result' => 1, 'msg' => 'Notification Sent Successfully.','url'=> route('admin/users')]);
        return false;
    }

    public function change_status(Request $request, $id, $status, $table, $wherecol, $status_variable){
        $delete_service = change_status($id, $status, $table, $wherecol, $status_variable, '=');
        $status_type = $request->post('status_type');
        if($status_type != 'delete'){
            $message = 'Status Changed Successfully!';
        }else{
            $message = 'Service Deleted Successfully!';
        }
        if(!empty($delete_service)){
            return response()->json(['result' => 1, 'msg' => $message, 'url' => route('admin/users')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Something Went Wrong']);
        }
    }

    // ------------------------- Forgot Password ------------------------------
    public function forget_password(){
        $data['title'] = "Forget Password";
        return view('admin/forget_password', $data);
    }

    public function forgot_password(Request $request){
        $email = $request->post('email');
        $admin_detail = $this->admin_model->get_admin_by_email($email);
        if(!empty($admin_detail)){
            $this->send_password_reset_mail($admin_detail);
            $this->admin_model->forgetPasswordLinkValidity($admin_detail->id);
            return response()->json(['result' => 1, 'msg' => 'Reset password link has been sent to your email-id', 'url' => route('admin/login')]);
            return FALSE;
        }else{
            return response()->json((['result' => -1, 'msg' => 'Please enter valid email-id!']));
            return FALSE;
        }
    }
    
    public function reset_password($admin_id){
        $data['admin_detail'] = $this->admin_model->getAdminDetail($admin_id);
        $data['title'] = "Reset Password";        
        $data['admin_id'] = $admin_id;
        $id = substr($admin_id, 10);
        $admin_id = substr($id, 0, -10);
        $forget_password = $this->admin_model->getLinkValidity($admin_id);
        if($forget_password['status'] == 1){
            $data['forget_password'] = 'expired';
        }else{
            $data['forget_password'] = 'valid';
        }
        $this->admin_model->linkValidity($admin_id);
        return view('admin/reset_password',$data);
    }
    
    public function do_reset_password(Request $request){
        $encrypted_id = $request->post('admin_id');
        $newpassword=hash('sha256',$request->post('newpassword'));
        $admin_id = decryptionID($encrypted_id);
        $result = $this->admin_model->do_fogot_password($admin_id,$newpassword);
        if (!empty($result)) {
            return response()->json(['result' => 1, 'url' => route('admin/login'), 'msg' => 'Pasword Reset Successfully']);
            return FALSE;
        } else {
            return response()->json(['result' => -1, 'msg' => 'New Password Cannot Be Same As Old Password.']);
            return FALSE;
        }
    }

    // ------------------------- Contact Us ------------------------------
    public function contactus() {
        $data['title'] ='Contact Details';
        $data['basic_datatable'] ='1';
        $data['contact_detail'] = $this->admin_model->getContactDetail();
        return $this->loadview('contactus/contactus',$data);
    }

    public function open_contact_form($id=NULL){
        $data['title'] = 'Edit Contact Details';
        $data['contact_detail'] = $this->admin_model->getContactDetail();
        return $this->loadview('contactus/contact-form',$data,true);
    }

    public function doUpdateContact(Request $request){
        $Requestdata = $request->all();
        $validator = Validator::make($Requestdata, $rules = [
            'company_name' => 'required',
            'address' => 'required',
            'email1' => 'required|email',
            'email2' => 'required|email',
            'contact_no1' => 'required',
            'contact_no2' => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $result=$this->admin_model->updateContactDetails($request);
        if($result){
            return response()->json(['result' => 1, 'msg' => 'Contact details updated successfully', 'url' => route('admin/contactus')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

}
