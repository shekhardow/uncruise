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
        $auth = userAuthentication($request);

        if ($auth->getStatusCode() !== 200) {
            return $auth;
        }

        $user_id = @$auth->original['data']->user_id;

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
        $auth = userAuthentication($request);

        if ($auth->getStatusCode() !== 200) {
            return $auth;
        }

        $user_id = @$auth->original['data']->user_id;

        $result = $this->api_model->getProfile($user_id);

        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Profile data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getSettings(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'type' => 'required',
        ], [
            'required' => 'This :attribute is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()], 400);
        }
        $type = $requestData['type'];

        if ($type == 'Privacy' || $type == 'Terms' || $type == 'About' || $type == 'Contact') {
            $data = select('settings', ['*'], [['type', '=', $type]])->first();
            if ($data) {
                return response()->json(['result' => '1', 'message' => 'Data Found', 'data' => $data]);
            } else {
                return response()->json(['result' => '-1', 'message' => 'Something went wrong!']);
            }
        } else {
            return response()->json(['result' => '-1', 'message' => 'Invalid Type!']);
        }
    }

    public function getAllShips()
    {
        $result = $this->api_model->getAllShips();
        foreach ($result as $value) {
            $value->guest = select('ship_details', 'ship_value', ['ship_value_type' => 'guest', 'ship_id' => $value->ship_id]);
            $value->ratings = select('review', 'ratings', ['ship_id' => $value->ship_id])->avg('ratings');
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Ships data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getShipDetails(Request $request)
    {
        $ship_id = $request->post('ship_id');
        if (empty($ship_id)) {
            return response()->json(['result' => 0, 'message' => 'ShipId Required!'], 400);
        }
        $result = $this->api_model->getShipDetails($ship_id);
        $result->images = select('ship_images', 'image_url', ['ship_id' => $ship_id]);
        $result->ratings = select('review', 'ratings', ['ship_id' => $ship_id])->avg('ratings');
        $result->size = select('ship_details', 'ship_value', ['ship_value_type' => 'size', 'ship_id' => $ship_id]);
        $result->guest = select('ship_details', 'ship_value', ['ship_value_type' => 'guest', 'ship_id' => $ship_id]);
        $result->crew = select('ship_details', 'ship_value', ['ship_value_type' => 'crew', 'ship_id' => $ship_id]);
        $result->destination = select('ship_details', 'ship_value', ['ship_value_type' => 'destination', 'ship_id' => $ship_id]);
        foreach ($result->destination as $key => $row) {
            $destination = select('destinations', 'name', ['destination_id' => $row->ship_value]);
            $row->name = $destination[0]->name;
            unset($row->ship_value);
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
        foreach ($result as $value) {
            $value->ratings = select('review', 'ratings', ['destination_id' => $value->destination_id])->avg('ratings');
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Destinations data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getDestinationDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination_id' => 'required',
            'key' => 'required',
        ], [
            'required' => 'This :attribute is required',
            'key.required' => 'Key should be (about, activities, amenities)',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $destination_id = $request->post('destination_id');
        $key = $request->post('key');
        $result = $this->api_model->getDestinationDetails($destination_id);
        if ($key == 'about') {
            $result->ratings = select('review', 'ratings', ['destination_id' => $destination_id])->avg('ratings');
            $result->images = select('destination_images', 'image_url', ['destination_id' => $destination_id]);
        } elseif ($key == 'activities') {
            $result->activities = select('destinations_activities', 'activity_id', ['destination_id' => $destination_id]);
            foreach ($result->activities as $row) {
                $activities = select('activities', ['activity_name', 'description', 'thumbnail_image'], ['destination_id' => $row->activity_id]);
                $row->activity_name = $activities[0]->activity_name;
                $row->description = $activities[0]->description;
                $row->thumbnail_image = $activities[0]->thumbnail_image;
                $row->ratings = select('review', 'ratings', ['ship_id' => $row->activity_id])->avg('ratings');
                unset($row->activity_id);
            }
        } else {
            $result->amenities = select('destinations_amenities', ['amenitie_title', 'amenitie_descriptions'], ['destination_id' => $destination_id]);
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg'  => "Destinations details fetched.", 'data' => $result]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!", 'data' => null]);
        }
    }

    public function getAllAdventures()
    {
        $result = $this->api_model->getAllAdventures();
        foreach ($result as $value) {
            $value->ratings = select('review', 'ratings', ['adventure_id' => $value->adventure_id])->avg('ratings');
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg' => 'Adventures data fetched.', 'data' => $result]);
        } else {
            return response()->json(['result' => 1, 'msg' => 'Something went wrong!']);
        }
    }

    public function getAdventureDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adventure_id' => 'required',
            'type' => 'required',
        ], [
            'required' => 'This :attribute is required',
            'type.required' => 'Type should be (destinations, ships, activities)',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first()]);
        }

        $adventure_id = $request->post('adventure_id');
        $type = $request->post('type');
        if ($type == 'destinations') {
            $result = select('adventure_details', 'journey_value', ['journey_type' => 'destinations', 'adventure_id' => $adventure_id]);
            foreach ($result as $key => $row) {
                $destination = select('destinations', ['destination_id', 'name', 'description', 'thumbnail_image'], ['destination_id' => $row->journey_value]);
                if (!empty($destination)) {
                    $row->destination_name = $destination[0]->name;
                    $row->description = $destination[0]->description;
                    $row->thumbnail_image = $destination[0]->thumbnail_image;
                    $row->ratings = select('review', 'ratings', ['destination_id' => $row->journey_value])->avg('ratings');
                    unset($row->journey_value);
                }
            }
        } elseif ($type == 'ships') {
            $result = select('adventure_details', ['journey_value'], ['journey_type' => 'ships', 'adventure_id' => $adventure_id]);
            foreach ($result as $key => $row) {
                $ships = select('ships', ['ship_id', 'ship_name', 'brief_description', 'thumbnail_image'], ['ship_id' => $row->journey_value]);
                if (!empty($ships)) {
                    $row->ship_name = $ships[0]->ship_name;
                    $row->brief_description = $ships[0]->brief_description;
                    $row->thumbnail_image = $ships[0]->thumbnail_image;
                    $row->guest = select('ship_details', 'ship_value', ['ship_value_type' => 'guest', 'ship_id' => $row->journey_value]);
                    $row->ratings = select('review', 'ratings', ['ship_id' => $row->journey_value])->avg('ratings');
                    unset($row->journey_value);
                }
            }
        } else {
            $result = select('adventure_details', 'journey_value', ['journey_type' => 'activities', 'adventure_id' => $adventure_id]);
            foreach ($result as $key => $row) {
                $ships = select('activities', ['activity_id', 'activity_name', 'description', 'thumbnail_image'], ['activity_id' => $row->journey_value]);
                if (!empty($ships)) {
                    $row->activity_name = $ships[0]->activity_name;
                    $row->description = $ships[0]->description;
                    $row->thumbnail_image = $ships[0]->thumbnail_image;
                    $row->ratings = select('review', 'ratings', ['adventure_id' => $row->journey_value])->avg('ratings');
                    unset($row->journey_value);
                }
            }
        }
        if ($result) {
            return response()->json(['result' => 1, 'msg'  => "Adventures details fetched.", 'data' => $result]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!"]);
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

    public function uploadPost(Request $request)
    {
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
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!"]);
        }
    }

    public function getUserReviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'type' => 'required',
        ], [
            'required' => 'The :attribute is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'errors' => $validator->errors()->first(),], 401);
        }

        $user_id = $request->post('user_id');
        $type = $request->post('type');

        $review_data = $this->api_model->getUserReviews($user_id, $type);

        foreach ($review_data as $value) {
            $value->multi_images = select('review_images', 'image_url as image', ['review_id' => $value->review_id]);
        }

        if ($review_data) {
            return response()->json(['result' => 1, 'msg' => 'Reviews data fetched.', 'data' => $review_data]);
        } else {
            return response()->json(['result' => '-1', 'msg' => 'Something went wrong!']);
        }
    }

    public function rateAdventure(Request $request)
    {
        try {
            $keyName = $request->input('key_name');

            // Validate the key_name
            $allowedKeyNames = ['destinations', 'ships', 'activities'];
            if (!in_array($keyName, $allowedKeyNames)) {
                return response()->json(['error' => 'Invalid key name'], 400);
            }

            $userId = $request->input('user_id');

            $uniqueIds = $request->input(rtrim($keyName, 's') . '_unique_id');

            if (is_array($uniqueIds)) {
                foreach ($uniqueIds as $uniqueId) {
                    $reviewKey = rtrim($keyName, 's') . '_' . $uniqueId . '_review';
                    $ratingKey = rtrim($keyName, 's') . '_' . $uniqueId . '_rating';

                    $review = $request->input($reviewKey);
                    $rating = $request->input($ratingKey);

                    $existingReview = $this->api_model->getAdventureReview($userId, $uniqueId, $keyName);

                    if ($existingReview) {
                        $this->api_model->updateAdventureReview($existingReview->review_id, [
                            'ratings' => $rating,
                            'review' => $review,
                        ]);

                        delete('review_images', 'review_id', $existingReview->review_id);

                        $newImagesKey = rtrim($keyName, 's') . '_' . $uniqueId . '_images';
                        $newImages = $request->file($newImagesKey);

                        if (isset($newImages) && is_array($newImages)) {
                            $newPictures = multipleCloudinaryUploads($request, $newImagesKey);
                            foreach ($newPictures as $image) {
                                $data['review_id'] = $existingReview->review_id;
                                $data['image_url'] = $image;
                                insert('review_images', $data);
                            }
                        }

                        continue;
                    }

                    $insertData = [
                        'user_id' => $userId,
                        'destination_id' => null,
                        'ship_id' => null,
                        'activity_id' => null,
                        'ratings' => $rating,
                        'review' => $review,
                        'review_type' => $keyName,
                    ];

                    if ($keyName == 'destinations') {
                        $insertData['destination_id'] = $uniqueId;
                    } elseif ($keyName == 'ships') {
                        $insertData['ship_id'] = $uniqueId;
                    } elseif ($keyName == 'activities') {
                        $insertData['activity_id'] = $uniqueId;
                    }

                    $result = $this->api_model->rateAdventure($insertData);

                    $imagesKey = 'destination_' . $uniqueId . '_images';
                    $images = $request->file($imagesKey);

                    $imagesKey = rtrim($keyName, 's') . '_' . $uniqueId . '_images';
                    $images = $request->file($imagesKey);

                    if (isset($images) && is_array($images)) {
                        $pictures = multipleCloudinaryUploads($request, $imagesKey);
                        foreach ($pictures as $image) {
                            $data['review_id'] = $result;
                            $data['image_url'] = $image;
                            insert('review_images', $data);
                        }
                    }
                }
            } else {
                return response()->json(['result' => -1, 'message' => 'UniqueId must be an array.']);
            }

            return response()->json(['result' => 1, 'message' => 'Reviews submitted successfully']);
        } catch (\Exception $e) {
            // return response()->json(['result' => -1, 'message' => 'Something went wrong: ' . $e->getMessage()]);
            return response()->json(['result' => -1, 'message' => 'Something went wrong!']);
        }
    }

    public function uploadUserDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'documents' => 'required|array|max:10',
            'documents.*' => 'image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => "0", 'errors' => $validator->errors()->first()], 401);
        }

        $user_id = $request->input('user_id');

        $uploadedDocuments = multipleCloudinaryUploads($request, 'documents');
        if (!empty($uploadedDocuments)) {
            foreach ($uploadedDocuments as $document) {
                $temp['user_id'] = $user_id;
                $temp['document'] = $document;
                insert('user_documents', $temp);
            }
        }
        $document_data = select('user_documents', ['document_id', 'document', 'doc_name'], ['user_id' => $user_id]);
        if ($uploadedDocuments) {
            return response()->json(['result' => '1', 'message' => 'Document uploaded successfully.', 'data' => $document_data]);
        } else {
            return response()->json(['result' => '-1', 'message' => 'Something went wrong!']);
        }
    }

    public function deleteUserDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'document_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => "0", 'errors' => $validator->errors()->first()], 401);
        }

        $user_id = $request->input('user_id');
        $document_id = $request->input('document_id');

        $result = delete('user_documents', ['document_id' => $document_id, 'user_id' => $user_id], $document_id);

        if ($result) {
            return response()->json(['result' => '1', 'message' => 'Document deleted successfully.']);
        } else {
            return response()->json(['result' => '-1', 'message' => 'Something went wrong!']);
        }
    }

    public function getUserDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => "0", 'errors' => $validator->errors()->first(),], 401);
        }

        $user_id = $request->post('user_id');

        $document_data = select('user_documents', ['document_id', 'document', 'doc_name'], ['user_id' => $user_id]);

        if ($document_data) {
            return response()->json(['result' => '1', 'message' => 'Document fetched successfully.', 'data' => $document_data]);
        } else {
            return response()->json(['result' => '-1', 'message' => 'Something went wrong!']);
        }
    }

    public function editUserDocumentName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => "0", 'errors' => $validator->errors()->first(),], 401);
        }

        $document_id = $request->post('document_id');
        $doc_name = $request->post('doc_name');

        $document_data = update('user_documents', 'document_id',  $document_id, ['doc_name' => $doc_name]);
        $document_data = select('user_documents', ['document_id', 'document', 'doc_name'], ['document_id' => $document_id]);

        if ($document_data) {
            return response()->json(['result' => '1', 'message' => 'Document name updated successfully.', 'data' => $document_data]);
        } else {
            return response()->json(['result' => '-1', 'message' => 'Something went wrong!']);
        }
    }

    public function getAllTestimonials()
    {
        $user_data = $this->api_model->getAllTestimonials();

        foreach ($user_data as $data) {
            if (!empty($data->ship_id)) {
                $picture = select('cruises', 'thumbnail_image', ['cruise_id' => $data->ship_id]);
                $data->ship_image = $picture[0]->thumbnail_image;
            }
            if (!empty($data->destination_id)) {
                $picture = select('destinations', 'thumbnail_image', ['destination_id' => $data->destination_id]);
                $data->destination_image = $picture[0]->thumbnail_image;
            }
            if (!empty($data->activity_id)) {
                $picture = select('activities', 'thumbnail_image', ['activity_id' => $data->activity_id]);
                $data->activity_image = $picture[0]->thumbnail_image;
            }
            $data->name = $data->first_name . ' ' . $data->last_name . ' | ' . $data->country;
            unset($data->first_name);
            unset($data->last_name);
            unset($data->country);
            unset($data->activity_id);
            unset($data->ship_id);
            unset($data->destination_id);
        }

        if ($user_data) {
            return response()->json(['result' => 1, 'msg'  => "Testimonials data fetched.", 'data' => $user_data]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!"]);
        }
    }

    public function getAllActivities()
    {
        $activities = select('activities', ['activity_id', 'activity_name']);
        if ($activities) {
            return response()->json(['result' => 1, 'msg'  => "Activities data fetched.", 'data' => $activities]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!"]);
        }
    }

    public function getActivityDetails(Request $request)
    {
        $activityIds = $request->post('activity_id');
        if (empty($activityIds)) {
            return response()->json(['result' => 0, 'msg'  => "Id is required"]);
        }
        $activityData = $this->api_model->getDestinationByActivity($activityIds);
        if ($activityData) {
            return response()->json(['result' => 1, 'msg'  => "Activities details fetched.", 'data' => $activityData]);
        } else {
            return response()->json(['result' => -1, 'msg'  => "Something went wrong!"]);
        }
    }
}
