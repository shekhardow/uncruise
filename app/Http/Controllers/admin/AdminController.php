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
use Illuminate\Support\Facades\Session;

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
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ------------------------- Login ------------------------------
    public function login(){
        $data['title'] ='Login';
        $data['admin_detail'] = $this->admin_model->getAdminDetails();
        return view('admin/login', $data);
    }

    public function check_login(Request $request){
        $form_data = $request->post();
        $admin_detail = $this->admin_model->getAdminDetails();
        if(empty($form_data['email'])){
            return response()->json((['result' => -1, 'msg' => 'Email is required!']));
        }
        if(empty($form_data['password'])){
            return response()->json((['result' => -1, 'msg' => 'Password is required!']));
        }
        if($admin_detail->email != $form_data['email'] && $admin_detail->password != hash('sha256', $form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email and Password']);
        }elseif($admin_detail->email != $form_data['email']){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email']);
        }elseif($admin_detail->password != hash('sha256', $form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Password']);
        }else{
            $request->session()->put(['admin_id' => $admin_detail->id]);
            return response()->json(['result' => 1, 'msg' => 'Loading... Please Wait', 'url' => redirect()->route('admin/dashboard')->with('status', 'Logged in successfully')->getTargetUrl()]);
        }
    }

    // ------------------------- Logout ------------------------------
    public function logout(Request $request){
        $request->session()->forget('admin_id');
        return response()->json(['result' => 1, 'url' => redirect()->route('admin/login')->with('status', 'Logged out!')->getTargetUrl()]);
    }
    
    // ------------------------- Dashboard ------------------------------
    public function dashboard(){
        $data['title'] ='Dashboard';
        $data['total_users'] = count($this->user_model->getAllUsers());
        $data['total_surveys'] = count($this->survey_model->getAllSurveys());
        return $this->loadview('dashboard/dashboard',$data);
    }

    //------------------------- Profile ------------------------------
    public function profile(){
        $data['title'] = "Profile";
        return $this->loadview('dashboard/profile', $data);
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
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
            return response()->json(['result' => 1, 'msg' => 'Profile updated succesfully', 'url' => route('admin/profile')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    // -------------------- Change Password ---------------------------
    public function changePassword(){
        $data['title'] = "Change Password";
        return $this->loadview('dashboard/change_password', $data);
    }

    public function updatePassword(Request $request){
        $form_data = $request->all();
        $validator = Validator::make($form_data, $rules = [
            'old_password' => 'required',
            'new_password' =>'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $old_password = DB::table('admin')->where(['password' => hash('sha256', $form_data['old_password'])])->first();
        if(!empty($old_password)){
            if($form_data['old_password'] !== $form_data['new_password']){
                if($form_data['new_password'] == $form_data['confirm_password']){
                    $update_data = [
                        'password' => hash('sha256', $form_data['new_password']),
                    ];
                    $changed = DB::table('admin')->where('id', 1)->update($update_data);
                    if($changed){
                        return response()->json(['result' => 1, 'url' => route('admin/dashboard'), 'msg' => 'Password changed successfully']);
                    }else{
                        return response()->json(['result' => -1, 'msg' => 'Password change process failed!']);
                    }
                }else{
                    return response()->json(['result' => -1, 'msg' => 'Please verify password with confirm password!']);
                }
            }else{
                return response()->json(['result' => -1, 'msg' => "Old password and new password shouldn't be same!"]);
            }
        }else{
            return response()->json(['result' => -1, 'msg' => 'Old password is not correct!']);
        }
    }

    // ------------ Setting Pages (Terms, Privacy, About) --------------
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

    // ------------------------- Faqs ------------------------------
    public function faqs() {
        $data['title'] = "FAQs";
        $data['faqs'] = $this->admin_model->getAllFaqs();
        return $this->loadview('faqs/faq', $data);
    }

    public function openFaqForm(Request $request) {
        $faq_id = $request->data_id;
        if(empty($faq_id)){
            // Add FAQ form
            $data['data'] = null;
            $htmlwrapper = view('admin/faqs/faq-form', $data)->render();
        }else{
            // Edit FAQ form
            $data['faq_detail'] = $this->admin_model->getFaqById($faq_id);
            $htmlwrapper = view('admin/faqs/faq-form', $data)->render();
        }
        return response()->json(['result' => 1, 'htmlwrapper' => $htmlwrapper]);
    }

    public function addFaq(Request $request){
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
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
            return response()->json(['result' => 1, 'url' => route('admin/faqs'), 'msg' => 'Faq added successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function updateFaq(Request $request, $faq_id){
        $form_data = $request->post();
        $faq_id = decryptionID($faq_id);
        $update_data = [
            'question'  => $form_data['question'],
            'answer'    => $form_data['answer'],
        ];
        $result = DB::table('faqs')->where('faq_id', $faq_id)->update($update_data);
        if($result){
            return response()->json(['result' => 1, 'url' => route('admin/faqs'), 'msg' => 'Faq updated successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }
    
    public function deleteFaq($id){
        $result = DB::table('faqs')->where('faq_id', $id)->delete();
        if($result){
            return response()->json(['result' => 1, 'url' => route('admin/faqs'), 'msg' => 'Faq deleted successfully']);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Oops... Something went wrong!']);
        }
    }

    public function change_faq_status(Request $request, $id, $status, $table, $wherecol, $status_variable){
        $delete_faq = change_status($id, $status, $table, $wherecol, $status_variable, '=');
        $status_type = $request->post('status_type');
        if($status_type != null){
            $message = 'Status changed successfully';
        }else{
            $message = 'FAQ deleted successfully';
        }
        if(!empty($delete_faq)){
            return response()->json(['result' => 1, 'msg' => $message]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!']);
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
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, $rules = [
            'social_link' => 'required',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $result = $this->admin_model->update_social_link($requestdata);
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Social links updated successfully', 'url' => route('admin/social')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
    }

    // --------------------- Contact Details -------------------------
    public function contactDetails() {
        $data['title'] ='Contact Details';
        $data['contact_details'] = $this->admin_model->getContactDetail();
        return $this->loadview('contact_details/contact_detail',$data);
    }

    public function openContactForm(){
        $data['contact_details'] = $this->admin_model->getContactDetail();
        $htmlwrapper = view('admin/contact_details/contact_details_form', $data)->render();
        return response()->json(['result' => 1, 'htmlwrapper' => $htmlwrapper]);
    }

    public function updateContactDetails(Request $request, $contact_detail_id){
        $Requestdata = $request->all();
        $contact_detail_id = decryptionID($contact_detail_id);
        $validator = Validator::make($Requestdata, $rules = [
            'company_name' => 'required',
            'address' => 'required',
            'contact_no1' => 'required',
            'contact_no2' => 'required',
            'email1' => 'required|email',
            'email2' => 'required|email',
        ], $messages = [
            'required' => 'The :attribute field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()]);
            return false;
        }
        $result = $this->admin_model->updateContactDetails($request);
        if($result){
            return response()->json(['result' => 1, 'msg' => 'Contact details updated successfully', 'url' => route('admin/contactDetails')]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'No changes were found!']);
        }
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

    // ------------------------- Forgot Password ------------------------------
    public function forgot_password(){
        $data['title'] = "Forgot Password";
        $data['admin_detail'] = $this->admin_model->getAdminDetails();
        return view('admin/forgot_password', $data);
    }

    public function sendPasswordResetOtp(Request $request){
        $email = $request->post('email');
        if(empty($email)){
            return response()->json((['result' => -1, 'msg' => 'Email is required!']));
        }
        $admin_detail = $this->admin_model->get_admin_by_email($email);
        if(empty($admin_detail)){
            return response()->json((['result' => -1, 'msg' => 'Please enter valid email-id!']));
        }
        $otp = generateOtp();
        $result = $this->admin_model->sendOtp($otp, $admin_detail->id);
        if(!empty($result)){
            // Send otp mail
            $maildata['name'] = $admin_detail->name;
            $maildata['email'] = $admin_detail->email;
            $maildata['address'] = $admin_detail->address;
            $maildata['message'] = 'Your Otp for forget password is ' . $otp;
            $sendmailotp = $this->sendpasswordResetMail($maildata);
            // if($sendmailotp){
            //     return response()->json(['result' => 1, 'msg' => 'OTP has been sent to your email-id', 'url' => route('admin/login')]);
            // }else{
            //     return response()->json((['result' => -1, 'msg' => 'Failed to send otp!']));
            // }
            return response()->json(['result' => 1, 'msg' => 'OTP has been sent to your email-id', 'url' => redirect()->route('admin/reset_password')->with('status', 'Check your Email for OTP')->getTargetUrl()]);
        }else{
            return response()->json((['result' => -1, 'msg' => 'Something went wrong!']));
        }
    }
    
    public function reset_password(){
        $data['title'] = "Reset Password";     
        $data['admin_detail'] = $this->admin_model->getAdminDetails();
        return view('admin/reset_password',$data);
    }
    
    public function doResetPassword(Request $request){
        $new_password = $request->post('new_password');
        $confirm_new_password = $request->post('confirm_new_password');
        $otp = $request->post('otp');
        if(empty($new_password)){
            return response()->json((['result' => -1, 'msg' => 'New password is required!']));
        }
        if(empty($confirm_new_password)){
            return response()->json((['result' => -1, 'msg' => 'Please confirm new password!']));
        }
        $old_password = DB::table('admin')->select('password')->first();
        if($old_password !== $new_password){
            if($new_password == $confirm_new_password){
                $update_data = [
                    'password' => hash('sha256', $new_password),
                ];
                $changed = DB::table('admin')->where('id', 1)->where('otp', $otp)->update($update_data);
                if($changed){
                    return response()->json(['result' => 1, 'url' => route('admin/login'), 'msg' => 'Password changed successfully']);
                }else{
                    return response()->json(['result' => -1, 'msg' => 'Password change process failed!']);
                }
            }else{
                return response()->json(['result' => -1, 'msg' => 'Please verify password with confirm password!']);
            }
        }else{
            return response()->json(['result' => -1, 'msg' => "Old password and new password shouldn't be same!"]);
        }
    }

    public function sendpasswordResetMail($data){
        $htmlContent = view('admin/mail/send_otp_mail', $data)->render();
        $from = "support@uncruise.com";
        $to = $data['email'];
        $subject = "[UnCruise Admin] Forgot Password";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        @mail($to, $subject, $htmlContent, $headers);
        return false;
    }

}
