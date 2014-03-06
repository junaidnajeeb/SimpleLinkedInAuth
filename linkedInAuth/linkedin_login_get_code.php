<?php

require_once(dirname(__FILE__) . '/config.php');

if(empty($_SESSION)){
	session_start();
}
// OAuth 2 Control Flow
if (isset($_GET['error'])) {
	// LinkedIn returned an error
	print $_GET['error'] . ': ' . $_GET['error_description'];
	exit;
} elseif (isset($_GET['code'])) {
	// User authorized your application
	if ($_SESSION['linkedIn_state'] == $_GET['state']) {
		// Get token so you can make API calls
		getAccessToken();
	} else {
		echo 'CSRF attack? Or did you mix up your states?';
		exit;
	}
} else {
	if ((empty($_SESSION['linkedIn_expires_at'])) || (time() > $_SESSION['linkedIn_expires_at'])) {
		// Token has expired, clear the state
		$_SESSION['linkedIn_access_token'] = '';
		$_SESSION['linkedIn_expires_in'] = '';
		$_SESSION['linkedIn_expires_at'] = '';
		$_SESSION['linkedIn_state'] = '';
	}
	if (empty($_SESSION['linkedIn_access_token'])) {
		// Start authorization process
		getAuthorizationCode();
	} else {
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
}

function getAuthorizationCode() {
	$params = array('response_type' => 'code',
		'client_id' => LINKEDIN_API_KEY,
		'scope' => LINKEDIN_SCOPE,
		'state' => uniqid('', true), // unique long string
		'redirect_uri' => $_SERVER['SCRIPT_URI'],
	);

	// Authentication request
	$url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);

	// Needed to identify request when it returns to us
	$_SESSION['linkedIn_state'] = $params['state'];

	// Redirect user to get access code
	header("Location: $url");
	exit;
}

function getAccessToken() {
	$params = array('grant_type' => 'authorization_code',
		'client_id' => LINKEDIN_API_KEY,
		'client_secret' => LINKEDIN_API_SECRET,
		'code' => $_GET['code'],
		'redirect_uri' => $_SERVER['SCRIPT_URI'],
	);

	// Access Token request
	$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

	// Tell streams to make a POST request
	$context = stream_context_create(
			array('http' =>
				array('method' => 'POST',
				)
			)
	);

	// Retrieve access token information
	$response = file_get_contents($url, false, $context);

	// Native PHP object, please
	$token = json_decode($response);

	// Store access token and expiration time in SESSION or in DB
	$_SESSION['linkedIn_access_token'] = $token->access_token; // guard this! 
	$_SESSION['linkedIn_expires_in'] = $token->expires_in; // relative time (in seconds)
	$_SESSION['linkedIn_expires_at'] = time() + $_SESSION['linkedIn_expires_in']; // absolute time
	
	// Redirect user to authenticate
	header("Location:".$_SERVER['HTTP_REFERER']);
}
