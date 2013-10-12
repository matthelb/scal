<?php
require_once('section.php');

class Course {

	private $id;
	private $title;
	private $description;
	

	public function __construct($json_object) {
		$this->id = $json_object['ScheduledCourseID'];
		$this->title = $json_object['CourseData']['title'];
		$this->description = $json_object['CourseData']['description'];
		$this->sections = array();
		foreach ($json_object['CourseData']['SectionData'] as $section) {
			array_push($this->sections, new Section($this, $section));
		}
	}

	public function getId() {
		return $id;
	}

	public function getTitle() {
		return $title;
	}

	public function getDescription() {
		return $description;
	}
}
?>