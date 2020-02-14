<?php
/**
 * Created by PhpStorm.
 * User: M.DilawarKhanAzeemi
 * Date: 11/11/2019
 * Time: 12:41 PM
 */

class ServiceProvide
{
    private function checkEmail($email)
    {
        global $DB;
        $sql = "SELECT email FROM service_providers WHERE email = '$email' ";
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

    private function updateServiceProviderFCM($fcm,$email)
    {
        global $DB;
        $sql = "UPDATE service_providers SET fcm_token = '$fcm' WHERE email = '$email' ";
        if ($DB->qr($sql))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function goOnline($id , $address , $latitude , $longitude)
    {
        global $DB;
        $sql = "UPDATE service_providers SET address = '$address' , latitude = '$latitude' , longitude = '$longitude' , is_active = '1' WHERE id = '$id' ";
        if ($DB->qr($sql))
        {
            $data = array(
                'success'   => true,
                'message'   => 'You are online.'
            );
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => 'Something went wrong.'
            );
        }
        return json_encode($data);
    }

    public function goOffline($id)
    {
        global $DB;
        $sql = "UPDATE service_providers SET is_active = '0' WHERE id = '$id' ";
        if ($DB->qr($sql))
        {
            $data = array(
                'success'   => true,
                'message'   => 'You are offline.'
            );
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => 'Something went wrong.'
            );
        }
        return json_encode($data);
    }

    public function ServiceProviderDetailByIDForServer($id)
    {
        global $DB;
        $sql = "SELECT id, name, email, phone, image, address, latitude, longitude, fcm_token, is_active, status FROM service_providers WHERE id = '$id' ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        return $result;
    }

    public function ServiceProviderDetail($id)
    {
        global $DB;
        $sql = "SELECT name, email, phone, image, address, latitude, longitude, is_active, status FROM service_providers WHERE id = '$id' ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        if ($DB->nr($res)>0)
        {
            if ($result['status'] == 2)
            {
                $data = array(
                    'success'   => false,
                    'message'   => 'Your account has been suspend.'
                );
            }
            else
            {
                $data = array(
                    'success'   => true,
                    'message'   => '',
                    'name'      => $result['name'],
                    'email'     => $result['email'],
                    'phone'     => $result['phone'],
                    'image'     => image_view_path.$result['image'],
                    'address'   => $result['address'],
                    'latitude'  => $result['latitude'],
                    'longitude' => $result['longitude'],
                    'is_active' => $result['is_active']
                );
            }
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => 'Something went wrong.'
            );
        }
        return json_encode($data);
    }

    public function ProviderRegistration($param)
    {
        global $DB;
        $pass = $this->enc_password($param['password']);
        $sql = "INSERT INTO service_providers(name, email, phone, password, fcm_token) VALUES ('".$param['name']."' , '".$param['email']."' , '".$param['phone']."' ,'$pass' , '".$param['fcm_token']."' ) ";
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

    public function login($email,$pass,$fcm)
    {
        global $DB;
        $this->updateServiceProviderFCM($fcm,$email);
        $enc_pass = $this->enc_password($pass);
        $sql = "SELECT id, name, email, status FROM service_providers WHERE email = '$email' AND password = '$enc_pass' ";
        $res = $DB->qr($sql);
        $detail = $DB->fa($res);
        if ($DB->nr($res)>0)
        {
            if ($detail['status'] == 0)
            {
                $data  = array(
                    'success'   => false,
                    'message'   => 'Waiting account approval.'
                );
            }
            else if ($detail['status'] == 2)
            {
                $data  = array(
                    'success'   => false,
                    'message'   => 'Your account has been suspend.'
                );
            }
            else
            {
                $data  = array(
                    'success'   => true,
                    'message'   => 'Login Successful.',
                    'user_id'   => $detail['id']
                );
            }
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
}