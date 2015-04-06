<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 3/1/2015
 * Time: 8:40 PM
 */

$name = $_REQUEST['name'];

$object = json_decode(file_get_contents("php://input"));

$myData = json_decode(file_get_contents($name.".json"));

if (!is_array($myData) || sizeof($myData)<2)
    die();

if (property_exists($object,"Rumor")){
    list($uuid,$seqNum) = explode(':',$object->Rumor->MessageID);
    $seqNum = (int)$seqNum;

    $found = false;
    foreach ($myData[0] as $receipts){
        if ($receipts->uuid == $uuid){
            $found = true;

            if ($seqNum != $receipts->Want + 1){
                die();
            }

            $receipts->Want = $seqNum;
        }
    }
    if (!$found){
        $newReceipt = new stdClass();
        $newReceipt->uuid = $uuid;
        $newReceipt->Want = $seqNum;

        $myData[0][] = $newReceipt;
    }

    $myData[] = $object->Rumor;
} else {
    $found = false;
    foreach ($myData[1] as $endPoint){
        if ($endPoint->EndPoint == $object->EndPoint){
            $found = true;

            $endPoint->Want = $object->Want;
        }
    }
    if (!$found){
        $newEndPoint = new stdClass();
        $newEndPoint->EndPoint = $object->EndPoint;
        $newEndPoint->Want = $object->Want;
        $newEndPoint->Sent = new stdClass();

        $myData[1][] = $newEndPoint;
    }
}

file_put_contents($name.".json",json_encode($myData));