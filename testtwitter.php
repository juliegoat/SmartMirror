<?php
require "apikeys.php";
$key = $twitter_apikey;
$secret = $twitter_secret;
$api_endpoint = 'https://api.twitter.com/1.1/search/tweets.json?q=smartmirrorthesis&src=typd'; // endpoint must support "Application-only authentication"

// request token
$basic_credentials = base64_encode($key.':'.$secret);
$tk = curl_init('https://api.twitter.com/oauth2/token');
curl_setopt($tk, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$basic_credentials, 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'));
curl_setopt($tk, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
$token = json_decode(curl_exec($tk));
curl_close($tk);

// use token
if (isset($token->token_type) && $token->token_type == 'bearer') {
	$br = curl_init($api_endpoint);
	curl_setopt($br, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token->access_token));
	curl_setopt($br, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($br);
	curl_close($br);
  
	print($data);
}

?>