<?php
class Instructor {
	public $first;
	public $last;

	public function __construct($json_object) {
		$this->first = $json_object['first_name'];
		$this->last = $json_object['last_name'];
	}

	public function getFirstName() {
		return $this->first;
	}

	public function getLastName() {
		return $this->last;
	}
}
?>