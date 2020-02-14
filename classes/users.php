<?php
/**
 * Created by PhpStorm.
 * User: DilawarKhan
 * Date: 2/5/2019
 * Time: 6:25 PM
 */

class users
{
    private function checkEmail($email)
    {
        global $DB;
        $sql = "SELECT email FROM users WHERE email = '$email' ";
        $res = $DB->qr($sql);
        if($DB->nr($res)>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function enc_password($password){
        $pass = sha1($password.KEY);
        return $pass;
    }

    public function GetUserDetailByIDForServer($id)
    {
        global $DB;
        $sql = "SELECT id, name, email, phone, image, address, latitude, longitude, password, fcm_token, is_active, status FROM users WHERE id = '$id' ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        return $result;
    }

    public function GetUserDetail($id)
    {
        global $DB;
        $sql = "SELECT id, name, email, phone, image, address, latitude, longitude, password, fcm_token, is_active, status FROM users WHERE id = '$id'";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        $data = array(
            'user_id'           =>  $result['user_id'],
            'user_name'         =>  $result['user_name'],
            'user_email'        =>  $result['user_email'],
            'user_password'     =>  $result['user_password'],
            'user_image'        =>  user_image_url.$result['user_image'],
            'user_dob'          =>  $result['user_dob'],
            'user_gender'       =>  $result['user_gender'],
            'user_phone'        =>  $result['user_phone'],
            'user_address'      =>  $result['user_address']
        );
        return json_encode($data);
    }

    private function updateUserFCM($fcm,$email)
    {
        global $DB;
        $sql = "UPDATE users SET fcm_token = '$fcm' WHERE email = '$email' ";
        if ($DB->qr($sql))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function UserRegistration($param)
    {
        global $DB;
        $pass = $this->enc_password($param['password']);
        $sql = "INSERT INTO users(name, email, password, fcm_token) VALUES ('".$param['name']."' , '".$param['email']."' , '$pass' , '".$param['fcm']."') ";
        if ($this->checkEmail($param['email']))
        {
            $data  = array(
                'success'   => false,
                'message'   => 'Email already exist.'
            );
        }
        else
        {
             if ($DB->qr($sql))
             {
                 $data  = array(
                     'success'   => true,
                     'message'   => 'Registration Successful.'
                 );
             }
             else
             {
                 $data  = array(
                     'success'   => false,
                     'message'   => 'Server error.'
                 );
             }
        }
        return json_encode($data);
    }

    public function login($emai,$pass,$fcm)
    {
        global $DB;
        $enc_pass = $this->enc_password($pass);
        $this->updateUserFCM($fcm,$emai);
        $sql = "SELECT id, name, email, password FROM users WHERE email = '$emai' AND password = '$enc_pass' ";
        $res = $DB->qr($sql);
        $detail = $DB->fa($res);
        if ($DB->nr($res)>0)
        {
            $data  = array(
                'success'   => true,
                'message'   => 'Login Successful.',
                'u_id'      => $detail['id']
            );
        }
        else
        {
            $data  = array(
                'success'   => false,
                'message'   => 'Invalid Login detail.'
            );
        }
        return json_encode($data);
    }

    public function jobPosting($param)
    {
        global $DB, $serviceProvider, $pushNotification, $pusher;

        $date = date('m/d/Y h:i:s a', time());

        //Get target distance in miles
        $target_distance = Target_distance; //1.5km is approx 1 miles

        $sql = "SELECT id, name, ( 3959 * acos( cos( radians('".$param['request_latitude']."') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('".$param['request_longitude']."') ) + sin( radians('".$param['request_latitude']."') ) * sin( radians( latitude ) ) ) ) AS distance
                FROM service_providers
                WHERE latitude IS NOT NULL
                AND longitude IS NOT NULL
                AND is_active = 1
                HAVING distance < '$target_distance'
                ORDER BY distance ASC
                LIMIT 0,1 ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        $sp_detail = $serviceProvider->ServiceProviderDetailByIDForServer($result['id']);
        $user_detail = $this->GetUserDetailByIDForServer($param['user_id']);
        if ($DB->nr($res)>0)
        {
            $jobSQL = "INSERT INTO jobs(user_id, service_category_id, task_detail, request_timestamp, request_address, request_latitude, request_longitude, job_accepted_by, service_provider_address, service_provider_latitude, service_provider_longitude) 
                       VALUES ('".$param['user_id']."' , '".$param['service_category_id']."' , '".$param['task_detail']."' , '$date' , '".$param['request_address']."' , '".$param['request_latitude']."' , '".$param['request_longitude']."' , '".$result['id']."' , '".$sp_detail['address']."' , '".$sp_detail['latitude']."' , '".$sp_detail['longitude']."' ) ";
            $last_id = $DB->get_last_id($jobSQL);
            if ($last_id != false)
            {
                $data  = array(
                    'success'   => true,
                    'message'   => "$last_id",
                );
                $pusherData = array(
                    'task_id'       => $last_id,
                    'sp_id'         => $result['id'],
                    'user_image'    => image_view_path.$user_detail['image'],
                    'user_name'     => $user_detail['name'],
                    'task'          => $param['task_detail'],
                    'address'       => $param['request_address']
                );
                $pushNotification->send($sp_detail['fcm_token'],"jobDetail","New Job","You have a new Job",$last_id);
                $pusher->newJob($pusherData);
            }
            else
            {
                $data  = array(
                    'success'   => false,
                    'message'   => 'Something went wrong please try again.'
                );
            }
        }
        else
        {
            $data  = array(
                'success'   => false,
                'message'   => 'We could not find any provider please try again letter.'
            );
        }
        return json_encode($data);
    }

}