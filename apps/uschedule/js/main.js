$( document ).ready(function() {
	$('#departments :nth-child(0)').prop('selected', true);
	$('#departments').trigger('change');
});

$('#departments').change(function () {
	var dept = $( "#departments option:selected").val();
	var courses = $("#courses");
	var sections = $("#sections");
	courses.empty();
	sections.empty();
	$.post("ajax/courses.php", {dept : dept}, function(result) {
		$.each(result, function(i, course) {
    		courses.append($("<option />").val(course.id).text(course.id + ' - ' + course.title));
		});
		$('#departments :nth-child(0)').prop('selected', true);
		courses.trigger('change');
	}, 'json');
	
});

$("#courses").change(function () {
	var course = $( "#courses option:selected").val();
	var sections = $("#sections");
	sections.empty();
	$.post("ajax/sections.php", {course : course}, function(result) {
		$.each(result, function(i, section) {
    		sections.append($("<option />").val(section.id).text(section.id + ' - ' + section.location));
		});
		$('#courses :nth-child(0)').prop('selected', true);
	}, 'json');
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
		$("#calendar-url").attr('href', result[0]).text("View your Calendar");
	});
});




