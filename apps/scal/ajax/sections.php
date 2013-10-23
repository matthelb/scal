<?php
require_once('../lib/functions.php');

if (isset($_GET['course']) && isset($_GET['semester'])) {
	$course = $_GET['course'];
	$semester = $_GET['semester'];
	echo json_encode(get_all_sections($course, $semester));
}
?>