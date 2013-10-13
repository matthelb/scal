$(document).ready(function() {
	$('#departments').chosen();
	$('#courses').chosen();
	$('#sections').chosen();
	$('#departments :nth-child(0)').prop('selected', true);
	$('#departments').trigger('change');
	$('#courses').trigger('change');

	$.get("ajax/get_sections.php", function(result) {
		$.each(result, function(i, section) {
			addSection(section);
		});
	}, 'json');

	var code = getParameterByName('code', document.URL);
	if (code != "") {
		createCalendar();
	}	
});

$('#departments').chosen().change(function () {
	var dept = $( "#departments option:selected").val();
	var courses = $("#courses");
	var sections = $("#sections");
	courses.children('option:not(:first)').remove();
	courses.trigger("chosen:updated");
	sections.children('option:not(:first)').remove();
	sections.trigger("chosen:updated");
	if (dept != '') {
		$.ajax({ type: 'POST', url : "ajax/courses.php", data : {dept : dept}, success : function(result) {
			if (result.length > 0) {
				/*courses.show();*/
				$.each(result, function(i, course) {
		    		courses.append($("<option />").val(course.id).text(course.id + ' - ' + course.title));
				});
				$('#departments :nth-child(0)').prop('selected', true);
				courses.children('option:first').text('Course');
				courses.prop('disabled', false);
				courses.trigger("chosen:updated");
			} else {
				/*courses.hide();
				$('<span>').attr('id', 'empty-courses').text('No courses for selected department.').insertAfter($('#departments'));*/
				courses.children('option:first').text('No courses for department.');
				courses.prop('disabled', true);
				courses.trigger("chosen:updated");
			}
			courses.trigger('change');
		}, error : function () {
			/*courses.hide();
			$('<span>').attr('id', 'empty-courses').text('No courses for selected department.').insertAfter($('#departments'));*/
			courses.prop('disabled', true);
			courses.trigger("chosen:updated");
		}, dataType : 'json'});
	} else {
		courses.prop('disabled', true);
		courses.trigger("chosen:updated");
	}
});

$("#courses").chosen().change(function () {
	var course = $( "#courses option:selected").val();
	var sections = $("#sections");
	sections.children('option:not(:first)').remove();
	sections.trigger("chosen:updated");
	if (course != '') {
		$.ajax({type: 'POST', url : "ajax/sections.php", data : {course : course}, success : function(result) {
			if (result.length > 0) {
				/*sections.show();*/
				$.each(result, function(i, section) {
		    		sections.append($("<option />").val(section.id).text(section.id + ' - ' + section.location + ' (' + section.days + ': ' + section.start + ')'));
				});
				$('#courses :nth-child(0)').prop('selected', true);
				$("#add-section").prop('disabled', false);
				sections.children('option:first').text('Section');
				sections.prop('disabled', false);
				sections.trigger("chosen:updated");
			} else {
				/*sections.hide();
				$('<span>').attr('id', 'empty-sections').text('No sections for selected department.').insertAfter($('#courses'));*/
				$("#add-section").prop('disabled', true);
				sections.children('option:first').text('No sections for course.');
				sections.prop('disabled', true);
				sections.trigger("chosen:updated");
			}
		}, error : function() {
			/*sections.hide();
			$('<span>').attr('id', 'empty-sections').text('No sections for selected department.').insertAfter($('#courses'));*/
			$("#add-section").prop('disabled', true);
			sections.prop('disabled', true);
			sections.trigger("chosen:updated");
		}, dataType : 'json'});
	} else {
		$("#add-section").prop('disabled', true);
		sections.prop('disabled', true);
		sections.trigger("chosen:updated");
	}
});

$("#add-section").click(function() {
	var section = $( "#sections option:selected").val();
	var course = $( "#courses option:selected").val();
	$.post("ajax/add_section.php", {section : section, course : course}, function(result) {
		addSection(result);
	}, 'json');
});

$("#clear-sections").click(function() {
	var mySections = $("#my-sections");
	$.get("ajax/clear_sections.php");
	mySections.empty();
});

$('#create-calendar').click(function() {
	createCalendar();
});

function addSection(section) {
	if (section != null) {
		var mySections = $("#my-sections");
		var sectionDiv = $("<li>").text(section.id + '\n' + section.days + '\n' + section.start + '\n' + section.end + '\n' + section.location + '\n' + section.instructor[0].first + ' ' + section.instructor[0].last);
		mySections.append(sectionDiv);
	}
}

function createCalendar() {
	var code = getParameterByName('code', document.URL);
	var data = (code != "") ? {code : code} : {};
	$('#calendar-url').hide();
	$.get("ajax/create_calendar.php", data, function(result) {
		if (result.error > 0) {
			window.location.replace(result.login_url);
		} else {	
			$("#calendar-url").attr('href', result[0][0]).text("View your Calendar").fadeIn("slow");
		}
	}, 'json');
}

function getParameterByName( name,href ) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( href );
  if( results == null ) {
    return "";
  } else {
    return decodeURIComponent(results[1].replace(/\+/g, " "));
  }
}

function helpPopup(){
	alert("How to use SCal to Google: \n1. Select your section.\n2. Press \"Add\" to add it to your class list.\n3. When all classes have been added, press \"Export\" to send to Google Calendar");
}

