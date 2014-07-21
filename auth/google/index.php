<?php
require_once('../../lib/google/Google_Client.php');
session_start();
require_once('../../lib/google/contrib/' . $_SESSION['authorization']['service'] . '.php');

$client = new Google_Client();
$service = new $_SESSION['authorization']['service']($client);
if (isset($_GET['code'])) {
	$client->authenticate($_GET['code']);
	$_SESSION['token'] = $client->getAccessToken();
} 
if (isset($_SESSION['token'])) {
	header('Location: ' . $_SESSION['authorization']['redirect'] . '?auth=1');
} else {
	echo json_encode(array('success' => false, 'auth_url' => $client->createAuthUrl()));
}
?>