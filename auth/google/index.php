<?php
require_once('../../config.php');
require_once('../../lib/google-api-php-client/src/Google/Client.php');
session_start();
require_once('../../lib/google-api-php-client/src/Google/Service/' . $_SESSION['authorization']['service'] . '.php');

$client = new Google_Client();
$client->setApplicationName("SCal");
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/auth/google/');
$client->addScope('https://www.googleapis.com/auth/calendar');

$serviceClass = 'Google_Service_' . $_SESSION['authorization']['service'];
$service = new $serviceClass($client);
if (isset($_GET['code'])) {
	$client->authenticate($_GET['code']);
	$_SESSION['token'] = $client->getAccessToken();
}
if (isset($_SESSION['token'])) {
	header('Location: ' . $_SESSION['authorization']['redirect']);
} else {
	echo json_encode(array('success' => false, 'auth_url' => $client->createAuthUrl()));
}
?>
