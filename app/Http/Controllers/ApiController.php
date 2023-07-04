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

    public function __construct()
    {
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

        $name = $requestData['first_name'] . ' ' . $requestData['last_name'];
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
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'OTP has been sent to your registered phone', 'data' => $result], 200);
        } else {
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

        $userDetails = $this->api_model->getUserByID($user_id);

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
        } else {
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

        if ($result) {
            $message = "Profile updated successfully";
            return response()->json(['result' => 1, 'msg' => $message, 'data' => $userDetails], 200);
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

    public function getAllShips()
    {
        $result = $this->api_model->getAllShips();
        foreach($result as $value){
            $value->guest = select('ship_details', 'ship_value', ['ship_value_type' => 'guest', 'ship_id' => $value->ship_id]);
            $value->ratings = select('review', 'ratings', ['ship_id' => $value->ship_id])->avg('ratings');
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Ships data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getAllDestinations()
    {
        $result = $this->api_model->getAllDestinations();
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Destinations data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getAllAdventures()
    {
        $result = $this->api_model->getAllAdventures();
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Adventures data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getAllPost(Request $request)
    {
        $user_id = $request->post('user_id');
        if (empty($user_id)) {
            return response()->json(['result' => 0, 'message' => 'UserId Required!'], 400);
        }

        $result = $this->api_model->getAllPost($user_id);

        foreach ($result as $value) {
            $multiple_images = select('post_images', 'image_url as image', ['post_id' => $value->post_id]);
            $value->multi_images = $multiple_images;
        }

        if ($result) {
            return response()->json(['result' => 1, 'msg' => "Posts data fetched.", 'data' => $result]);
        } else {
            return response()->json(['result' => -1, 'msg' => "Something went wrong!"]);
        }
    }

    public function uploadPost(Request $request)   {
        $requestdata = $request->all();
        $validator = Validator::make($requestdata, [
            'user_id' => 'required|numeric',
            'post_images' => 'required|array|max:10',
            'post_images.*' => 'image|mimes:jpeg,jpg,png',
            'journey_date' => 'required|date',
            'destination' => 'required',
            // 'post_title' => 'required|string',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => "0", 'errors' => $validator->errors()->first(),], 401);
        }

        $user_id = $requestdata['user_id'];

        $journey_date = $requestdata['journey_date'];

        $postdata = array(
            'user_id'  => $user_id,
            'Post_date' => $journey_date,
            'description' => $requestdata['description'],
            // 'post_title' => $requestdata['post_title'],
            'destination' => $requestdata['destination'],
        );

        $post_id = insert('posts', $postdata);

        $post_images = multipleCloudinaryUploads($request, 'post_images');
        if (!empty($post_images)) {
            foreach ($post_images as $value) {
                $other_image_data['post_id'] = $post_id;
                $other_image_data['image_url'] = $value;
                insert('post_images', $other_image_data);
            }
        }
        if ($post_id) {
            return response()->json(['result' => '1', 'message' => 'Post added Successfully']);
        } else {
            return response()->json(['result' => '-1', 'message' => 'Error Occured']);
        }
    }

    public function getSettings(Request $request)
    {
        $type = $request->post('type');
        if (empty($type)) {
            return response()->json(['result' => '-1', 'message' => 'Type Required!']);
        } elseif (!empty($type) && $type == 'Privacy Policy') {
            $data = select('settings', ['description', 'page_title'], [['page_title', '=', $type]])->first();
            if ($data) {
                return response()->json(['result' => '1', 'message' => 'Data Found', 'data' => $data]);
            } else {
                return response()->json(['result' => '-1', 'message' => 'Error Occured']);
            }
        } elseif (!empty($type) && $type == 'Terms and Conditions') {
            $data = select('settings', ['description', 'page_title'], [['page_title', '=', $type]])->first();
            if ($data) {
                return response()->json(['result' => '1', 'message' => 'Data Found', 'data' => $data]);
            } else {
                return response()->json(['result' => '-1', 'message' => 'Error Occured']);
            }
        } elseif (!empty($type) && $type == 'About Us') {
            $data = select('settings', ['description', 'page_title'], [['page_title', '=', $type]])->first();
            if ($data) {
                return response()->json(['result' => '1', 'message' => 'Data Found', 'data' => $data]);
            } else {
                return response()->json(['result' => '-1', 'message' => 'Error Occured']);
            }
        } elseif (!empty($type) && $type == 'Contact Us') {
            $data = select('settings', ['description', 'page_title'], [['page_title', '=', $type]])->first();
            if ($data) {
                return response()->json(['result' => '1', 'message' => 'Data Found', 'data' => $data]);
            } else {
                return response()->json(['result' => '-1', 'message' => 'Error Occured']);
            }
        } else {
            return response()->json(['result' => '-1', 'message' => 'Invalid Type!']);
        }
    }

    public function searchShipByKeyword(Request $request)
    {
        $keyword = $request->post('keyword');
        $data = $this->api_model->searchShipByKeyword($keyword);
        dd($data);
        die;
        foreach ($data as $value) {
            $multiple_images = select('ship_images', 'image_url as image', ['ship_id' => $value->ship_id]);
            $value->multi_images = $multiple_images;
        }
        $data = array("ships" => $data);
        if ($data['ships']->count() > 0) {
            return response()->json(['result' => 1, 'msg'  => "Data found", 'data' => $data]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "No Data FOUND", 'data' => NULL]);
        }
    }

    public function rateAdventure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'ratings' => 'required',
        ], [
            'required' => 'The :attribute is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $user_id = $request->input('user_id');
        $adventureId = $request->input('adventure_id');
        $adventureType = $request->input('adventure_type');

        $adventureDetails = $this->api_model->getAdventureDetailsInfo($adventureId, $adventureType);

        $destinationId = null;
        $shipId = null;
        $activityId = null;

        foreach ($adventureDetails as $value) {
            $id = $value->journey_value;

            if ($adventureType == 'destinations') {
                $value->adventures = select('destinations', ['destination_id', 'name'], ['destination_id' => $id, 'status' => 'Active']);
                foreach ($value->adventures as $val1) {
                    $destinationId = $val1->destination_id;
                }
            } elseif ($adventureType == 'ships') {
                $value->ships = select('ships', ['ship_id', 'ship_name'], ['ship_id' => $id, 'status' => 'Active']);
                foreach ($value->ships as $val2) {
                    $shipId = $val2->ship_id;
                }
            } elseif ($adventureType == 'adventures') {
                $value->adventures = select('adventures', ['adventure_id', 'journey'], ['adventure_id' => $id, 'status' => 'Active']);
                foreach ($value->adventures as $val3) {
                    $adventureId = $val3->adventure_id;
                }
            }
        }

        $existingRating = $this->api_model->getUserAdventureRating($user_id, $adventureType);
        $reviews = $request->input('review');
        $ratings = $request->input('ratings');
        $pictures = $request->file('pictures');
        if ($existingRating) {
            delete('review', 'review_id', $existingRating->review_id);
            delete('review_images', 'review_id', $existingRating->review_id);
        }
        $reviews = $request->input('review');
        $ratings = $request->input('ratings');
        $pictures = $request->file('pictures');

        foreach ($reviews as $index => $review) {
            $insertData = [
                'user_id' => $request->input('user_id'),
                'review' => $review,
                'ratings' => $ratings[$index],
                'review_type' => $adventureType,
                'destination_id' => $destinationId,
                'ship_id' => $shipId,
                'adventure_id' => $adventureId,
            ];

            $result = $this->api_model->rateAdventure($insertData);
        }
        // dd($result); die;
        if ($result) {
            if (!empty($pictures)) {
                $pictures = multipleCloudinaryUploads($request, 'pictures');
                foreach ($pictures as $image) {
                    $data['review_id'] = $result;
                    $data['image_url'] = $image;
                    insert('review_images', $data);
                }
            }
            $data = $this->api_model->getReviewDetails($result);
            return response()->json(['result' => 1, 'msg' => 'Adventure rated successfully.', 'data' => $data], 200);
        } else {
            return response()->json(['result' => -1, 'msg' => 'Something went wrong!'], 500);
        }
    }
}
