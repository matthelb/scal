<?php
require_once('course.php');
require_once('department.php');
require_once('google/Google_Client.php');
require_once('google/contrib/Google_CalendarService.php');

define('API_ROOT', 'http://web-app.usc.edu/ws/soc/api/');
define('API_COURSES', API_ROOT . 'classes/%s/%d/');
define('API_DEPARTMENTS', API_ROOT . 'depts/%d/');
define('API_TERMS', API_ROOT . 'terms/');
define('API_SESSIONS', API_ROOT . 'session/%s/%d');

function get_all_courses($dept, $semester) {
	$json_object = get_json(sprintf(API_COURSES, $dept, $semester));
	$courses = array();
	$json_object = json_decode($json_object, true);
	if(array_key_exists('course', $json_object['OfferedCourses'])) {
		foreach ($json_object['OfferedCourses']['course'] as $course) {
			array_push($courses, new Course($course, $semester));
		}
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

function get_all_sections($course, $semester) {
	foreach (get_all_courses(substr($course, 0, strpos($course, '-')), $semester) as $c) {
		if (strcmp($c->getId(), $course) == 0) {
			return $c->getSections();
		}
	}
	return null;
}

function get_section($section, $course, $semester) {
	foreach(get_all_sections($course, $semester) as $s) {
		if (strcmp($section, $s->getId()) == 0) {
			return $s;
		}
	}
	return null;
}

function get_session($id, $semester) {
	$json_object = get_json(sprintf(API_SESSIONS, $id, $semester));
	return json_decode($json_object, true);
}

function get_all_terms() {
	$json_object = get_json(API_TERMS);
	return json_decode($json_object, true)['terms'];
}

function add_section_to_calendar($cal, $section) {
	$urls = array();
	foreach($section->toCalendarEvents() as $event) {
		array_push($urls, $cal->events->insert('primary', $event)['htmlLink']);
	}
	return $urls;
}

function get_json($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
    return $result;
}
?>