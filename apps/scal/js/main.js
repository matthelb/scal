$(document).ready(function() {
	bindEvents();
	$('#departments').chosen();
	var departmentsWidth = $('#departments_chosen').width();
	$('#courses').chosen({width: departmentsWidth + "px"});
	$('#sections').chosen({width: departmentsWidth + "px"});
	$('#semesters-highlighted').trigger('click');
	$('#departments').trigger('change');
	$('#courses').trigger('change');

	if (getParameterByName('auth', document.URL) != '') {
		$('html, body').animate({
	       	scrollTop: $('#create-calendar').offset().top
	   	}, 500);
	   	createCalendar();
	}	
	populateSections();
});
function bindEvents() {
	$('#semesters > li').click(function() {
		var highlighted = $('#semester-highlighted');
		if (!highlighted.is($(this))) {
			highlighted.attr('id', '');
			$(this).attr('id', 'semester-highlighted');
			populateSections();
			var semester = $(this).attr('data-semester-id');
			var departments = $('#departments');
			departments.children('option:not(:first)').remove();
			$("#calendar-url").hide();
			$('#departments_chosen > .chosen-single').append($('<div>').attr('id', 'departments-loading').attr('class', 'loading'));
			$('#departments_chosen > .chosen-single > div > b').hide();
			$.ajax({ type: 'GET', url : "ajax/departments.php", data : {semester : semester}, success : function(result) {
					if (result.length > 0) {
						$.each(result, function(i, dept) {
							departments.append($("<option/>").val(dept.code).html(dept.code + ' - ' + dept.name));
						});
						departments.attr('data-placeholder', 'Department');
						departments.prop('disabled', false);
					} else {
						departments.attr('data-placeholder', 'No departments for semester.');
						departments.prop('disabled', true);
					}
					$('#departments-loading').remove();
					$('#departments_chosen > .chosen-single > div > b').show();
					departments.trigger('change');
					departments.trigger("chosen:updated");
				}, error : function () {
					departments.attr('data-placeholder', 'No departments for semester.');
					departments.prop('disabled', true);
					$('#departments-loading').remove();
					$('#departments_chosen > .chosen-single > div > b').show();
					departments.trigger('change');
					departments.trigger("chosen:updated");
				}, dataType : 'json'});
			departments.trigger('change');
			departments.trigger('chosen:updated');
		}
	});

	$('#departments').change(function () {
		var dept = $('#departments option:selected').val();
		var courses = $('#courses');
		var sections = $('#sections');
		var semester = $('#semester-highlighted').attr('data-semester-id');
		courses.children('option:not(:first)').remove();	
		sections.children('option:not(:first)').remove();
		if (dept != '') {
			$('#courses_chosen > .chosen-single').append($('<div>').attr('id', 'courses-loading').attr('class', 'loading'));
			$('#courses_chosen > .chosen-single > div > b').hide();
			$.ajax({ type: 'GET', url : "ajax/courses.php", data : {dept : dept, semester : semester}, success : function(result) {
				if (result.length > 0) {
					$.each(result, function(i, course) {
						courses.append($("<option />").val(course.id).html(course.id + ' - ' + course.title));
					});
					courses.attr('data-placeholder', 'Course');
					courses.prop('disabled', false);
				} else {
					courses.attr('data-placeholder', 'No courses for department.');
					courses.prop('disabled', true);
				}
				$('#courses-loading').remove();
				$('#courses_chosen > .chosen-single > div > b').show();
				courses.trigger('change');
				courses.trigger("chosen:updated");
			}, error : function () {
				courses.attr('data-placeholder', 'No courses for department.');
				courses.prop('disabled', true);
				$('#courses-loading').remove();
				$('#courses_chosen > .chosen-single > div > b').show();
				courses.trigger('change');
				courses.trigger("chosen:updated");
			}, dataType : 'json'});
		} else {
			courses.prop('disabled', true);
		}
		courses.trigger('change');
		courses.trigger("chosen:updated");
	});

	$('#courses').change(function () {
		var course = $( "#courses option:selected").val();
		var sections = $("#sections");
		var semester = $('#semester-highlighted').attr('data-semester-id');
		sections.children('option:not(:first)').remove();
		if (course != '') {
			$('#sections_chosen > .chosen-single').append($('<div>').attr('id', 'sections-loading').attr('class', 'loading').css('background-image', 'url(img/ajax-loader.gif)'));
			$('#sections_chosen > .chosen-single > div > b').hide();
			$.ajax({type: 'GET', url : "ajax/sections.php", data : {course : course, semester : semester}, success : function(result) {
				if (result.length > 0) {
					$.each(result, function(i, section) {
						var title = (section.title != null) ? section.title : section.id;
						var information = '';
						if (section.location) {
							information += section.location;
						}
						if (section.days) {
							information += ' (' + dayCodeToString(section.days);
						}
						if (section.start) {
							information += ': ' + section.start;
						}
						if (information.indexOf('(') != -1) {
							information += ')';
						} else {
							information = 'No information available.';
						}
						sections.append($("<option />").val(section.id).html(title + ' - ' + information));
					});
					sections.attr('data-placeholder', 'Section');
					sections.prop('disabled', false);
				} else {
					sections.attr('data-placeholder', 'No sections for course.');
					sections.prop('disabled', true);
				}
				$('#sections-loading').remove();
				$('#sections_chosen > .chosen-single > div > b').show();
				sections.trigger("chosen:updated");
			}, error : function() {
				sections.children('option:first').text('No sections for course.');
				sections.prop('disabled', true);
				$('#sections-loading').remove();
				$('#sections_chosen > .chosen-single > div > b').show();
				sections.trigger("chosen:updated");
			}, dataType : 'json'});
		} else {
			sections.prop('disabled', true);
		}
		sections.trigger('chosen:updated');
	});

	$('#add-section').click(function() {
		var section = $('#sections option:selected').val();
		var course = $( '#courses option:selected').val();
		var semester = $('#semester-highlighted').attr('data-semester-id');
		if (section.length) {
		$('#my-sections').prepend($('<li>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
			$.post("ajax/add_section.php", {section : section, course : course, semester : semester}, function(result) {
				if (result.success) {
					addSection(result.section);
				} else {
					$('.loading').parent().remove();
				}
			}, 'json');
		}
	});
	$('#clear-sections').click(function() {
		var mySections = $('#my-sections');
		if (!mySections.is(":empty")) {
			$.post('ajax/clear_sections.php', {semester : $('#semester-highlighted').attr('data-semester-id')});
			mySections.empty();
		}
	});

	$('#create-calendar').click(function() {
		var mySections = $('#my-sections');
		if(!mySections.is(":empty")){
			createCalendar();
		}
	});

	$('#get-started-link').click(function() {
	   	$('html, body').animate({
	       	scrollTop: $( $.attr(this, 'href') ).offset().top
	   	}, 700);
	   	$('#startModal').modal('hide')
	   	return false;
	});
}

function addSection(section) {
	if (section != null) {
		var mySections = $('#my-sections');
		var sectionDiv = $('<div>').attr('data-section-id', section.id);
		var title = (section.title != null) ? section.title : section.course.title;
		sectionDiv.append($("<h4>").attr('class', 'course-id').text(title + ' - ' + section.type).append($('<div>').click(function() {
    		removeSection($(this).parents('div').data('section-id'));
    	}).hover(function(){
    		$(this).animate({
    			width: "+=45"
    		}, 'fast', function(){
    			$(this).html('remove');
    		});
    	}, function(){
    		$(this).animate({
    			width: "-=45"
    		}, 'fast', function(){
    			$(this).html('&times;');
    		});
    	}).attr('class', 'close').html('&times;')));
    	if (sections.days || section.start || section.end) {
			sectionDiv.append($("<p>").attr('class', 'section-days-time').text(((section.days) ? (dayCodeToString(section.days) + ' | ') : '') + section.start + " - " + section.end));
		}
		if (section.location) {
			sectionDiv.append($("<p>").attr('class', 'section-location').text(section.location));
		}
		sectionDiv.append($("<p>").attr('class', 'section-instructor').text(section.instructor[0].first + ' ' + section.instructor[0].last));
		var loading = mySections.children('li').css('text-align', '').children('.loading');
		if (loading.length) {
			loading.replaceWith(sectionDiv);
		} else {
			mySections.prepend($('<li>').append(sectionDiv));
		}
	}
}

function removeSection(section) {
	$.post("ajax/remove_section.php", {section : section, semester : $('#semester-highlighted').attr('data-semester-id')}, function(result) {
		if (result.success) {
			$("[data-section-id=\"" + section + "\"]").parent().remove();
		}
	}, 'json');
}

function dayCodeToString(code) {
	var days = '';
	for(var i = 0; i < code.length; i++) {
		switch (code.charAt(i)) {
			case 'M':
			days += 'Monday';
			break;
			case 'T':
			days += 'Tuesday';
			break;
			case 'W':
			days += 'Wednesday';
			break;
			case 'H':
			days += 'Thursday';
			break;
			case 'F':
			days += 'Friday';
			break;
		}
		if (i != code.length - 1) {
			days += ', ';
		}
	}
	return days;
}

function populateSections() {
	var mySections = $('#my-sections');
	mySections.empty();
	mySections.prepend($('<li>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
	$.ajax({type: 'GET',  url: 'ajax/get_sections.php', data: {semester : $('#semester-highlighted').attr('data-semester-id')}, success : function(result) {
		$.each(result, function(i, section) {
			addSection(section);
		});
		mySections.children('li').css('text-align', '').children('.loading').remove();
	}, error: function() {
		mySections.children('li').css('text-align', '').children('.loading').remove();
	}, dataType : 'json'});
}

function createCalendar() {
	var code = getParameterByName('code', document.URL);
	var data = (code != '') ? {code : code, semester : $('#semester-highlighted').attr('data-semester-id')} : {semester : $('#semester-highlighted').attr('data-semester-id')};
	$('#calendar-url').hide();
	if (!$('#export-loading').length) {
		$('<div>').attr('id', 'export-loading').attr('class', 'loading').css('background-image', 'url(img/ajax-loader.gif)').insertAfter($('#create-calendar'));
	}
	$.post("ajax/create_calendar.php", data, function(result) {
		if (result) {
			if (result.success) {
				$("#calendar-url").attr('href', result.urls[0][0]).show();
			} else {	
				window.location.href = result.auth_url;
			}
		}
		$('#export-loading').remove();
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