
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>SCal to Google</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="css/chosen.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<table border="0" align="center">
<tr>
<td>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="hero-unit">
				<h1>
					<img src="logo.gif" height="100"/>
				</h1>
				<p>
					<a class="btn btn-primary btn-large" href="#">How it works »</a>
				</p>
			</div>
			<div class="tabbable" id="tabs-533001">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#panel-350864" data-toggle="tab">Add your Classes</a>
					</li>
					<li class="active">
						<a href='#' data-toggle="tab">Clubs</a>
					</li>
					<li class="active">
						<a href='#' data-toggle="tab">My Account</a>
					</li>
					<li class="active">
						<a href='#' data-toggle="tab">Friends</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-350864">
						<p>
							<h3> Choose your classes! </h3>
							<h4> Departments: </h4>
								<?php
									require_once('lib/functions.php');
									$depts = get_all_departments(20133);
								?>
								<select id="departments" name="departments">
            						<option value="">Department</option>
            						<?php
										foreach ($depts as $dept) {
											$code = $dept->getCode();
											$name = $dept->getName();
											echo "<option value=\"$code\">$name</option>";
										}
									?>
								</select>
							<h4> Courses: </h4>
								<select id="courses" name="courses">
						            <option value="">Course</option>
								</select>
							<h4> Sections: </h4>
								<select id="sections" name="sections">
						            <option value="">Section</option>
						        </select>
						     <br/>
							<a id="add-section" class="btn btn-primary btn-large" href="#">Add »</a>
						</p>
						<p>
							<h3> View all your classes! </h3>
                            <ul id="my-sections"></ul>
							<a id="clear-sections" class="btn btn-primary btn-large" href="#">Clear All »</a>
						<p>
							<h3> Now you're ready to export! </h3>
							<a id="create-calendar" class="btn btn-primary btn-large" href="#">Export »</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</td>
</tr>
</table>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
</body>
</html>
