<?php
require_once('../lib/functions.php');

if (isset($_POST['semester'])) {
	$semester = $_POST['semester'];
	echo json_encode(get_all_departments($semester));
}
?>