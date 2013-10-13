/*<?php
$curl = curl_init();
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
?>*/
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <p>Hello world! This is HTML5 Boilerplate.</p>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>
