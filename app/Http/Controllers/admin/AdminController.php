<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Models\admin\AdminModel;
use App\Models\admin\UserModel;
use App\Models\admin\CruiseModel;
use App\Models\admin\DestinationModel;
use App\Models\admin\AdventureModel;
use App\Models\admin\JourneyModel;
use App\Models\admin\ReviewModel;

class AdminController extends Controller
{
    // DB Instance
    private $admin_model;
    private $user_model;
    private $cruise_model;
    private $destination_model;
    private $adventure_model;
    private $journey_model;
    private $review_model;

    public function __construct(){
        $this->admin_model = new AdminModel();
        $this->user_model = new UserModel();
        $this->cruise_model = new CruiseModel();
        $this->destination_model = new DestinationModel();
        $this->adventure_model = new AdventureModel();
        $this->journey_model = new JourneyModel();
        $this->review_model = new ReviewModel();
    }

    private function loadview($view, $data = NULL){
        $data['admin_detail'] = admin_detail();
        if (empty($data['admin_detail'])) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ------------------------- Login ------------------------------
    public function login(Request $request){
        if($request->session()->has('admin_id')){
            return back();
        }
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
        if($form_data['rememberPswd'] == 'on'){
            setcookie('login_email', $form_data['email'], time()+60*60*24*100);
            setcookie('login_password', $form_data['password'], time()+60*60*24*100);
        }else{
            setcookie('login_email', $form_data['email'], 100);
            setcookie('login_password', $form_data['password'], 100);
        }
        if($admin_detail->email != $form_data['email'] && $admin_detail->password != hash('sha256', $form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email and Password']);
        }elseif($admin_detail->email != $form_data['email']){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Email']);
        }elseif($admin_detail->password != hash('sha256', $form_data['password'])){
            return response()->json(['result' => -1, 'msg' => 'Please Enter Valid Password']);
        }else{
            $request->session()->put(['admin_id' => $admin_detail->id]);
            return response()->json(['result' => 1, 'msg' => 'Loading... Please Wait', 'url' => route('admin/dashboard')]);
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
        $data['total_cruises'] = count($this->cruise_model->getAllCruises());
        $data['total_destinations'] = count($this->destination_model->getAllDestinations());
        $data['total_adventures'] = count($this->adventure_model->getAllAdventures());
        $data['total_journeys'] = count($this->journey_model->getAllJourneys());
        $data['total_reviews'] = count($this->review_model->getAllReviews());
        $data['total_testimonials'] = count($this->review_model->getAllTestimonials());
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
        }elseif($key=='contact-us'){
            $type = 'Contact';
            $data['title'] = 'Contact Us';
        }
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
        if($key == 'Terms'){
            $url = route('admin/siteSetting',['key' => 'terms-condition']);
            $title = 'Terms & Condition';
        }elseif($key == 'Privacy'){
            $url = route('admin/siteSetting',['key' => 'privacy-policy']);
            $title = 'Privacy Policy';
        }elseif($key=='contact-us'){
            $type = 'Contact';
             $url = route('admin/siteSetting',['key' => 'contact-us']);

            $title = 'Contact Us';
        }
        else{
            $url = route('admin/siteSetting',['key' => 'contact-us']);
            $title = 'Contact Us';
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
            $maildata['support_email'] = $admin_detail->support_email;
            $maildata['message'] = 'Your Otp for forget password is ' . $otp;
            $sendmailotp = $this->sendpasswordResetMail($maildata);
            // if($sendmailotp){
            //     return response()->json(['result' => 1, 'msg' => 'OTP sent to your email-id', 'url' => redirect()->route('admin/reset_password')->with('status', 'Check your Email for OTP')->getTargetUrl()]);
            // }else{
            //     return response()->json((['result' => -1, 'msg' => 'Failed to send otp!']));
            // }
            return response()->json(['result' => 1, 'msg' => 'OTP sent to your email-id', 'url' => redirect()->route('admin/reset_password')->with('status', 'Check your Email for OTP')->getTargetUrl()]);
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
            return response()->json((['result' => -1, 'msg' => 'Confirm password is required!']));
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
                    return response()->json(['result' => -1, 'msg' => "Couldn't change password!"]);
                }
            }else{
                return response()->json(['result' => -1, 'msg' => 'Confirm with new password!']);
            }
        }else{
            return response()->json(['result' => -1, 'msg' => "New password shouldn't be same as old!"]);
        }
    }

    public function sendpasswordResetMail($data){
        $htmlContent = view('admin/mail/send_otp_mail', $data)->render();
        $from = $data['support_email'];
        $to = $data['email'];
        $subject = "[UnCruise Admin] Forgot Password";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        @mail($to, $subject, $htmlContent, $headers);
        return false;
    }

    public function change_status(Request $request, $id, $status, $table, $wherecol, $status_variable){
        $delete_faq = change_status($id, $status, $table, $wherecol, $status_variable, '=');
        if(substr($table, -1) === "s"){
            $table = substr($table, 0, -1);
        }
        $status_type = $request->post('status_type');
        if($status_type != null){
            $message = 'Status changed successfully';
        }else{
            $message = ucfirst($table).' deleted successfully';
        }
        if(!empty($delete_faq)){
            return response()->json(['result' => 1, 'msg' => $message]);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!']);
        }
    }

    // Toggle status change
    public function toggleStatus(Request $request){
        $change_status = $request->post('changevalue');
        $change_column_name = $request->post('column_name');
        $wherevalue = $request->post('wherevalue');
        $table = $request->post('table');
        $wherecolumn = $request->post('wherecolumn');
        update($table,$wherecolumn,$wherevalue,[$change_column_name=>$change_status]);
        return response()->json(['result' => 1, 'msg' => 'Status Changed Successfully']);
    }

}
