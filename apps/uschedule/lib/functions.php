<?php
require_once('course.php');
require_once('department.php');
require_once('google/Google_Client.php');
require_once('google/contrib/Google_CalendarService.php');

define('API_ROOT', 'http://web-app.usc.edu/ws/soc/api/');
define('API_COURSES', API_ROOT . 'classes/%s/%d/');
define('API_DEPARTMENTS', API_ROOT . 'depts/%d/');
define('API_TERMS', API_ROOT . 'terms/');
define('API_SESSIONS', API_ROOT . 'sessions/%s/%d');

function get_all_courses($dept, $semester) {
	$json_object = get_json(sprintf(API_CLASSES, $dept, $semester));
	$courses = array();
	$json_object = json_decode($json_object, true);
	foreach ($json_object['OfferedCourses']['course'] as $course) {
		array_push($courses, new Course($course));
	}
	return $courses;
}

function get_all_departments($semester) {
	$json_object = get_json(sprintf(API_DEPARTMENTS, $semester));
	$departments = array();
	$json_object = json_decode($json_object, true);
	foreach($json_object['department'] as $department) {
		array_push($departments, new Department($department));
	}
	return $departments;
}

function get_all_terms() {
	$json_object = get_json(API_TERMS);
	return json_decode($json_object)['terms'];
}

function get_json($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
    return $result;
}
?>