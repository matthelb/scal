<?php
require_once('../lib/functions.php');
session_start();
$client = new Google_Client();
$cal = new Google_Service_Calender($client);
$semester = array_key_exists('semester', $_POST) ? $_POST['semester'] : null;

if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
} else {
	$_SESSION['authorization']['redirect'] = $_SERVER['HTTP_REFERER'];
}

if ($client->getAccessToken() && $semester) {
	if (isset($_SESSION['sections']) && isset($_SESSION['sections'][$semester])) {
		$urls = array();
		$calendar = get_or_create_calendar($cal, $semester);
		foreach($_SESSION['sections'][$semester] as $section) {
			if ($section) {
				array_push($urls, add_section_to_calendar($cal, $calendar, $section));
			}
		}
		if (isset($_SESSION['authorization'])) {
			unset($_SESSION['authorization']);
		}
		echo json_encode(array('success' => true, 'urls' => $urls));
	}
	$_SESSION['token'] = $client->getAccessToken();
} else {
	$_SESSION['authorization']['service'] = 'Calendar';
	header('Location: http://' . $_SERVER['HTTP_HOST'] .
		((strcmp($_SERVER['HTTP_HOST'], 'localhost') == 0) ?
		substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/', 1)) : '' ). '/auth/google/');
}
?>
