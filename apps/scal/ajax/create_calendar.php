<?php
require_once('../lib/functions.php');
session_start();
$client = new Google_Client();
$cal = new Google_CalendarService($client);

if (isset($_POST['code']) && !isset($_SESSION['token'])) {
  $client->authenticate($_POST['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken() && isset($_POST['semester'])) {
	if (isset($_SESSION['sections']) && isset($_SESSION['sections'][$_POST['semester']])) {
		$urls = array();
		foreach($_SESSION['sections'][$_POST['semester']] as $section) {
			if ($section) {
				array_push($urls, add_section_to_calendar($cal, $section));
			}
		}
		echo json_encode(array('success' => true, 'urls' => $urls));
	}
	$_SESSION['token'] = $client->getAccessToken();
} else {
	$authUrl = $client->createAuthUrl();
	echo json_encode(array(
  		'success' => false,
  		'login_url' => $authUrl 
    ));
}
?>