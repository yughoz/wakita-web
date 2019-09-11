<?php
$curl = curl_init();

$phoneArr = ['6285693784939','628980530042'];
$token = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
$result = [];
foreach ($phoneArr as $key => $value) {
	$data = [
	    'phone' => $value,
	    'message' => 'hellow world mas ,pak ,om tante'.date("H:i:s"),
	];

	curl_setopt($curl, CURLOPT_HTTPHEADER,
	    array(
	        "Authorization: $token",
	    )
	); 
	
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($curl, CURLOPT_URL, "https://wablas.com/api/send-message");
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	$result[] = curl_exec($curl);

}

	curl_close($curl);
echo "<pre>";
print_r($result);