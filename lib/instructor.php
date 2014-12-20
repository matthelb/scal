<?php
class Instructor {
	public $first;
	public $last;
	public $full_name;

	public function __construct($json_object) {
		$this->first = $json_object['first_name'];
		$this->last = $json_object['last_name'];
		$this->full_name = ($this->first) ? ($this->first . ' ' . $this->last) : 'TBA';
	}

	public function getFirstName() {
		return $this->first;
	}

	public function getLastName() {
		return $this->last;
	}

	public function getFullName() {
		return $this->full_name;
	}
}
?>
