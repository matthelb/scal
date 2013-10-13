<?php
require_once('../lib/functions.php');

if (isset($_POST['section']) && isset($_POST['course'])) {
	$section = $_POST['section'];
	$course = $_POST['course'];
	session_start();
	if (!isset($_SESSION['sections'])) {
		$_SESSION['sections'] = array();
	}
	$s = get_section($section, $course, 20133);
	array_push($_SESSION['sections'], $s);
	echo json_encode($s);
}
?>