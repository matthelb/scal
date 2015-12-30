<?php
require_once('functions.php');

class Session {
	public $term;
	public $id;
	public $firstDayClasses;
	public $lastDayAdd;
	public $lastDayDropWithW;
	public $end;
  private $excludedDates;

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

  public function getExcludedDates($dtz) {
      $this->excludedDates = array();
      $year = substr($this->getTerm(), 0, 4);
      $semester = $this->getTerm()[4];
      switch ($semester) {
        case '1': { // Spring
          array_push($this->excludedDates, new DateTime('third monday of January ' . $year, $dtz)); // MLK Jr. Day
          array_push($this->excludedDates, new DateTime('third monday of February ' . $year, $dtz)); // Pres. Day
          // Spring Break
          array_push($this->excludedDates, new DateTime('-3 days third thursday of March ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('-2 days third thursday of March ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('-1 day third thursday of March ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('third thursday of March ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('+1 day third thursday of March ' . $year, $dtz));
          break;
        }
        case '2': { // Summer
          array_push($this->excludedDates, new DateTime('last monday of May ' . $year, $dtz)); // Memorial Day
          array_push($this->excludedDates, new DateTime('July 4th, ' . $year, $dtz)); // Indepedence Day
          break;
        }
        case '3': { // Fall
          array_push($this->excludedDates, new DateTime('first monday of September ' . $year, $dtz)); // Labor Day
          // Thanksgiving Break
          array_push($this->excludedDates, new DateTime('-1 day fourth Thursday of November ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('fourth Thursday of November ' . $year, $dtz));
          array_push($this->excludedDates, new DateTime('+1 day fourth Thursday of November ' . $year, $dtz));
          break;
        }
      }
    return $this->excludedDates;
  }
	public static function forId($id, $semester) {
		return new Session(get_session($id, $semester));
	}
}
?>
