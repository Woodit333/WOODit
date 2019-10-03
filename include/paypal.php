<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sandbox.paypal.com/v2/checkout/orders",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{
  "intent": "CAPTURE",
  "purchase_units": [
    {
      "reference_id": "PUHF",
      "amount": {
        "currency_code": "USD",
        "value": "100.00"
      }
    }
  ],
  "application_context": {
    "return_url": "",
    "cancel_url": ""
  }
}',
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'accept-language: en_US',
    'authorization: Bearer A21AAFJ9eoorbnbVH3fTJrCTl2o7-P_1T6q8vdYB_QwBB9Ais5ZZmJD4BsNjIiOh8j8OyOcfzLO1BKcgKe0pK-mntpk6jOm-',
    'content-type: application/json'
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