<?php
require_once('../lib/functions.php');

if (isset($_POST['course'])) {
	$course = $_POST['course'];
	echo json_encode(get_all_sections($course, 20133));
}
?>