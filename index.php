<?php
session_start();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>SCal to Google</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:title" content="SCal" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="http://scal.heuristix.me/" />
  <meta property="og:image" content="http://scal.heuristix.me/img/photo.png" />
  <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
  <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/css/materialize.min.css">
  <style>
    body {
      padding-bottom: 20px;
    }
  </style>
  <!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
  <link rel="stylesheet" href="css/main.css">
  <script src="js/vendor/modernizr-2.6.2.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body id="about">
    <!--[if lt IE 7]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    <nav>
      <div class="nav-wrapper">
        <div class="col s12">
          <a href="#" class="brand-logo">SCal</a>
        </div>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li class="active"><a href="#about">about</a></li>
          <li><a href="#content">create</a></li>
          <li><a href="#export">export</a></li>
        </ul>
      </div>
    </nav>

    <div class="top-container">
      <div id="top-content">
        <h2 style="padding-top:5px;margin-top:0px;">
          <span class="top-text">Convert your USC course list to a Google Calendar with SCal</span>
        </h2>
        <div id="steps">
          <ol>
            <li>1. Enter your courses.</li>
            <li>2. Review your selections.</li>
            <li>3. Export.</li>
          </ol>
        </div>
        <button class="btn btn-primary btn-lg amber accent-4" id="get-started-link" href="#content">Get Started</button> <br />
        <img src="img/calendar.png" />
      </div>
    </div>

    <div class="container" id="main">
      <div class="row">
        <div id="content" class="col-lg-4 center">
          <h3 id="select-courses"> Select Your Courses </h3>
          <ul id="semesters" class="list-inline">
            <?php
            require_once('lib/functions.php');
            $semesters = get_all_semesters();
            $size = sizeof($semesters);
            $current = $size - 1;
            if (isset($_SESSION['authorization']['calendar'])) {
              $current = array_search($_SESSION['authorization']['calendar'], $semesters);
            }
            for($i = 0; $i < $size; $i++) {
              $semester = $semesters[$i];
              $display = semester_to_string($semester);
              $id = ($i == $current) ? 'id="semester-highlighted"' : '';
              echo "<li $id class=\"semester\" data-semester-id=\"$semester\"><strong>$display</strong></li>";
            }
            ?>
          </ul>
          <hr/>
          <div role="tabpanel" class="anchor">
            <ul class="tabs grey lighten-2 advanced-regular-tabs" role="tablist">
              <li class="tab" role="presentation" class="active">
                <a href="#regular-user" aria-controls="regular-user" role="tab" data-toggle="tab"
                    class="amber-text text-accent-4">Regular</a>
              </li>
              <li class="tab" role="presentation">
                <a href="#advanced-user" aria-controls="advanced-user" role="tab" data-toggle="tab"
                    class="amber-text text-accent-4">Advanced</a>
              </li>
            </ul>
            <div>
              <div role="tabpanel" class="tab-pane active" id="regular-user">
                <p>
                  <h4> Departments </h4>
                  <select id="departments" data-placeholder="select a department" name="departments">
                    <option value=""></option>
                    <?php
                    $depts = get_all_departments($semesters[$current]);
                    foreach($depts as $dept) {
                      $code = $dept->getCode();
                      $name = $dept->getName();
                      echo "<option value=$code>$code - $name</option>";
                    }
                    ?>
                  </select>
                  <h4> Courses </h4>
                  <select id="courses" data-placeholder="select a course" name="courses">
                    <option value=""></option>
                  </select>
                  <h4> Sections </h4>
                  <select id="sections" data-placeholder="select a section" name="sections">
                    <option value=""></option>
                  </select>
                  <br>
                  <br>
                  <a id="add-section" class="btn btn-primary btn-small amber accent-4" href="#" onclick="return false;">Add Course</a>
                </p>
              </div>
              <div role="tabpanel" class="tab-pane" id="advanced-user">
                <p>
                  1. Drag this button into your bookmark bar: <a class="btn btn-primary btn-small grey darken-2"
                  href='javascript: function oldWebReg(){var a={},b=["spring","summer","fall"],c=$(".termblock")[0].innerHTML.split(" "),d=c[1],e=b.indexOf(c[0].toLowerCase())+1;a.semester=d+e,a.sectionList=[];for(var f=$("#listWarp > table > tbody > tr"),g=1;g<f.length;++g){var h={},i=$(f[g]),j=i.find("td");h.course=j[0].innerHTML,h.section=$(j[2]).text(),a.sectionList.push(h)}return a}function newWebReg(){var a={},b=["spring","summer","fall"],c=$(".termbutn")[0].innerHTML.split(" "),d=c[1].trim(),e=b.indexOf(c[0].trim().toLowerCase())+1;a.semester=d+e,a.sectionList=[];for(var g,f=$("#fsReg > .row"),h=0;h<f.length;h++){var i={};$(f[h]).find(".crsID")[0]?(i.course=$(f[h]).find(".crsID")[0].innerHTML.split(" ")[0],g=i.course):(i.course=g,$(f[h]).find("[class*=section_alt] [class*=id_alt] b")[0]&&(i.section=$(f[h]).find("[class*=section_alt] [class*=id_alt] b")[0].innerHTML.split(" ")[0],a.sectionList.push(i)))}return a}function newestWebReg(){var a={},b=["spring","summer","fall"],c=$("#activeTermTab").text().trim().split(" "),d=c[1].trim(),e=b.indexOf(c[0].trim().toLowerCase())+1;a.semester=d+e;var f={};$(".k-event > div[title]").each(function(a){var b=/\(.*?\): (.+?) \((\d+?)\).*/,c=b.exec($(this).attr("title"));f.hasOwnProperty(c[1])||(f[c[1]]={}),f[c[1]][c[2]]=!0});a.sectionList=[];for(var h in f)for(var i in f[h]){var j={};j.course=h,j.section=i,a.sectionList.push(j)}return a}var data="webreg.usc.edu"==window.location.hostname?newestWebReg():oldWebReg();window.alert("Copy to clipboard:"+JSON.stringify(data));'>
                      Drag Me!
                    </a>
                </p>
                <p>2. Go to the "Calendar View" tab in Web Registration</p>
                <p>3. Click the bookmarked snippet, copy the output, and paste it below</p>
                <textarea id="sections-json" style="width: 90%;margin-bottom: 10px;"></textarea>
                <p>
                  4. <a id="add-sections" class="btn btn-primary btn-small amber accent-4" href="#" onclick="return false;">Add Courses</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="review">
      <h3> Review Your Courses </h3>
      <a id="load-calendar" class="btn btn-primary btn-small amber accent-4" href="#" onclick="return false;">Load Sections</a>
      <a id="clear-sections" class="btn btn-primary btn-small btn-group amber accent-4" href="#" onclick="return false;">Clear All</a>
      <div id="empty-msg">Manually add courses using the drop downs above or load a calendar
        previously made with SCal by clicking the 'Load Sections' button.</div>
        <div id="calendar">
          <div id="sections-overlay"></div>
          <table id="calendar-table">
            <tr>
              <td></td>
              <th>Monday</th>
              <th>Tuesday</th>
              <th>Wednesday</th>
              <th>Thursday</th>
              <th>Friday</th>
            </tr>
            <tr>
              <th>6:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>7:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>8:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>9:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>10:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>11:00 AM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>12:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>1:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>2:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>3:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>4:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>5:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>6:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>7:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>8:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>9:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>10:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <th>11:00 PM</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table>
        </div>
        <div id="sections">
          <div id="my-sections" class="list-unstyled"></div>
        </div>
        <p id="export">
          <a id="create-calendar" class="btn btn-primary btn-small btn-group amber accent-4" href="#"  onclick="return false;">Export</a>
          <a id="calendar-url" class="btn btn-primary btn-small amber accent-4" href="#" style="display: none;" target="_blank">View calendar</a>
        </p>
      </div>

      <footer>
        <hr />
        <div id="footer-content">
          <span>&copy; MMAS 2015</span>
        </div>
      </footer>
      <!-- /container -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/js/materialize.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
      <script src="js/plugins.js"></script>
      <script src="js/main.js"></script>

      <script>
        var _gaq=[['_setAccount','UA-44989976-1'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
          g.src='//www.google-analytics.com/ga.js';
          s.parentNode.insertBefore(g,s)}(document,'script'));
      </script>
    </body>
    </html>
