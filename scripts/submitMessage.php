<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 3/1/2015
 * Time: 11:13 PM
 */

$comment = $_REQUEST['comment'];

$user = $_COOKIE['user'];

$profiles = json_decode(file_get_contents("profiles.json"));
$myData = json_decode(file_get_contents($user.".json"));

$profile = null;
foreach ($profiles as $prof){
    if ($prof->name == $user){
        $profile = $prof;
    }
}

$profile->sequence++;

$newMessage = new stdClass();
$newMessage->MessageID = $profile->uuid.":".$profile->sequence;
$newMessage->Originator = $user;
$newMessage->Text = $comment;

$myData[] = $newMessage;

$found = false;
foreach($myData[0] as $receipt){
    if ($receipt->uuid == $profile->uuid){
        $found = true;

        $receipt->Want = $profile->sequence;
    }
}
if (!$found){
    $newReceipt = new stdClass();
    $newReceipt->uuid = $profile->uuid;
    $newReceipt->Want = $profile->sequence;

    $myData[0][] = $newReceipt;
}


file_put_contents($user.'.json', json_encode($myData));
file_put_contents('profiles.json', json_encode($profiles));

print json_encode(true);