<?php

$userGuid = "61f82d28-c9f3-4bec-be8f-cd1cc0b7cab7";
$apiKey = "AccmyJLuXl4XR8V1/ubdW+B4Bwtfr6cxuHDKNNI/jXFHPFqQI2Q6RxMtTQ4TK59Vyu1zfoN/YTeU6Me8l4+oyg==";

function aisd_menu_query($connectorGuid, $input, $userGuid, $apiKey, $additionalInput) {

  $url = "https://api.import.io/store/connector/" . $connectorGuid . "/_query?_user=" . urlencode($userGuid) . "&_apikey=" . urlencode($apiKey);

  $data = array("input" => $input);
  if ($additionalInput) {
    $data["additionalInput"] = $additionalInput;
  }

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $result = curl_exec($ch);
  curl_close($ch);

  return json_decode($result);
}

// Query for tile AISD Monthly Menu
$result = aisd_menu_query("babd048c-5a05-47ee-ba41-582923a73ef6", array(
  "webpage/url" => "http://www.austinisd.org/nutritionfoodservices/menu-information?level=elementary&language=English",
), $userGuid, $apiKey, false);
var_dump($result);
