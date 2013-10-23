<?php
require_once('google/Google_Client.php');
require_once('google/contrib/Google_CalendarService.php');
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
			$instructors = $json_object['instructor'];
			if(isset($instructors[0])) {
				foreach ($instructors as $instructor) {
					array_push($this->instructor, new Instructor($instructor));
				}

			} else {
				array_push($this->instructor, new Instructor($json_object['instructor']));
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
		$event = new Google_Event();
		$event->setSummary($this->getCourse()->getId() . ' - ' . $this->getCourse()->getTitle());
		$event->setLocation($this->getLocation());
		$event->setDescription($this->getCourse()->getDescription());
		return $event;
	}

	public function toCalendarEvents() {
		$sessionObject = $this->getSessionObject();
		$events = array();
		if ($this->getDays()) {
			foreach (str_split($this->getDays()) as $day) {
				$start = new Google_EventDateTime();
				$start->setTimeZone(DEFAULT_TZ);
				$end = new Google_EventDateTime();
				$end->setTimeZone(DEFAULT_TZ);	
				$event = $this->getBaseEvent();
				$date = new DateTime($sessionObject->getFirstDayOfClasses());
				$offset = date('N', $date->getTimestamp()) - 1;
				$date->modify(sprintf('+%d days', Section::getDayOffset($day) - $offset));

				$startTime = $this->getStartTime();
				if ($startTime) {
					$date->modify($this->getStartTime());
					$start->setDateTime($date->format('Y-m-d\TH:i:s.u'));
					$event->setStart($start);	

					$date->modify($this->getEndTime());
					$end->setDateTime($date->format('Y-m-d\TH:i:s.u'));
					$event->setEnd($end);
				} else {
					$start->setDate($date->format('Y-m-d'));
					$end->setDate($date->format('Y-m-d'));
					$event->setStart($start);
					$event->setEnd($end);
				}
				$endDate = new DateTime($sessionObject->getEnd());
				$endDate->setTimeZone(new DateTimeZone(DEFAULT_TZ));
				$endDate->modify('-14 day');
				$event->setRecurrence(array('RRULE:FREQ=WEEKLY;UNTIL=' . $endDate->format('Ymd\THms\Z')));
				array_push($events, $event);
			}
		} else {
			$start = new Google_EventDateTime();
			$start->setTimeZone(DEFAULT_TZ);
			$end = new Google_EventDateTime();
			$end->setTimeZone(DEFAULT_TZ);	
			$event = $this->getBaseEvent();
			$date = new DateTime($sessionObject->getFirstDayOfClasses());
			$start->setDate($date->format('Y-m-d'));
			$end->setDate($date->format('Y-m-d'));
			$event->setStart($start);
			$event->setEnd($end);
			$endDate = new DateTime($sessionObject->getEnd());
			$endDate->setTimeZone(new DateTimeZone(DEFAULT_TZ));
			$endDate->modify('-14 day');
			$event->setRecurrence(array('RRULE:FREQ=WEEKLY;UNTIL=' . $endDate->format('Ymd\THms\Z')));
			array_push($events, $event);
		}
		return $events;
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
}
?>