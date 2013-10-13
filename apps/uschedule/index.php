<?php require_once("db_populate.php"); ?>
<html lang="en">
<head>
	<title>SCal to Google</title>
	<style type="text/css">
		body {
			background-color: #FFFF66;
			background-image:url('stripes.jpg'); }
		</style>
	</head>
	<div id="logo"> <img src="logo.gif"/> </div>
	<style type = "text/css">
	#logo{
		text-align: center;
		}
	</style>
	<div id="main_section">
	<body>
		<form action ="index.php">
		<style type = "text/css">
			select{
				width:500px;
				text-align:center;
			}
		</style>
			<select id="department">
				<option value="">Department</option>
				<?php populate_dpts(); ?>
			</select><br />
			<?php?>
			<select id="course">
				<option value="">Course</option>
				<?php populate_courses(); ?>
			</select><br />
			<select id="section">
				<option value ="">Section</option>
				<?php populate_sections(); ?>
			</select><br />
			<input type="submit" name="go" value="submit">
		</form>
		<script src="jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="jquery.chained.mini.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$("#course").chained("#department");
			$("#section").chained("#course");
		</script>
		
	</body>
	</div>
	<style type="text/css">
	#main_section{
		text-align:center;
	}
	</style>
	<div id="socialMedia"> 
		<div id="Facebook"> 
		<a href="http://www.facebook.com">
		<img src="fnl_facebook_logo.png" height="45" width = "90"> 
		</a>
		</div>
		</div>
	<style type="text/css">
	#socialMedia{
		text-align:bottom;
	}
	</style>
	</html>