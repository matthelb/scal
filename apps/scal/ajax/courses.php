<?php
require_once('../lib/functions.php');

if (isset($_POST['dept']) && isset($_POST['semester'])) {
	$dept = $_POST['dept'];
	$semester = $_POST['semester'];
	echo json_encode(get_all_courses($dept, $semester));
}
?>