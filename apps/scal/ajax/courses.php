<?php
require_once('../lib/functions.php');

if (isset($_POST['dept'])) {
	$dept = $_POST['dept'];
	echo json_encode(get_all_courses($dept, 20133));
}
?>