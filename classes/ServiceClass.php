<?php
/**
 * Created by PhpStorm.
 * User: M.DilawarKhanAzeemi
 * Date: 11/1/2019
 * Time: 2:44 PM
 */

class ServiceClass
{
    public function load_service_category()
    {
        global $DB;
        $sql = "SELECT id, name, image FROM service_categories";
        $res = $DB->qr($sql);
        if ($DB->nr($res)>0)
        {
            while ($result = $DB->fa($res))
            {
                $data[] = array(
                    'success'   => true,
                    'message'   => "",
                    'id'        => $result['id'],
                    'name'      => $result['name'],
                    'image'     => image_view_path.$result['image'],
                );
            }
        }
        else
        {
            $data[] = array(
                'success'   => false,
                'message'   => "Record not found"
            );
        }
        return json_encode($data);
    }

    public function service_charges_by_id($service_id)
    {
        global $DB;
        $sql = "SELECT id, service_category_id, basec_charges, intermediate_charges, higher_charges FROM service_charges WHERE service_category_id = '$service_id'  ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        return $result;
    }

    public function select_job_detail_for_Server($job_id)
    {
        global $DB;
        $sql = " SELECT id, user_id, service_category_id, task_detail, request_timestamp, request_address, request_latitude, request_longitude, job_accepted_by, job_accept_timestamp, service_provider_address, service_provider_latitude, service_provider_longitude, job_start_time, `job_completed_time`, service_charges, job_type, job_hrs, total_charges, status FROM jobs WHERE id = '$job_id'  ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        return $result;
    }

    public function job_detail_for_service_provider_by_id($job_id)
    {
        global $DB;
        $sql = "SELECT j.id , j.user_id, u.name as user_name, u.image , u.phone, sc.name , j.task_detail, j.request_timestamp, j.request_address , j.request_latitude , j.request_longitude, j.service_provider_address , j.service_provider_latitude , j.service_provider_longitude , j.job_accepted_by , j.job_accept_timestamp, j.job_start_time, j.job_completed_time, j.job_type, j.job_hrs, j.service_charges, j.total_charges, j.status
                FROM jobs j
                INNER JOIN users u
                on u.id = j.user_id
                INNER JOIN service_categories sc
                on sc.id = j.service_category_id
                WHERE j.id = '$job_id' ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        if ($DB->nr($res)>0)
        {
            $data = array(
                'success'               => true,
                'message'               => '',
                'job_id'                => $result['id'],
                'user_id'               => $result['user_id'],
                'user_name'             => $result['user_name'],
                'user_image'            => image_view_path.$result['image'],
                'user_phone'            => $result['phone'],
                'cat_name'              => $result['name'],
                'task_detail'           => $result['task_detail'],
                'job_req_time'          => $result['request_timestamp'],
                'job_address'           => $result['request_address'],
                'job_latitude'          => $result['request_latitude'],
                'job_longitude'         => $result['request_longitude'],
                'sp_address'            => $result['service_provider_address'],
                'sp_latitude'           => $result['service_provider_latitude'],
                'sp_longitude'          => $result['service_provider_longitude'],
                'job_accepted_by'       => $result['job_accepted_by'],
                'job_accept_timestamp'  => $result['job_accept_timestamp'],
                'job_start_time'        => $result['job_start_time'],
                'job_completed_time'    => $result['job_completed_time'],
                'job_type'              => $result['job_type'],
                'job_hrs'               => $result['job_hrs'],
                'service_charges'       => $result['service_charges'],
                'total_charges'         => $result['total_charges'],
                'status'                => $result['status'],
            );
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => "Record not found"
            );
        }
        return json_encode($data);
    }

    public function job_detail_for_user_by_id($job_id)
    {
        global $DB;
        $sql = "SELECT j.id , j.job_accepted_by, sp.name as user_name, sp.image , sp.phone, sc.name , j.task_detail, j.request_timestamp, j.request_address , j.request_latitude , j.request_longitude, j.service_provider_address , j.service_provider_latitude , j.service_provider_longitude , j.job_accepted_by , j.job_accept_timestamp, j.job_start_time, j.job_completed_time, j.job_type, j.job_hrs, j.service_charges, j.total_charges, j.status
                FROM jobs j
                INNER JOIN service_providers sp
                on sp.id = j.job_accepted_by
                INNER JOIN service_categories sc
                on sc.id = j.service_category_id
                WHERE j.id = '$job_id' ";
        $res = $DB->qr($sql);
        $result = $DB->fa($res);
        if ($DB->nr($res)>0)
        {
            $data = array(
                'success'               => true,
                'message'               => '',
                'job_id'                => $result['id'],
                'sp_id'                 => $result['job_accepted_by'],
                'sp_name'               => $result['user_name'],
                'sp_image'              => image_view_path.$result['image'],
                'sp_phone'              => $result['phone'],
                'cat_name'              => $result['name'],
                'task_detail'           => $result['task_detail'],
                'job_req_time'          => $result['request_timestamp'],
                'job_address'           => $result['request_address'],
                'job_latitude'          => $result['request_latitude'],
                'job_longitude'         => $result['request_longitude'],
                'sp_address'            => $result['service_provider_address'],
                'sp_latitude'           => $result['service_provider_latitude'],
                'sp_longitude'          => $result['service_provider_longitude'],
                'job_accepted_by'       => $result['job_accepted_by'],
                'job_accept_timestamp'  => $result['job_accept_timestamp'],
                'job_start_time'        => $result['job_start_time'],
                'job_completed_time'    => $result['job_completed_time'],
                'job_type'              => $result['job_type'],
                'job_hrs'               => $result['job_hrs'],
                'service_charges'       => $result['service_charges'],
                'total_charges'         => $result['total_charges'],
                'status'                => $result['status'],
            );
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => "Record not found"
            );
        }
        return json_encode($data);
    }

    public function sp_accepting_job($job_id)
    {
        global $DB, $pushNotification, $pusher, $user;

        $jobDetail = $this->select_job_detail_for_Server($job_id);
        $userDetail = $user->GetUserDetailByIDForServer($jobDetail['user_id']);

        $sql = "UPDATE jobs SET status = 'Accepted' WHERE id = '$job_id' ";
        if ($DB->qr($sql))
        {
            $data = array(
                'success'   => true,
                'message'   => "Job has been accepted."
            );
            $pusherData = array(
                'job_id'    => $job_id,
                'message'   => "Job has been accepted."
            );
            $pusher->jobAccepted($pusherData);
            $pushNotification->send($userDetail['fcm_token'],"jobAccepted","Job Accepted","Your job has accepted",$job_id);
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => "Unable to accept job."
            );
        }
        return json_encode($data);
    }
}