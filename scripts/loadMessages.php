<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 3/1/2015
 * Time: 9:16 PM
 */

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    parse_str(file_get_contents('php://input'), $_REQUEST);
}

$user = $_COOKIE['user'] ? $_COOKIE['user'] : '';

if (!$user)
    die(json_encode(array()));

$messages = json_decode(file_get_contents($user.".json"));

array_shift($messages);
array_shift($messages);

print json_encode($messages);
