<?php
require_once('../lib/functions.php');

if (isset($_POST['course']) && isset($_POST['semester'])) {
	$course = $_POST['course'];
	$semester = $_POST['semester'];
	echo json_encode(get_all_sections($course, $semester));
}
?>