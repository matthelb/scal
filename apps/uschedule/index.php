<?php
	/*$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL,'http://web-app.usc.edu/ws/soc/api/depts/20133');
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	$result = curl_exec($curl);
	curl_close($curl);
	$classes = json_decode($result,true);
	var_dump($classes);
	for($i=0;$i<sizeof($classes['department']);$i++){
		echo $classes['department'][$i]['name'];
		echo "<br />";
	}*/
	$curl2 = curl_init();
	curl_setopt($curl2, CURLOPT_URL, 'http://web-app.usc.edu/ws/soc/api/classes/csci/20133');
	curl_setopt($curl2,CURLOPT_RETURNTRANSFER,true);
	$result2 = curl_exec($curl2);
	curl_close($curl2);
	$classes2 = json_decode($result2, true);
	var_dump($classes2['OfferedCourses']['course']);
	for($i=0;$i<sizeof($classes2['OfferedCourses']['course']);$i++){
		for($j=0;$j<sizeof($classes2['OfferedCourses']['course'][$i]['CourseData']);$j++){
		echo $classes2['OfferedCourses']['course'][$i]['CourseData']['title'];
		//echo $classes2['OfferedCourses']['course'][$i]['CourseData']['SectionData'][$j];
		echo "<br />";
	}
}
?>