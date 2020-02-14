<?php
/**
 * Created by PhpStorm.
 * User: M.DilawarKhanAzeemi
 * Date: 9/24/2019
 * Time: 4:41 PM
 */

class FormClass
{
    public function FormSubmit($form_detail)
    {
        global $DB;
        $sql ="INSERT INTO form(f_detail) VALUES ('$form_detail') ";
        if ($DB->qr($sql))
        {
            $data = array(
                'success'   => true,
                'message'   => "Form Submit successfully."
            );
        }
        else
        {
            $data = array(
                'success'   => false,
                'message'   => "Please try again."
            );
        }
        return json_encode($data);
    }
}