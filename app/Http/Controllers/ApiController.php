<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ApiModel;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // DB Instance
    private $api_model;
    private $userdata;

    public function __construct(Request $request){
        $this->api_model = new ApiModel();
    }


    /* User Registration API */

    public function registration(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'phone' => ['required', 'digits_between:10,15'],
            'email' => 'required|email',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'required' => 'This :attribute is required',
            'email' => 'Invalid email address',
            'digits_between' => 'The :attribute must be between :min and :max digits',
            'image' => 'The :Attribute must be an image',
            'mimes' => 'The :Attribute must be a file of type: :values',
            'max' => 'The :Attribute must not exceed :max kilobytes',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }
        
        $phone = $requestData['phone'];
        
        $user = $this->api_model->getUserByPhone($phone);

        if (!empty($user)) {
            return response()->json(['result' => -1, 'msg' => "The Phone Number has already been taken"]);
        }

        $name = $requestData['first_name'].' '.$requestData['last_name'];
        $deviceType = $requestData['device_type'];
        $deviceId = $requestData['device_id'];

        // Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $imagePath = singleCloudinaryUpload($request, 'profile_image');
        }

        // Insert data into database
        $insertData = [
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'gender' => $requestData['gender'],
            'age' => $requestData['age'],
            'country' => $requestData['country'],
            'country_code' => $requestData['country_code'],
            'phone' => $requestData['phone'],
            'email' => $requestData['email'],
            'profile_image' => !empty($imagePath) ? $imagePath : null,
        ];

        $result = $this->api_model->doRegister($insertData, $deviceType, $deviceId);

        if ($result) {
            $message = "Registration Successful!";
            return response()->json(['result' => 1, 'msg' => $message, 'data' => ['name' => $name]], 200);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong', 'data' => null], 500);
        }
    }


    /* User Login API */

    public function login(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'phone' => 'required',
        ], [
            'required' => 'This :attribute is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $phone = $requestData['phone'];

        $user = $this->api_model->getUserByPhone($phone);

        if (empty($user)) {
            return response()->json(['result' => -1, 'msg' => "No account found with this Phone Number"]);
        }

        if (in_array($user->status, ['Deleted', 'Blocked', 'Inactive'])) {
            return response()->json(['result' => -2, 'msg' => 'Your account has been ' . strtolower($user->status) . '!'], 401);
        }

        // Send OTP to the phone number
        $otp = '1234';
        // $otp = generateOtp();
        $this->api_model->updateOTP($user->user_id, $otp);

        // Update user token
        $this->api_model->updateToken($user->user_id, generateToken());

        // Get user data and return response
        $result = $this->api_model->getUserByID($user->user_id);
        if($result){
            return response()->json(['result' => 1, 'msg' => 'OTP has been sent to your registered phone', 'data' => $result], 200);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!', 'data' => null], 500);
        }
    }


    /* Verify User Login By OTP API */

    public function verifyLogin(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'phone' => 'required',
            'otp' => 'required',
        ], [
            'required' => 'This :attribute is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $phone = $requestData['phone'];
        $otp = $requestData['otp'];

        $user = $this->api_model->getUserByPhone($phone);

        if (empty($user)) {
            return response()->json(['result' => -1, 'msg' => "No account found with this Phone Number"]);
        }

        if ($user->otp !== $otp) {
            return response()->json(['result' => -2, 'msg' => 'Invalid OTP']);
        }

        // Clear the OTP field in the database
        $this->api_model->updateOTP($user->user_id, null);

        // Update user token
        $this->api_model->updateToken($user->user_id, generateToken());

        // Get user data and return response
        $result = $this->api_model->getUserByID($user->user_id);
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'OTP verification successful', 'data' => $result], 200);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!', 'data' => null], 500);
        }
    }


    public function resendOTP(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'phone' => 'required',
        ], [
            'required' => 'The :attribute is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $phone = $requestData['phone'];

        $user = $this->api_model->getUserByPhone($phone);

        if (empty($user)) {
            return response()->json(['result' => -1, 'msg' => "No account found with this Phone Number"]);
        }

        // // Check if the last OTP was sent less than 2 minutes ago
        // $lastOTPSentAt = new DateTime($user->otp_sent_at);
        // $currentTime = new DateTime();
        // $timeDifference = $lastOTPSentAt->diff($currentTime)->format('%i');

        // if ($timeDifference < 2) {
        //     return response()->json(['result' => -2, 'msg' => 'Please wait for 2 minutes before requesting a new OTP']);
        // }

        // Generate and send a new OTP
        $newOTP = generateOTP();
        $this->api_model->updateOTP($user->user_id, $newOTP);

        // Code to send the new OTP to the user (e.g., via SMS or email)

        return response()->json(['result' => 1, 'msg' => 'New OTP sent successfully'], 200);
    }
    
    
    public function updateProfile(Request $request)
    {
        $user_id = userAuthentication($request)->user_id;
        
        if (empty($user_id)) {
            return false;
        }
        
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'age' => 'required|integer|min:1',
        ], [
            'required' => 'This :attribute is required',
            'email' => 'Invalid email address',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()], 400);
        }
        
        // Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $imagePath = singleCloudinaryUpload($request, 'profile_image');
        }else{
            $imagePath = $userDetails->profile_image;
        }
    
        $updateData = [
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'email' => $requestData['email'],
            'gender' => $requestData['gender'],
            'age' => $requestData['age'],
            'profile_image' => $imagePath,
        ];
    
        $result = $this->api_model->updateProfile($user_id, $updateData);
        $data = $this->api_model->getUserByID($user_id);
    
        if ($result) {
            $message = "Profile updated successfully";
            return response()->json(['result' => 1, 'msg' => $message, 'data' => $data], 200);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!', 'data' => null], 500);
        }
    }
    
    
    public function getAllCountries()
    {
        $result = $this->api_model->getAllCountries();
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Country data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }


    public function getProfile(Request $request)
    {
        $user_id = userAuthentication($request)->user_id;
        
        if (empty($user_id)) {
            return false;
        }

        $result = $this->api_model->getProfile($user_id);
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Profile data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }


}
