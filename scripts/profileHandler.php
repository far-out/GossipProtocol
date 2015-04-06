<?php
/**
 * Created by PhpStorm.
 * User: tmanhendy
 * Date: 2/3/2015
 * Time: 9:17 PM
 */

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    parse_str(file_get_contents('php://input'), $_REQUEST);
}

$name = $_REQUEST['name'] ? $_REQUEST['name'] : '';

$user = $_COOKIE['user'] ? $_COOKIE['user'] : '';

$profiles = json_decode(file_get_contents("profiles.json"));

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        print json_encode($profiles);

        break;
    case 'POST':
        if ($name) {
            if ($name == 'profiles')
                die(json_encode(false));

            foreach ($profiles as $profile){
                if ($profile->name == $name){
                    die(json_encode(false));
                }
            }

            $newUser = new stdClass();
            $newUser->name = $name;
            $newUser->uuid = gen_uuid();
            $newUser->sequence = -1;
            $profiles[] = $newUser;

            file_put_contents($name.'.json', json_encode(Array(Array(),Array())));
            file_put_contents('profiles.json', json_encode($profiles));

            print json_encode(true);
        } else
            die(json_encode(false));

        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}