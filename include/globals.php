<?php
/**
 * Created by DK_KHAN.
 * User: DK_KHAN
 * Date: 10/4/2018
 * Time: 1:22 PM
 */

include "mysql.php";
$DB = new mysql_functions();

include "classes/FormClass.php";
$form = new FormClass();

include "classes/users.php";
$user = new users();

include "classes/ServiceClass.php";
$service = new ServiceClass();

include "classes/ServiceProvide.php";
$serviceProvider = new ServiceProvide();

include "classes/PushNotification.php";
$pushNotification = new PushNotification();

include "classes/PusherClass.php";
$pusher = new PusherClass();