<?php

$curl = curl_init();
$data = http_build_query([
            "phone" => "085693784939",
            "message" => "hello from API Native"
        ]);

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://149.129.248.52/wablas/API/Outbox/sendMessage",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $data,
  CURLOPT_HTTPHEADER => array(
    "authorization: MmlOdnk5elVVVlN3TVhTTzcxU0l2ZE53akUyYzdEcmZWNktuM3RDUmNPdnJrTW52bDc0a3JhQ1ViaFpBSFpaTw",
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}