<?php
/**
 * all data POST sent from wablas.com
 * you must create URL what can receive POST data
 * we will sent data like this: 
 * id = message ID - string
 * phone = sender phone - string
 * message = content of message - text
 * pushName = Sender Name like contact name - string
 * groupId = Group ID if message from group - string
 * groupSubject = Group Name - string
 * timestamp = time send message - integer
 * image = name of the image file when receiving image message
 */
// $this->startRes = time();

$subUrl = str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$subUrl = str_replace("native/", "", $subUrl);
// echo $subUrl;

// echo "http://".$_SERVER['HTTP_HOST'].$subUrl."API/Whatsapp/webhook";die();

$curl = curl_init();
$token = "";
if (!empty($_POST)) {
	$data =$_POST;
} else {
	$data = [];
}

curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: $token",
    )
);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_URL, "http://".$_SERVER['HTTP_HOST'].$subUrl."API/Whatsapp/webhook");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($curl);
curl_close($curl);


print_r($result);


$saveData = $_POST;
	file_put_contents('log/'.date("dmY"),json_encode($saveData).PHP_EOL, FILE_APPEND);
file_put_contents('log/'.date("dmYResult"),json_encode($result).PHP_EOL, FILE_APPEND);
	// echo "mantap ";
