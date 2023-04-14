<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminModel extends Model
{
    use HasFactory;

    public function getAdminDetail($id){
        return DB::table('admin')->where('id', $id)->first();
    }

    public function getContactDetail(){
        return DB::table('contact_details')->get()->first();
    }

    public function getAdmin(){
        return DB::table('admin')->first();
    }

    public function get_social_link(){
        return DB::table('social')
            ->where('status', '=', '1')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getAllFacebookUsers(){
        return DB::table('users')->select('users.*')
            ->where('source', '=', 'facebook')
            ->orderByDesc('user_id')->get();
    }

    public function getAllGmailUsers(){
        return DB::table('users')->select('users.*')
            ->where('source', '=', 'google')
            ->orderByDesc('user_id')->get();
    }

    public function updateProfile($data){
        return DB::table('admin')->where('id', 1)->update($data);
    }

    public function update_social_link($request){
        $social_name=$request['social_name'];
        $social_link=$request['social_link'];
        $i=0;
        $update_data_status =false;
        foreach($social_name as $value){
            $data=array(
                'social_name'=>$value,
                'social_link'=>$social_link[$i],
            );
            $update_data = DB::table('social')->where('id', $i+1)->update($data);
            if($update_data){
                $update_data_status=true;
            }
            $i++;
        }
        return $update_data_status;
    }

    //--------------------------------------------------------Forgot Password------------------------------------------
    public function do_reset_password($email,$password) {
      return  DB::table('admin')->where('email',$email)->update(['admin_password' => $password]);
    }
    
    public function get_admin_by_email($email) {
        return DB::table('admin')->where('admin_email',$email)->first();
    }
    
    public function forgetPasswordLinkValidity($admin_id) {
        
        $data = array(
            'admin_id'  => $admin_id,
            'status'   => '0',
        );
        DB::table('forgot_password')->insert($data);
        $id = DB::getPdo()->lastInsertId();
        return DB::table('forgot_password')->where('forgot_password_id',$id)->get()->first();

    }
    
    public function linkValidity($admin_id) {
        return DB::table('admin')->where('admin_id' ,$admin_id)->update(['status' => '1']);
    }
    
    public function getLinkValidity($admin_id){  
        return DB::table('forgot_password')->where('admin_id',$admin_id)->orderByDesc('forgot_password_id');    
    }
    
    public function do_fogot_password($id,$newpassword) {
       return DB::table('admin')->where('admin_id',$id)->update(['password' => $newpassword]);
    }
    //--------------------------------------------------------------------------------------------------

    public function updateContactDetails($request){
        $data=[
            'company_name' => $request['company_name'],
            'address' => $request['address'],
            'email1' => $request['email1'],
            'email2' => $request['email2'],
            'contact_no1' => $request['contact_no1'],
            'contact_no2' => $request['contact_no2'],
        ];
        $affected_row = DB::table('contact_details')->update($data);
        return $affected_row;
    }
    
    public function sendNotfication($id,$message,$subject){
        $data=array(
            'user_id' => $id,
            'message' => $message,
            'title' => $subject 
        );
        DB::table('notification')->insert($data);
        return true;
    }

    public function getAllFaqs(){
        return DB::table('faqs')->select('*')->where('status', '!=', 'Deleted')->get();
    }
    
    public function getFaqById($faq_id){
        return DB::table('faqs')->where('faq_id', $faq_id)->get()->first();
    }
    
}
