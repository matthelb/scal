<?php
session_start();
$success = false;
if (isset($_POST['semester'])) {
	unset($_SESSION['sections'][$_POST['semester']]);
	$success = true;
}
echo json_encode(array('success' => $success));
?>