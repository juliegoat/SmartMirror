<?php
require "apikeys.php";
define('CONSUMER_KEY', $twitter_apikey);
define('CONSUMER_SECRET', $twitter_secret);
/**
*	Get the Bearer Token, this is an implementation of steps 1&2
*	from https://dev.twitter.com/docs/auth/application-only-auth
*/
function get_bearer_token(){
	// Step 1
	// step 1.1 - url encode the consumer_key and consumer_secret in accordance with RFC 1738
	$encoded_consumer_key = urlencode(CONSUMER_KEY);
	$encoded_consumer_secret = urlencode(CONSUMER_SECRET);
	// step 1.2 - concatinate encoded consumer, a colon character and the encoded consumer secret
	$bearer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
	// step 1.3 - base64-encode bearer token
	$base64_encoded_bearer_token = base64_encode($bearer_token);
	// step 2
	$url = "https://api.twitter.com/oauth2/token"; // url to send data to for authentication
	$headers = array( 
		"POST /oauth2/token HTTP/1.1", 
		"Host: api.twitter.com", 
		"User-Agent: Twitter Application-only OAuth App v.1",
		"Authorization: Basic ".$base64_encoded_bearer_token,
		"Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
	); 
	$ch = curl_init();  // setup a curl
	curl_setopt($ch, CURLOPT_URL,$url);  // set url to send to
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
	curl_setopt($ch, CURLOPT_POST, 1); // send as post
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return output
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); // post body/fields to be sent
	$header = curl_setopt($ch, CURLOPT_HEADER, 1); // send custom headers
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$retrievedhtml = curl_exec ($ch); // execute the curl
	curl_close($ch); // close the curl
	$output = explode("\n", $retrievedhtml);
	//print($retrievedhtml);
	$bearer_token = '';
	foreach($output as $line)
	{
		if($line === false)
		{
			// there was no bearer token
		}else{
			$bearer_token = $line;
		}
	}
	$bearer_token = "AAAAAAAAAAAAAAAAAAAAAGRY3gAAAAAAgEMEuhnzmT7BkZP5VUwr5fk%2Bcpw%3D4LlDgeq664cy06WK1JPRzzm5hjqsnLGqc0Fo5r7wJP2HMV45gb";
	//print($bearer_token);
	return $bearer_token->{'access_token'};
}
/**
* Search
* Basic Search of the Search API
* Based on https://dev.twitter.com/docs/api/1.1/get/search/tweets
*/
function search_for_a_term($bearer_token, $result_type='mixed', $count='3'){
	$url = "https://api.twitter.com/1.1/search/tweets.json?q=smartmirrorthesis&src=typd"; // base url
	if($result_type!='mixed'){$url = $url.'&result_type='.$result_type;} // result type - mixed(default), recent, popular
	if($count!='3'){$url = $url.'&count='.$count;} // results per page - defaulted to 15
	$url = $url.'&include_entities=true'; // makes sure the entities are included, note @mentions are not included see documentation
	print($url);
	$headers = array( 
		"GET /1.1/search/tweets.json".$url." HTTP/1.1", 
		"Host: api.twitter.com", 
		"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
		"Authorization: Bearer ".$bearer_token
	);
	$ch = curl_init();  // setup a curl
	curl_setopt($ch, CURLOPT_URL,$url);  // set url to send to
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return output
	$retrievedhtml = curl_exec ($ch); // execute the curl
	curl_close($ch); // close the curl
	print($retrievedhtml);
	return $retrievedhtml;
}
// lets run a search.
$bearer_token = get_bearer_token(); // get the bearer token
print search_for_a_term($bearer_token);
?>