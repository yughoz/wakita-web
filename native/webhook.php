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

$curl = curl_init();
$token = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
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
curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1/wablas/API/Whatsapp/webhook");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($curl);
curl_close($curl);


print_r($result);


$saveData = $_POST;
	file_put_contents('log/'.date("dmY"),json_encode($saveData).PHP_EOL, FILE_APPEND);

	// echo "mantap ";
