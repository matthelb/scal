<?php
require_once('../lib/functions.php');
session_start();
$client = new Google_Client();
$cal = new Google_Service_Calendar($client);
$semester = array_key_exists('semester', $_POST) ? $_POST['semester'] : null;

if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
} else {
	$_SESSION['authorization']['redirect'] = $_SERVER['HTTP_REFERER'];
}

if ($client->getAccessToken() && $semester) {
  try {
    $calendar = retrieve_calendar($cal, $semester);
    if ($calendar == null) {
      echo json_encode(array('success' => false));
    } else {
      $sections = array();
      $_SESSION['sections'][$semester] = array();
      foreach($cal->events->listEvents($calendar->getId())->getItems() as $event) {
        $properties = $event->getExtendedProperties()->private;
        if (isset($properties['scal'])) {
          $arr = explode(';', base64_decode($properties['scal']));
          $s = get_section($arr[2], $arr[1], $arr[0]);
          $success = true;
          if (isset($_SESSION['section'][$semester])) {
            foreach($_SESSION['sections'][$semester] as $section2) {
              if (strcmp($section2->id, $s->id) == 0) {
                $success = false;
                break;
              }
            }
          }
          if ($success) {
            array_push($_SESSION['sections'][$semester], $s);
            array_push($sections, $s);
          }
        }
      }
      if (isset($_SESSION['authorization'])) {
        unset($_SESSION['authorization']);
      }
      echo json_encode(array('success' => true, 'sections' => $sections));
    }
    $_SESSION['token'] = $client->getAccessToken();
  } catch (Google_Auth_Exception $e) {
	  $_SESSION['authorization']['redirect'] = $_SERVER['HTTP_REFERER'];
    do_google_authorization();
  }
} else {
  do_google_authorization();
}
?>
