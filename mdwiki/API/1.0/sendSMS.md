Send sms Version 1.0
=====


Send Message
--------


Send a message to a new or existing chat. The message will be added to the queue to send and send even if the phone is disconnected from the Internet or authorization is not passed. This function serves to send information, or notification messages to the user




Request parameters 
------------

|Name      |Detail|
|----------|------------|
| **Type** | POST                   |
| **URL**  | API/SMS/sendMessage |

### **Header**

|Key            | Validation | Value     |
|---------------|------------|           |
| Authorization | Required   | Your Token|

#### **Body**

| Key    |Validation|Value|
|--------|----------|-----|
| phone  | Required |  Target phone number. You can use the country code prefix or not. Example: 081223xx|
| message| Required | Text message to be sent. Format: UTF-8 or UTF-16 string. for single newline (\n), double newline (\n2) |
| save_hotline| optional | Save message to hotline message |

Example
-----------------

```bash
<?php

$curl = curl_init();
$param = http_build_query([
            "phone" => "08569*****",
            "message" => "hello",
            "save_hotline" = "1",
        ]);

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://wakita.id/API/SMS/sendMessage",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $param,
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

```

Result
-----------------

|Code      | Desc|
|----------|------------|
| **501**  | "invalid Authorization token"|
| **502**  | "invalid parameter" |
| **503**  | "Authorization token error" |
| **500**  | "Unknown error" |
| **200**  | "Create Record Success" |


```json

{
  "result": "success",
  "code": "200",
  "message": "Create Record Success",
  "data": [
    {
      "id": "5da57078c2666e3b1c01f832",
      "phone": "62856******",
      "text": "hello ini sms",
    }
  ]
}

```
