<?php
require_once('../lib/functions.php');

if(isset($_POST['sectionData'])) {
  $res['sections'] = array();
  $sectionData = $_POST['sectionData'];
  $semester = $sectionData['semester'];
  $sectionList = $sectionData['sectionList'];
  session_start();
  if (!isset($_SESSION['sections'])) {
    $_SESSION['sections'] = array();
  }
  if (!isset($_SESSION['sections'][$semester])) {
    $_SESSION['sections'][$semester] = array();
  }
  foreach($sectionList as $section) {
    $id = $section['section'];
    $course = $section['course'];
    $success = true;

    foreach($_SESSION['sections'][$semester] as $section2) {
      if($section2 != null && strcmp($section2->id, $id) == 0) {
        $success = false;
        break;
      }
    }

    if($success) {
      $s = get_section($id, $course, $semester);
      array_push($_SESSION['sections'][$semester], $s);
      array_push($res['sections'], $s);
    }
  }
  $res['success'] = true;
  echo json_encode($res);
}

?>