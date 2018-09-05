<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06-Aug-18
 * Time: 11:03
 */
function response($status,$status_message,$data)
{
    header("HTTP/1.1 ".$status);

    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;

    $json_response = json_encode($response);
    return $json_response;
}