<?php require_once("db_populate.php"); ?>
<html lang="en">
	<head>
		<title>SCal to Google</title>
	</head>
	<body>
		<form action ="index3.php">
		<select id="department">
		  <option value="">--</option>
		  
		  <?php populate_dpts(); ?>
		</select><br />
		<?php //populate_dpts2(); ?>
		<select id="course">
		  <option value="">--</option>
		  <?php populate_courses(); ?>
		</select><br />
		<select id="section">
			<?php populate_sections(); ?>
		</select><br />
		<input type="submit" name="go" value="submit">
		</form>
		<script src="jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="jquery.chained.mini.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$("#course").chained("#department"); /* or $("#series").chainedTo("#mark"); */
			$("#section").chained("#course");
		</script>
		
	</body>
</html>