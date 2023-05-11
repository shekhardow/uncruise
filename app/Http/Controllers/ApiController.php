<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiModel;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // DB Instance
    private $api_model;
    private $userdata;

    public function __construct(Request $request){
        $this->api_model = new ApiModel();
        $token = $request->header('token');
        if (!empty($token)) {
            $this->userdata = $this->api_model->getUserByToken($token);
            if (!empty($this->userdata->user_id)) {
                $this->subscription_data = @$this->subscriptionManagement(@$this->userdata->user_id);
            }
        }
    }

    public function uniqueId(){
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNIPQRSTUVWXYZ';
        $nstr = str_shuffle($str);
        $unique_id = substr($nstr, 0, 10);
        return $unique_id;
    }

    public function sendMail($data, $view){
        $htmlContent = view('mail/' . $view, $data)->render();
        $from = "admin@scalie.io";
        $to = $data['email'];
        $subject = $data['subject'];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        @mail($to, $subject, $htmlContent, $headers);
        return false;
    }



    /*
    User Registration API (By Email/Phone)
    */
    public function register(Request $request){
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'identifier' => 'required',
            'type' => 'required|in:email,phone',
            // 'device_id' => 'required',
        ], [
            'required' => 'This :Attribute is Required',
            'in' => 'The :Attribute field must be either email or phone.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $identifier = $requestData['identifier'];
        $type = $requestData['type'];
        if ($type === 'email' && !filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['result' => 0, 'errors' => 'Invalid email address']);
        }
        if ($type === 'phone' && !preg_match('/^[0-9]{10,}$/', $identifier)) {
            return response()->json(['result' => 0, 'errors' => 'Invalid phone number']);
        }

        $otp = generateOtp();
        $user = $this->api_model->getUserByIdentifier($identifier, $type);
        if (!empty($user)) {
            if ($user->is_verified == 'no') {
                $message = "Already registered, We have sent you an OTP on your $type. Please verify yourself!";
            } else {
                update('users', 'user_id', $user->user_id, ['otp' => $otp]);
                update('users_authentication', 'user_id', $user->user_id, ['device_id' => $requestData['device_id']]);
                $message = "Already registered, We have sent you an OTP on your $type. Please verify yourself!";
            }
        } else {
            // If user does not exist, insert data into database
            if ($request->hasfile('profile_image')) {
                $profile_image = singleCloudinaryUpload($request, 'profile_image');
            }
            $insertData = [
                $type => $identifier,
                'otp' => $otp,
                'profile_image' => $profile_image,
                'first_name' => $requestData['first_name'],
                'last_name' => $requestData['last_name'],
                'gender' => $requestData['gender'],
                'age' => $requestData['age'],
                'country' => $requestData['country'],
            ];
            $deviceType = $requestData['device_type'];
            $deviceId = $requestData['device_id'];
            $result = $this->api_model->doRegister($insertData, $deviceType, $deviceId);
            if ($result) {
                $message = "Registration Successful. We have sent you an OTP on your $type. Please verify yourself!";
            } else {
                return response()->json(['result' => -1, 'msg' => 'Something went wrong', 'data' => null]);
            }
        }

        // Verification OTP mail
        $mailData = [
            'name' => 'Hi User!',
            'identifier' => $identifier,
            'otp' => $otp,
            'subject' => 'OTP Verification Mail!!'
        ];
        if ($type == 'email') {
            $mailData['email'] = $identifier;
            $this->sendMail($mailData, 'otpmail');
        } else {
            // Send SMS with OTP
        }
        return response()->json(['result' => 1, 'msg' => $message, 'data' => ['identifier' => $identifier]]);
    }



    /*
    User Login API (By Email/Phone)
    */
    public function login(Request $request){
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'identifier' => 'required',
            'type' => 'required|in:email,phone',
            'otp' => 'required',
        ], [
            'required' => 'This :Attribute is Required',
            'in' => 'The :Attribute field must be either email or phone.',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $identifier = $requestData['identifier'];
        $type = $requestData['type'];
        $otp = $requestData['otp'];
        if ($type === 'email' && !filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['result' => 0, 'errors' => 'Invalid email address']);
        }
        if ($type === 'phone' && !preg_match('/^[0-9]{10,}$/', $identifier)) {
            return response()->json(['result' => 0, 'errors' => 'Invalid phone number']);
        }

        $user = null;
        if ($type === 'email') {
            $user = $this->api_model->getUserByIdentifier($identifier, 'email');
        } else {
            // Phone-based login
            $user = $this->api_model->getUserByIdentifier($identifier, 'phone');
            if (!empty($user)) {
                // Send OTP to the phone number
                $otp = generateOtp();
                $this->api_model->updateOtp($user->user_id, $otp);
                return response()->json(['result' => 2, 'msg' => 'OTP has been sent to your registered phone', 'data' => null]);
            }
        }

        if (!$user) {
            return response()->json(['result' => -1, 'msg' => "No account found with this $type!"]);
        }

        if ($user->is_verified == 'no') {
            // Verification OTP Mail
            $maildata['name'] = $user->name;
            $maildata['email'] = $user->email;
            $maildata['message'] = 'Your verification Otp is ' . generateOtp();
            $maildata['subject'] = 'Otp Verifiation mail !!';
            $this->sendMail($maildata, 'otpmail');
            return response()->json(['result' => -2, 'msg' => 'Please verify yourself. We have resent the verification link to your email id. Please check your mail!'], 401);
        }
        if (in_array($user->status, ['Deleted', 'Blocked', 'Inactive'])) {
            header('HTTP/1.1 402 User Account ' . strtolower($user->status) . '.', true, 402);
            return response()->json(['result' => -2, 'msg' => 'Your account has been ' . strtolower($user->status) . '!'], 401);
        }

        // Verify OTP
        if ($otp != $user->otp) {
            return response()->json(['result' => -1, 'msg' => 'Invalid OTP!']);
        }

        // Update user token
        $this->api_model->updateToken($user->user_id, genrateToken());

        // Get user data and return response
        $result = $this->api_model->getUserByID($user->user_id);
        return response()->json(['result' => 1, 'msg' => 'Login Successfully', 'data' => $result]);
        return false;
    }



    /*
    Update User Profile API (Required User ID)
    */
    public function updateProfile(Request $request){
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'user_id' => 'required',
        ], [
            'required' => 'The :attribute field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        // Get user data
        $user_id = $request->input('user_id');
        $user = $this->api_model->getUserByID($user_id);
        if (!$user) {
            return response()->json(['result' => -1, "msg" => "User not found", 'data' => null]);
        }

        // Upload profile image
        $profile_image = @$user->profile_image;
        if ($request->hasFile('profile_image')) {
            $profile_image = singleCloudinaryUpload($request, 'profile_image');
            if (!$profile_image) {
                return response()->json(['result' => -1, "msg" => "Error uploading image", 'data' => null]);
            }
        }

        // Prepare update data
        $updateData = [
            'first_name' => @$requestData['first_name'],
            'last_name' => @$requestData['last_name'],
            'profile_image' => @$profile_image,
        ];

        // Update user data
        if (!update('users', 'user_id', $user_id, $updateData)) {
            return response()->json(['result' => -1, "msg" => "Error updating user data", 'data' => null]);
        }

        // Update profile image
        if ($profile_image !== $user->profile_image) {
            update('users', 'user_id', $user_id, ['profile_image' => $profile_image]);
        }

        // Get updated user data
        $user = $this->api_model->getUserByID($user_id);
        return response()->json(['result' => 1, "msg" => "Personal details updated", 'data' => $user]);
    }

}
