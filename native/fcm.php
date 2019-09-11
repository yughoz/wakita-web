<?php 

$data = json_encode([
    // "to" => 'egVqgonXXHM:APA91bEPXbTBW2iW16UQHQOyXI0f-Yfb6HcRX6Q2_7BYs6vZ_Y7JBzUmH8JDPITPRSyQt-t0JKGKRK64dqgvBFAEuXjfw4hKekS6MDZmRFEPjC9AfwZZBg0ZfDJI-z3PWQ3N7JK8P6WJ',
    // "to" => 'cYhsZSacooo:APA91bEWIS4j_C1SXLMx1uLQaw_0bzzE-SCiS6j2u7ruWnT-REQo20yNhy0pbqYUD76-KwBclQTB7K475SzgYxRyxRfqfjq3P7WEgPSmy0vDJ8wCVojBVvpZiZjwtZkHZXQ0LOGV9Pxo',
    
    'to'=>'/topics/huawei_cloud',
    'priority'=>'high',
    "notification" => [
        "body" => "ini body ",
        "title" => "ini tile",
        "icon" => "ic_launcher"
    ],
    "data" => [
        "data1" =>"ANYTHING EXTRA HERE"
    ]
]);
//FCM API end-point
$url = 'https://fcm.googleapis.com/fcm/send';
//api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
$server_key = 'AIzaSyC3rX7bePdEZnRLZJ_asuCdBEDqp6BrFUI';
//header with content_type api key
$headers = array(
    'Content-Type:application/json',
    'Authorization:key='.$server_key
);
//CURL request to route notification to FCM connection server (provided by Google)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Oops! FCM Send Error: ' . curl_error($ch));
}
curl_close($ch);

echo print_r($result);
?>