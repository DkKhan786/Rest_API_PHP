<?php
/**
 * Created by PhpStorm.
 * User: hp pc
 * Date: 10/6/2018
 * Time: 4:19 PM
 */

class PushNotification
{
    // sending push message to single user by FCM registration id
    public function send($to, $for, $title, $msg, $job_id) {
        $message = array(
            'for'       => $for,
            'title'     =>  $title,
            'message'   =>  $msg,
            'job_id'    =>  $job_id
        );
        $fields = array(
            'to'    => $to,
            'data'  => $message,
        );
        return $this->sendPushNotification($fields);
    }


    // sending push message to Multiple user by FCM registration id
    public function send_multiple($tokens, $title, $msg, $image) {
        $message = array(
            'title' =>  $title,
            'message'   =>  $msg,
            'image' =>  $image
        );
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // function makes curl request to FCM servers
    private function sendPushNotification($fields) {

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        //return $result;
    }
}