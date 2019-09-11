<?php 
error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors',1);
error_reporting(-1);

use Vendor\Wablas\wablasclientphp\Src\WablasClient;

$apiToken = '2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO';
$wablasClient = new WablasClient($apiToken);

// // add recipient (support multiple recipient)
// $wablasClient->addRecipient('6285693784939');

// // send message
// $message = 'type your message here.';
// $wablasClient->sendMessage($message);

echo $apiToken;



// send image
// $wablasClient->sendImage('your image caption here', 'http://your.image/url/here')


