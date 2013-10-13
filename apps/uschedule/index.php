<?php
$ch = curl_init("http://web-app.usc.edu/ws/soc/api/classes/phys/20133");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
var_dump($result);
var_dump(json_decode($result, true)['OfferedCourses']['course']);
?>