<?php
/**
 * Created by PhpStorm.
 * User: markazie
 * Date: 3/8/2019
 * Time: 11:37 AM
 */
$db = mysqli_connect('localhost','root','','restaurant');

if(!$db){
    echo mysqli_connect_error();
}
//mysqli_query("SET character_set_results=utf8;",$db);

ini_set('display_errors',1);
error_reporting(E_ALL);
ob_start();