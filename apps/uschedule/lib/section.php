<?php
require_once('instructor.php');

class Section {
	private $course;
	private $start;
	private $end;
	private $days;
	private $location;
	private $instructor;

	public function __construct($course, $json_object) {
		$this->course = $course;
		$this->start = new DateTime($json_object['start_time']);
		$this->end = new DateTime($json_object['end_time'])
		$this->days = $json_object['day'];
		$this->location = $json_object['location'];
		$this->instructor = new Instructor($json_object['instructor']);
	}

	public function getCourse() {
		return $this->course;
	}

	public function getStartTime() {
		return $this->start;
	}

	public function getEndTime() {
		return $this->end;
	}

	public function getDays() {
		return $this->days;
	}

	public function getLocation() {
		return $this->location;
	}

	public function getInstructor() {
		return $this->instructor;
	}
}
?>