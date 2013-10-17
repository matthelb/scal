<?php
require_once('../lib/functions.php');

if (isset($_POST['section']) && isset($_POST['course']) && isset($_POST['semester'])) {
	$section = $_POST['section'];
	$course = $_POST['course'];
	$semester = $_POST['semester'];
	session_start();
	if (!isset($_SESSION['sections'])) {
		$_SESSION['sections'] = array();
	}
	if (!isset($_SESSION['sections'][$semester])) {
		$_SESSION['sections'][$semester] = array();
	}
	$s = get_section($section, $course, $semester);
	$success = true;
	foreach($_SESSION['sections'][$semester] as $section2) {
		if (strcmp($section2->id, $s->id) == 0) {
			$success = false;
		}
	}
	if ($success) {
		array_push($_SESSION['sections'][$semester], $s);
	}
	echo json_encode(array('success' => $success, 'section' => $s));
}
?>