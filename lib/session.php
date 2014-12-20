<?php
require_once('functions.php');

class Session {
	public $term;
	public $id;
	public $firstDayClasses;
	public $lastDayAdd;
	public $lastDayDropWithW;
	public $end;

	public function __construct($json_object) {
		$this->term = $json_object['term'];
		$this->id = $json_object['session'];
		$this->firstDayClasses = $json_object['first_day_of_classes'];
		$this->lastDayAdd = $json_object['last_day_to_add'];
		$this->lastDayDropWithW = $json_object['last_day_to_drop_with_w'];
		$this->end = $json_object['end_of_session'];
	}

	public function getTerm() {
		return $this->term;
	}

	public function getId() {
		return $this->id;
	}

	public function getFirstDayOfClasses() {
		return $this->firstDayClasses;
	}
	
	public function getLastDayToAdd() {
		return $this->lastDayAdd;
	}

	public function getLastDayToDropWithW() {
		return $this->lastDayDropWithW;
	}

	public function getEnd() {
		return $this->end;
	}

	public static function forId($id, $semester) {
		return new Session(get_session($id, $semester));
	}
}
?>