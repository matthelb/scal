<?php

function populate_dpts() {

	$dbhost = "coursescal2.db.9627977.hostedresource.com";
	$dbuser = "coursescal2";
	$dbpass = "MMA123!k";
	$dbname = "coursescal2";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if (mysqli_connect_errno()) {
		die("Database Connection Failed ".mysqli_connect_error()." (".mysqli_connect_errno().")"
	);}
	
	$query = "SELECT * FROM departments";
		$result = mysqli_query($connection, $query);
		
	if (!$result) {
				die("Database query failed!");
			}
	
	$row = mysqli_fetch_assoc($result);
	$i = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		
		echo "<option value=\"{$i}\">";
		echo $row['code'];
		echo " - ";
		echo $row['name'];
		echo "</option>";
		$i++;
	}
	}
	
	function populate_courses() {
	

	$dbhost = "coursescal2.db.9627977.hostedresource.com";
	$dbuser = "coursescal2";
	$dbpass = "MMA123!k";
	$dbname = "coursescal2";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if (mysqli_connect_errno()) {
		die("Database Connection Failed ".mysqli_connect_error()." (".mysqli_connect_errno().")"
	);}
	
	$query = "SELECT * FROM courses";
		$result = mysqli_query($connection, $query);
		
	if (!$result) {
				die("Database query failed!");
			}
	
	$row = mysqli_fetch_assoc($result);
	
	while ($row = mysqli_fetch_assoc($result)) {
		$cID = $row['deptID']-1;
		echo "<option value=\"{$row['id']}\" class=\"{$cID}\">";
		echo $row['name'];
		echo "</option>";
	}

}
	function populate_sections() {
	

	$dbhost = "coursescal2.db.9627977.hostedresource.com";
	$dbuser = "coursescal2";
	$dbpass = "MMA123!k";
	$dbname = "coursescal2";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if (mysqli_connect_errno()) {
		die("Database Connection Failed ".mysqli_connect_error()." (".mysqli_connect_errno().")"
	);}
	
	$query = "SELECT * FROM sections";
		$result = mysqli_query($connection, $query);
		
	if (!$result) {
				die("Database query failed!");
			}
	
	$row = mysqli_fetch_assoc($result);
	
	while ($row = mysqli_fetch_assoc($result)) {
		
		echo "<option value=\"{$row['id']}\" class=\"{$row['courseID']}\">";
		echo $row['SectionID']." - ".$row['stime']." - ".$row['etime']." - ".$row['day'];
		echo "</option>";
	}

}



?>