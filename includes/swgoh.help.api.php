<?php

function get_token(){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.swgoh.help/auth/signin',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'username=Morgs27&password=Morg%3D2708&grant_type=password&client_id=abc&client_secret=123',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: connect.sid=s%3A1zQAkVvKLSrCA_dSauSIFf8ZnF83eePI.15EgDM2DWGbdldJZDu43OXIkyd%2BRE2tBWClDU50onec'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response);
if (isset($response->error)){
	return null;
}
else {
return $response->access_token;
}
}


function get_player_data($allyCodes){
	$curl = curl_init();
	$token = get_token();
	if ($token == null){
		return null;
	}
	else {
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.swgoh.help/swgoh/player',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"allycodes": '.$allyCodes.',
		"languege":"eng_us",
		"enums": false,
		"structure": false
	}',
	CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer ' .$token,
		'Content-Type: application/json',
		'Cookie: connect.sid=s%3A1zQAkVvKLSrCA_dSauSIFf8ZnF83eePI.15EgDM2DWGbdldJZDu43OXIkyd%2BRE2tBWClDU50onec'
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
	}
}


$allycodes = $_POST['allycodes'];

$data = get_player_data($allycodes);

print_r($data);

