<?php
require_once('../lib/functions.php');

if (isset($_POST['section']) && isset($_POST['semester'])) {
	$section = $_POST['section'];
	$semester = $_POST['semester'];
	session_start();
	$found = false;
	if (isset($_SESSION['sections'][$semester])) {
		$sections = array();
		foreach ($_SESSION['sections'][$semester] as $s) {
			if ($s) {
				if (strcmp($s->id, $section) != 0) {
					array_push($sections, $s);
				} else {
					$found = true;
				}
			}
		}
	}
	$_SESSION['sections'][$semester] = $sections;
	echo json_encode(array('success' => $found));
}
?>