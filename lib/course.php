<?php
require_once('section.php');

class Course {

	public $id;
	public $title;
	public $description;
	private $sections;
	public $semester;

	public function __construct($json_object, $semester) {
		$this->semester = $semester;
		$this->id = $json_object['ScheduledCourseID'];
		$this->title = $json_object['CourseData']['title'];
		$this->description = $json_object['CourseData']['description'];
		$this->sections = array();
		$sections = $json_object['CourseData']['SectionData'];
		if (isset($sections[0])) {
			foreach ($sections as $section) {
				array_push($this->sections, new Section($section, $this));
			}
		} else {
			array_push($this->sections, new Section($sections, $this));
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getSemester() {
		return $this->semester;
	}

	public function getSections() {
		return $this->sections;
	}
}
?>