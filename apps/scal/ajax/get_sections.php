<?php
require_once('../lib/functions.php');
session_start();
if (isset($_GET['semester']) && isset($_SESSION['sections']) && isset($_SESSION['sections'][$_GET['semester']])) {
	echo json_encode($_SESSION['sections'][$_GET['semester']]);
}
?>