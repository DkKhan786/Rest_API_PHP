<?php
/**
 * Created by DK_KHAN.
 * User: DK_KHAN
 * Date: 10/4/2018
 * Time: 1:02 PM
 */
header('Content-type: application/json');
include "include/globals.php";

//take Request Key values from Android Via Post
$req_key  = $_POST['req_key'];

// check which Methode should execute
switch($req_key)
{
    // User registration
    case 'user_registration':
        $param = array(
            'name'      =>  $_POST['name'],
            'email'     =>  $_POST['email'],
            'password'  =>  $_POST['password'],
            'fcm'       =>  $_POST['fcm_token']
        );
        echo $user->UserRegistration($param);
        break;

    // Service Provider Registration
    case 'service_provider_registration':
        $param = array(
            'name'      =>  $_POST['name'],
            'email'     =>  $_POST['email'],
            'phone'     =>  $_POST['number'],
            'password'  =>  $_POST['password'],
            'fcm_token' =>  $_POST['fcm_token']
        );
        echo $serviceProvider->ProviderRegistration($param);
        break;

        // user login
    case 'user_login':
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $fcm = $_POST['fcm_token'];
        echo $user->login($email,$pass,$fcm);
        break;

    // Service Provider Login
    case 'service_provider_login':
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $fcm_token = $_POST['fcm_token'];
        echo $serviceProvider->login($email,$pass,$fcm_token);
        break;

        // Load service category
    case 'load_service_category':
        echo $service->load_service_category();
        break;

    // Load Service provider detail
    case 'load_service_provider_detail':
        $id = $_POST['service_provider_id'];
        echo $serviceProvider->ServiceProviderDetail($id);
        break;

    // Service Provider Go Online
    case 'service_provider_go_online':
        $id = $_POST['service_provider_id'];
        $address = $_POST['address'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        echo $serviceProvider->goOnline($id,$address,$latitude,$longitude);
        break;

    // Service Provider Go Offline
    case 'service_provider_go_offline':
        $id = $_POST['service_provider_id'];
        echo $serviceProvider->goOffline($id);
        break;

    // Job Post find service provider
    case 'job_post_find_someone':
        $param = array(
            'user_id'               =>  $_POST['user_id'],
            'service_category_id'   =>  $_POST['service_category_id'],
            'task_detail'           =>  $_POST['task_detail'],
            'request_address'       =>  $_POST['request_address'],
            'request_latitude'      =>  $_POST['request_latitude'],
            'request_longitude'     =>  $_POST['request_longitude'],
        );
        echo $user->jobPosting($param);
        break;

    // Load job detail for service provider
    case 'job_detail_for_service_provider':
        $job_id = $_POST['job_id'];
        echo $service->job_detail_for_service_provider_by_id($job_id);
        break;

    // Load job detail for users
    case 'job_detail_for_user':
        $job_id = $_POST['job_id'];
        echo $service->job_detail_for_user_by_id($job_id);
        break;

    // Service Provider accepting job
    case 'service_provider_accepting_job':
        $job_id = $_POST['job_id'];
        echo $service->sp_accepting_job($job_id);
        break;

    // test push noti
    case 'test_push_noti':
        $fcm = $_POST['fcm'];
        echo $pushNotification->send($fcm,"Title","Hello World","");
        break;

    // Default Case
    default:
        $data 	= array(
            'success' => false,
            'message' => 'Bad Request'
        );
        echo json_encode($data);
        break;

}
