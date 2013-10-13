$(document).ready(function() {
	$('#departments :nth-child(0)').prop('selected', true);
	$('#departments').trigger('change');
	$('#courses').trigger('change');
});

$('#departments').change(function () {
	var dept = $( "#departments option:selected").val();
	var courses = $("#courses");
	var sections = $("#sections");
	courses.children('option:not(:first)').remove();
	sections.children('option:not(:first)').remove();
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
			} else {
				/*courses.hide();
				$('<span>').attr('id', 'empty-courses').text('No courses for selected department.').insertAfter($('#departments'));*/
				courses.children('option:first').text('No courses for department.');
				courses.prop('disabled', true);
			}
			courses.trigger('change');
		}, error : function () {
			/*courses.hide();
			$('<span>').attr('id', 'empty-courses').text('No courses for selected department.').insertAfter($('#departments'));*/
			courses.prop('disabled', true);
		}, dataType : 'json'});
	} else {
		courses.prop('disabled', true);
	}
});

$("#courses").change(function () {
	var course = $( "#courses option:selected").val();
	var sections = $("#sections");
	sections.children('option:not(:first)').remove();
	if (course != '') {
		$.ajax({type: 'POST', url : "ajax/sections.php", data : {course : course}, success : function(result) {
			if (result.length > 0) {
				/*sections.show();*/
				$.each(result, function(i, section) {
		    		sections.append($("<option />").val(section.id).text(section.id + ' - ' + section.location));
				});
				$('#courses :nth-child(0)').prop('selected', true);
				$("#add-section").prop('disabled', false);
				sections.children('option:first').text('Section');
				sections.prop('disabled', false);
			} else {
				/*sections.hide();
				$('<span>').attr('id', 'empty-sections').text('No sections for selected department.').insertAfter($('#courses'));*/
				$("#add-section").prop('disabled', true);
				sections.children('option:first').text('No sections for course.');
				sections.prop('disabled', true);
			}
		}, error : function() {
			/*sections.hide();
			$('<span>').attr('id', 'empty-sections').text('No sections for selected department.').insertAfter($('#courses'));*/
			$("#add-section").prop('disabled', true);
			sections.prop('disabled', true);
		}, dataType : 'json'});
	} else {
		$("#add-section").prop('disabled', true);
		sections.prop('disabled', true);
	}
});

$("#add-section").click(function() {
	var section = $( "#sections option:selected").val();
	var course = $( "#courses option:selected").val();
	$.post("ajax/add_section.php", {section : section, course : course}, function(result) {
		var mySections = $("#my-sections");
		var sectionDiv = $("<div>").append($("<p>").text(result.id + '\n' + result.days + '\n' + result.start + '\n' + result.end + '\n' + result.location + '\n' + result.instructor[0].first + ' ' + result.instructor[0].last));
		mySections.append(sectionDiv);
	}, 'json');
});

$("#clear-sections").click(function() {
	var mySections = $("#my-sections");
	$.get("ajax/clear_sections.php");
	mySections.empty();
});

$('#create-calendar').click(function() {
	$.get("ajax/create_calendar.php", function(result) {
		$("#calendar-url").attr('href', $.parseJSON(result)[0][0]).text("View your Calendar");
	});
});




