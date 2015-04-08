<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 3/1/2015
 * Time: 8:40 PM
 */

$user = $_COOKIE['user'];

$myData = json_decode(file_get_contents($user.".json"));

if (sizeof($myData[1]) < 1){
    die();
}

$randType = rand(0,1);

$randEndPoint = rand(0,(sizeof($myData[1]))-1);
//print "randendpoint = $randEndPoint\n";

$toSend = new stdClass();
$toSend->EndPoint = "http://ec2-54-165-61-57.compute-1.amazonaws.com/gossip/scripts/receiveMessage.php?name=".$user;

if ($randType){
    $toSend->Want = new stdClass();

    if (sizeof($myData[0]) > 0){
        foreach($myData[0] as $receipt){
            $toSend->Want->{$receipt->uuid} = $receipt->Want;
        }
    }
} else {

    if (sizeof($myData[0]) == 0)
        die();

    $randReceipt = rand(0,(sizeof($myData[0]))-1);

    $uuid = $myData[0][$randReceipt]->uuid;
    $seqNum = 0;

    if (is_null($myData[1][$randEndPoint]->Want)) {
        die();
    }

    if (property_exists($myData[1][$randEndPoint]->Want,$uuid)){
        $seqNum = $myData[1][$randEndPoint]->Want->{$uuid} + 1;
    }

    $messages = $myData;
    array_shift($messages);
    array_shift($messages);

    $found = false;
    foreach($messages as $message) {
        if ($message->MessageID == $uuid . ":" . $seqNum) {
            $found = true;

            $toSend->Rumor = $message;
            break;
        }
    }
    if (!$found){
        die();
    }
}

$toSend = json_encode($toSend);
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $myData[1][$randEndPoint]->EndPoint);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($toSend)
));

$result = curl_exec($ch);

print json_encode($result);