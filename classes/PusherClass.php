<?php
/**
 * Created by PhpStorm.
 * User: M.DilawarKhanAzeemi
 * Date: 2/23/2019
 * Time: 1:33 PM
 */

class PusherClass
{
    private function Connect()
    {
        require 'vendor/autoload.php';

        $options = array(
            'cluster' => 'us2',
            'useTLS' => true
        );
        $pusher = new Pusher\Pusher(
            '80dec84b107e4d61eacf',
            'e6eed79f8714b33635b2',
            '901832',
            $options
        );

        return $pusher;
    }

    public function newJob($data)
    {
        $pusher = $this->Connect();
        $pusher->trigger('job', 'new-job', $data);
    }

    public function jobAccepted($data)
    {
        $pusher = $this->Connect();
        $pusher->trigger('job', 'job-accept', $data);
    }

}