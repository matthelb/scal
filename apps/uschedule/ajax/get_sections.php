<?php
require_once('../lib/functions.php');
session_start();
if (isset($_SESSION['sections'])) {
	echo json_encode($_SESSION['sections']);
}
?>