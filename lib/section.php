<?php
require(__DIR__ . '/../vendor/autoload.php');
require_once('instructor.php');
require_once('course.php');
require_once('session.php');

define('DEFAULT_TZ', 'America/Los_Angeles');

class Section {
	public $id;
	public $course;
	public $title;
	public $description;
	public $start;
	public $end;
	public $days;
	public $location;
	public $type;
	public $instructor;
	public $session;
	public $dayOffsets;
	public $timeSlot;
	public $timeSlots;
	private $sessionObject;

	public function __construct($json_object, Course $course) {
			$this->id = $json_object['id'];
			$this->course = $course;
			$this->start = @$json_object['start_time'];
			$this->end = @$json_object['end_time'];
			$this->days = @$json_object['day'];
			$this->days = (is_string($this->days)) ? $this->days : null;
			$this->location = @$json_object['location'];
			$this->type = @$json_object['type'];
			$this->instructor = array();
			$instructors = @$json_object['instructor'];
      if (is_array($this->start)) {
        $this->start = $this->start[0];
        $this->end = $this->end[0];
      }
			if(isset($instructors[0])) {
				foreach ($instructors as $instructor) {
					array_push($this->instructor, new Instructor($instructor));
				}
			} else {
				$instructor = array_key_exists('instructor', $json_object) ? $instructors : array();
				array_push($this->instructor, new Instructor($instructor));
			}
			$this->session = $json_object['session'];
			if (array_key_exists('section_title', $json_object)) {
				if (is_string($json_object['section_title'])) {
					$this->title = $json_object['section_title'];
				}
			}
			if (array_key_exists('section_description', $json_object)) {
				if (is_string($json_object['section_description'])) {
					$this->description = $json_object['section_description'];
				}
			}
			$this->dayOffsets = array();
			$days = str_split($this->getDays());
			foreach ($days as $day) {
				array_push($this->dayOffsets, Section::getDayOffset($day));
			}
			if (!empty($this->getStartTime()) && strcmp($this->getStartTime(), 'TBA') !== 0) {
				$date = new DateTime();
				$date->modify($this->getStartTime());
				$hour = intval($date->format('G')) + intval($date->format('i')) / 60;
				$this->timeSlot = $hour / 0.5;
				$date->modify($this->getEndTime());
				$this->timeSlots = ((intval($date->format('G')) + (intval($date->format('i')) + 10) / 60) - $hour) / 0.5;
			}
	}

	public function getId() {
		return $this->id;
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

	public function getRFCDays() {
		$str = '';
		foreach (str_split($this->getDays()) as $day) {
			$str .= Section::toRFCDay($day) . ',';
		}
		if (substr($str, -1) == ',') {
			$str = substr($str, 0, -1);
		}
		return $str;
	}

	public function getLocation() {
		return $this->location;
	}

	public function getInstructor() {
		return $this->instructor;
	}

	public function getSession() {
		return $this->session;
	}

	public function getSessionObject() {
		if (!$this->sessionObject) {
			$this->sessionObject = Session::forId($this->getSession(), $this->getCourse()->getSemester());
		}
		return $this->sessionObject;
	}

	private function getBaseEvent() {
		$event =  new Google_Service_Calendar_Event();
		$event->setSummary($this->getCourse()->getId() . ' - ' . $this->getCourse()->getTitle());
		$event->setLocation($this->getLocation());
		$instructorName = $this->getInstructor() ? ($this->getInstructor()[0] ? $this->getInstructor()[0]->getFullName() : 'TBA') : 'TBA';
		$event->setDescription(
			'Instructor: ' . $instructorName . "\n" .
			$this->getCourse()->getDescription()
		);
		return $event;
	}

	private function getSCalId() {
		return base64_encode(implode(';', array($this->getSessionObject()->getTerm(), $this->getCourse()->getId(), $this->getId())));
	}


	public function toCalendarEvent() {
		$sessionObject = $this->getSessionObject();
		$event = $this->getBaseEvent();
		$start = new Google_Service_Calendar_EventDateTime();
		$start->setTimeZone(DEFAULT_TZ);
		$end = new Google_Service_Calendar_EventDateTime();
		$end->setTimeZone(DEFAULT_TZ);
		$date = new DateTime($sessionObject->getFirstDayOfClasses());
		$byDay = $this->getDays() ? 'BYDAY=' . $this->getRFCDays() . ';' : '';
		$offset = date('N', $date->getTimestamp()) - 1;
		$day = $this->getDays()[0];
		$date->modify(sprintf('+%d days', Section::getDayOffset($day) - $offset));
		if ($this->getStartTime()) {
			$date->modify($this->getStartTime());
			$start->setDateTime($date->format('Y-m-d\TH:i:s.u'));
			$date->modify($this->getEndTime());
			$end->setDateTime($date->format('Y-m-d\TH:i:s.u'));
		} else {
			$start->setDate($date->format('Y-m-d'));
			$end->setDate($date->format('Y-m-d'));
		}
		$event->setStart($start);
		$event->setEnd($end);
		$endDate = new DateTime($sessionObject->getEnd());
    $endDate->setTimezone(new DateTimeZone(DEFAULT_TZ));
		$endDate->modify('-11 days');
    $startTime = $this->getStartTime();
    $exFmt = function ($dt) use ($startTime) {
      $dt->modify($startTime);
      $dt->setTimezone(new DateTimeZone('UTC'));
      error_log($dt->format('Ymd\THis\Z P'));
      return $dt->format('Ymd\THis\Z');
    };
		$event->setRecurrence(array(
          'RRULE:FREQ=WEEKLY;' . $byDay . 'UNTIL=' . $endDate->format('Ymd\THis\Z'),
          'EXDATE;TZID=' . DEFAULT_TZ . ':' . implode(',', array_map($exFmt, $sessionObject->getExcludedDates(new DateTimeZone(DEFAULT_TZ)))),
        )
      );
		$properties = new Google_Service_Calendar_EventExtendedProperties();
		$properties->setPrivate(array('scal'=>$this->getSCalId()));
		$event->setExtendedProperties($properties);
		return $event;
	}

	public static function getDayOffset($day) {
		$days = array('M', 'T', 'W', 'H', 'F');
		for ($i = 0; $i < 5; $i++) {
			if (strcmp($day, $days[$i]) == 0) {
				return $i;
			}
		}
		return 0;
	}

	public static function toRFCDay($day) {
		return array(
			'M' => 'MO',
			'T' => 'TU',
			'W' => 'WE',
			'H' => 'TH',
			'F' => 'FR'
		)[$day];
	}
}
?>
