<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 3/1/2015
 * Time: 11:13 PM
 */

$url = $_REQUEST['url'];

$user = $_COOKIE['user'];

$myData = json_decode(file_get_contents($user.".json"));

// Make sure this peer doesn't already exist
foreach($myData[1] as $peer){
    if ($peer->EndPoint == $url) {
        die(json_encode(false));
    }
}

$newPeer = new stdClass();
$newPeer->EndPoint = $url;
$newPeer->Want = null;
$newPeer->Sent = new stdClass();

$myData[1][] = $newPeer;

file_put_contents($user.".json",json_encode($myData));

print json_encode(true);