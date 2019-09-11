<?php
$curl = curl_init();
$token = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
$data = [
    'phone' => '6285693784939',
    'caption' => 'testwoke', // can be null
    'image' => 'http://149.129.248.52/wablas/assets/doc_wa/1565596742460.doc',
    'document' => 'http://149.129.248.52/wablas/assets/doc_wa/1565596742460.doc',
];

curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: $token",
    )
);
curl_setopt($curl, CURLOPT_URL, "https://wablas.com/api/send-document");
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($curl);
curl_close($curl);

echo "<pre>";
print_r($result);
