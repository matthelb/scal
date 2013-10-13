<?php
require_once('../lib/functions.php');
session_start();
$client = new Google_Client();
$cal = new Google_CalendarService($client);

if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
	if (isset($_SESSION['sections'])) {
		$urls = array();
		foreach($_SESSION['sections'] as $section) {
			if ($section) {
				array_push($urls, add_section_to_calendar($cal, $section));
			}
		}
		echo json_encode($urls);
	}
	$_SESSION['token'] = $client->getAccessToken();
} else {
	$authUrl = $client->createAuthUrl();
	header('Location: ' . $authUrl);
}
?>