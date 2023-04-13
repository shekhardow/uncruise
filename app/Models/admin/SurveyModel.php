<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SurveyModel extends Model
{
    use HasFactory;

    public function getAllSurveys(){
        return DB::table('surveys')->select('*')->orderByDesc('survey_id')->get();
    }

    public function getSurveyBySurveyId($survey_id){
        return DB::table('surveys')->where('survey_id', $survey_id)->get();
    }

}
