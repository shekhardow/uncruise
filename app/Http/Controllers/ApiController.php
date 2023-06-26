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
        $token = $request->header('token');
        if (!empty($token)) {
            $this->userdata = $this->api_model->getUserByToken($token);
            if (!empty($this->userdata->user_id)) {
                $this->subscription_data = @$this->subscriptionManagement(@$this->userdata->user_id);
            }
        }
    }


    /* User Registration API */

    public function register(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'phone' => ['required', 'digits_between:10,15', 'unique:users'],
            'email' => 'required|email',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'required' => 'This :attribute is required',
            'email' => 'Invalid email address',
            'digits_between' => 'The :attribute must be between :min and :max digits',
            'unique' => 'The Phone Number has already been taken',
            'image' => 'The :Attribute must be an image',
            'mimes' => 'The :Attribute must be a file of type: :values',
            'max' => 'The :Attribute must not exceed :max kilobytes',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
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
            return response()->json(['result' => 1, 'msg' => $message, 'data' => ['name' => $name]], Response::HTTP_OK);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong', 'data' => null], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json(['result' => 1, 'msg' => 'OTP has been sent to your registered phone', 'data' => $result], Response::HTTP_OK);
        }else{
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!', 'data' => null], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json(['result' => 1, 'msg' => 'OTP verification successful', 'data' => $result], Response::HTTP_OK);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!', 'data' => null], Response::HTTP_INTERNAL_SERVER_ERROR);
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

        return response()->json(['result' => 1, 'msg' => 'New OTP sent successfully'], Response::HTTP_OK);
    }


    public function personalDetails(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'user_id' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'relationship_status' => 'required',
            'education' => 'required',
            'country' => 'required',
            'city' => 'required',
            //'bio' => 'required',
        ], [
            'required' => 'The :Attribute field is Required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
            return false;
        }

        $user_id = $requestData['user_id'];
        $user = $this->api_model->getUserByID($user_id);
        $image_url = @$user->profile_image;
        //profile picture upload
        if ($request->hasfile('profile_image')) {
            $image_url = singleUpload($request, 'profile_image', 'images');
        }
        $updatedata = array(
            'name' => @$requestData['name'],
            'gender' => @$requestData['gender'],
            'date_of_birth' => @$requestData['date_of_birth'],
            'relationship_status' => @$requestData['relationship_status'],
            'education' => @$requestData['education'],
            'country' => @$requestData['country'],
            'city' => @$requestData['city'],
            'bio' => @$requestData['bio'],
            'is_step' => 'more_about',
            'profile_image' => $image_url,
            'age' => @calculateAge(@$requestData['date_of_birth']),
            'status' => 'Active',
        );

        $key = $request->post('key');
        if (!empty($key)) {
            if ($key == 'update') {
                unset($updatedata['is_step']);
            }
        }
        $result = update('users', 'user_id', $user_id, $updatedata);

        if ($result = true) {
            //for the first time profile images
            $image_id=insert('users_images',['user_id'=>$user_id,'image_url'=>@$image_url]);
            update('users', 'user_id', $user_id, ['profile_image_id'=>$image_id]);
            //user details
            $user = $this->api_model->getUserByID($user_id);
            return response()->json(['result' => 1, "msg" => "Personal Details Updated", 'data' => $user]);
        } else {
            return response()->json(['result' => -1, "msg" => "Something went Wrong", 'data' => null]);
        }

    }



}
