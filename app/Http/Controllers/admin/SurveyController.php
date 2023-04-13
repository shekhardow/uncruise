<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SurveyModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class SurveyController extends Controller
{
    // DB Instance
    private $survey_model;

    public function __construct(){
        $this->survey_model = new SurveyModel();
    }

    private function loadview($view, $data = NULL){
        $admin_detail = admin_detail();
        if (empty($admin_detail)) {
            return redirect('admin');
        }
        return view('admin/'.$view, $data);
    }

    // ------------------------- Users ------------------------------
    public function surveys() {
        $data['title'] ='Surveys';
        $data['basic_datatable']='1';
        $data['surveys'] = $this->survey_model->getAllSurveys();
        return $this->loadview('surveys/survey', $data);
    }
    
    public function surveyDetails($survey_id){
        $data['title'] ='Survey Details';
        $survey_id = decryptionID($survey_id);
        $data['survey_details'] = $this->survey_model->getSurveyBySurveyId($survey_id);
        return $this->loadview('surveys/survey_details', $data);
    }

}
