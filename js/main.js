$(document).ready(function() {
	bindEvents();
	$('.btn-group').hide();
	$('#calendar').hide();

	$('#departments').select2({width: '90%'});
	$('#courses').select2({width: '90%'});
	$('#sections').select2({width: '90%'});
	
	$('#semesters-highlighted').trigger('click');
	$('#departments').trigger('change');
	$('#courses').trigger('change');

	if (getParameterByName('auth', document.URL) != '') {
		$('html, body').animate({
	       	scrollTop: $('#create-calendar').offset().top
	   	}, 500);
	   	createCalendar();
	}

	// Load stored semester selection from browser.
	var semesterChosen = localStorage.getItem('semester-chosen');
	if (semesterChosen !== null) {
		var element = $('*[data-semester-id=\'' + semesterChosen + '\']');
		if (element !== null) {
			var highlighted = $('#semester-highlighted');
				highlighted.attr('id', '');
				$(element).attr('id', 'semester-highlighted');
		}
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
			$('#calendar-url').hide();
			$('#select2-departments-container').append($('<div>').attr('id', 'departments-loading').attr('class', 'loading'));
			// $('#departments_chosen > .chosen-single > div > b').hide();
			$.ajax({ type: 'GET', url : 'ajax/departments.php', data : {semester : semester}, success : function(result) {
					if (result.length > 0) {
						$.each(result, function(i, dept) {
							departments.append($('<option/>').val(dept.code).html(dept.code + ' - ' + dept.name));
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
					departments.trigger('updateResults');
				}, error : function () {
					departments.attr('data-placeholder', 'No departments for semester.');
					departments.prop('disabled', true);
					$('#departments-loading').remove();
					$('#departments_chosen > .chosen-single > div > b').show();
					departments.trigger('change');
					departments.trigger('updateResults');
				}, dataType : 'json'});
			departments.trigger('change');
			departments.trigger('updateResults');
			localStorage.setItem('semester-chosen', semester);
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
			$('#select2-courses-container').append($('<div>').attr('id', 'courses-loading').attr('class', 'loading'));
			$('#courses_chosen > .chosen-single > div > b').hide();
			$.ajax({ type: 'GET', url : 'ajax/courses.php', data : {dept : dept, semester : semester}, success : function(result) {
				if (result.length > 0) {
					$.each(result, function(i, course) {
						courses.append($('<option />').val(course.id).html(course.id + ' - ' + course.title));
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
				courses.trigger('updateResults');
			}, error : function () {
				courses.attr('data-placeholder', 'No courses for department.');
				courses.prop('disabled', true);
				$('#courses-loading').remove();
				$('#courses_chosen > .chosen-single > div > b').show();
				courses.trigger('change');
				courses.trigger('updateResults');
			}, dataType : 'json'});
		} else {
			courses.prop('disabled', true);
		}
		courses.trigger('change');
		courses.trigger('updateResults');
	});

	$('#courses').change(function () {
		var course = $( '#courses option:selected').val();
		var sections = $('#sections');
		var semester = $('#semester-highlighted').attr('data-semester-id');
		sections.children('option:not(:first)').remove();
		if (course != '') {
			$('#select2-sections-container').append($('<div>').attr('id', 'sections-loading').attr('class', 'loading').css('background-image', 'url(img/ajax-loader.gif)'));
			$('#sections_chosen > .chosen-single > div > b').hide();
			$.ajax({type: 'GET', url : 'ajax/sections.php', data : {course : course, semester : semester}, success : function(result) {
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
						sections.append($('<option />').val(section.id).html(title + ' - ' + information));
					});
					sections.attr('data-placeholder', 'Section');
					sections.prop('disabled', false);
				} else {
					sections.attr('data-placeholder', 'No sections for course.');
					sections.prop('disabled', true);
				}
				$('#sections-loading').remove();
				$('#sections_chosen > .chosen-single > div > b').show();
				sections.trigger('change');
			}, error : function() {
				sections.children('option:first').text('No sections for course.');
				sections.prop('disabled', true);
				$('#sections-loading').remove();
				$('#sections_chosen > .chosen-single > div > b').show();
				sections.trigger('change');
			}, dataType : 'json'});
		} else {
			sections.prop('disabled', true);
		}
		sections.trigger('change');
	});

	$('#add-section').click(function() {
		var section = $('#sections option:selected').val();
		var course = $( '#courses option:selected').val();
		var semester = $('#semester-highlighted').attr('data-semester-id');
		if (section.length) {
		$('#my-sections').prepend($('<div>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
			$.post('ajax/add_section.php', {section : section, course : course, semester : semester}, function(result) {
				if (result.success) {
					addSection(result.section);
				} else {
					$('.loading').parent().remove();
				}
			}, 'json');
		}
	});

	$('#add-sections').click(function() {
		var sectionData = JSON.parse($('#sections-json').val());
		$('#my-sections').prepend($('<div>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
		$.post('ajax/add_sections.php', {sectionData : sectionData}, function(result) {
			if (result.success) {
				for(var i = 0; i < result.sections.length; ++i) {
					addSection(result.sections[i]);
				};
			}
			$('.loading').parent().remove();
		}, 'json');
	});

	$('#clear-sections').click(function() {
		var mySections = $('#my-sections');
		if (!mySections.is(':empty')) {
			console.log('clearing');
			$.post('ajax/clear_sections.php', {semester : $('#semester-highlighted').attr('data-semester-id')});
			clearSectionsUI();
		}
	});

	$('#load-calendar').click(function() {
		loadCalendar();
	});

	$('#create-calendar').click(function() {
		var mySections = $('#my-sections');
		if(!mySections.is(':empty')) {
			createCalendar();
		}
	});

	$('#get-started-link').click(function() {
	   	$('html, body').animate({
	       	scrollTop: $( $.attr(this, 'href') ).offset().top
	   	}, 700);
	   	return false;
	});

	$('.navbar-inverse a').on('click', function(){
	   $('.navbar-inverse').find('.active').removeClass('active');
	   $(this).parent().addClass('active');
	});
}

function initCalendar() {
	var calendar = $('#calendar-table tbody');
	var startTime = 6;
	var timeBlock = $('tr');
	var eBlock = $('tr');	
	eBlock.append($('td'));
	for(var i = 0; i < 5; ++i) {
		timeBlock.append($('td'));
		eBlock.append($('td'));
	}
	for(var i = 0; i < 18; ++i) {
		calendar.append(timeBlock.clone()
										.prepend($('th').text(String.format('{0}:00 {1}', (startTime++) % 12, startTime < 12 ? 'AM' : 'PM'))));
		calendar.append(eBlock.clone());
	}
}

function addSection(section) {
	if (section !== null) {
		$('.btn-group').show();
		$('#empty-msg').hide();
		$('#calendar').show();
		var mySections = $('#my-sections');
		var sectionDiv = $('<div>').attr('data-section-id', section.id).addClass('card');
		var title = (section.title != null) ? section.title : section.course.title;

		var cardContent = $('<div>').addClass('card-content black-text')
				.append($('<span>').addClass('card-title course-id').text(title + ' - ' + section.type));
		
		sectionDiv.append($('<i>').addClass('remove-section material-icons').text('close').click(function() {
			removeSection($(this).parent().data('section-id'));
		}));

    if (sections.days || section.start || section.end) {
			cardContent.append($('<p>').attr('class', 'section-days-time').text(((section.days) ? (dayCodeToString(section.days) + ' | ') : '') + section.start + ' - ' + section.end));
		}
		if (section.location) {
			cardContent.append($('<p>').attr('class', 'section-location').text(section.location));
		}
		if (section.instructor.length > 0) {
			cardContent.append($('<p>').attr('class', 'section-instructor').text(section.instructor[0].full_name));
		}
		sectionDiv.append(cardContent);

		var loading = mySections.children('div').css('text-align', '').children('.loading');
		if (loading.length) {
			loading.replaceWith(sectionDiv);
		} else {
			mySections.prepend(sectionDiv);
		}

		
		$.each(section.dayOffsets, function(i, o) {
			var sectionElem = $('<div>').addClass('section').attr('data-section-id', section.id).css('height', 18 * section.timeSlots).css('left', (16.666 * (o + 1)) + '%').css('top', (1 + section.timeSlot - 12) * 18).text(section.course.id);
			$('#calendar').append(sectionElem);
		});
	}
}

function clearSectionsUI() {
  $('#my-sections').empty();
	$('.section').remove();
	$('.btn-group').hide();
	$('#calendar').hide();
	$('#empty-msg').show();
}

function removeSection(section) {
	$.post('ajax/remove_section.php', {section : section, semester : $('#semester-highlighted').attr('data-semester-id')}, function(result) {
		if (result.success) {
			$('#my-sections [data-section-id*=' + section + ']').parent().remove();
			$('#calendar [data-section-id=' + section + ']').remove();
		}
		if ($('#my-sections').is(':empty')) {
			$('.btn-group').hide();
			$('#empty-msg').show();
			$('#calendar').hide();
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
	console.log('clearing and populating: ' + $('#semester-highlighted').attr('data-semester-id'));
	clearSectionsUI();
	mySections.prepend($('<div>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
	$.ajax({type: 'GET',  url: 'ajax/get_sections.php', data: {semester : $('#semester-highlighted').attr('data-semester-id')}, success : function(result) {
		$.each(result, function(i, section) {
			addSection(section);
		});
		mySections.children('li').css('text-align', '').children('.loading').remove();
	}, error: function() {
		mySections.children('li').css('text-align', '').children('.loading').remove();
	}, dataType : 'json'});
}

function loadCalendar() {
	var mySections = $('#my-sections');
	mySections.empty();
	mySections.prepend($('<div>').css('text-align', 'center').append($('<div>').attr('class', 'loading')));
	$.ajax({type: 'POST',  url: 'ajax/load_calendar.php', data: {semester : $('#semester-highlighted').attr('data-semester-id')}, success : function(result) {
		if (result.success) {
			$.each(result.sections, function(i, section) {
				addSection(section);
			});
			$('.btn-group').show();
			$('#empty-msg').hide();
			$('#calendar').show();
		} else if (result.auth_url) {
			window.location.href = result.auth_url;
		}
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
	$.post('ajax/create_calendar.php', data, function(result) {
		if (result) {
			if (result.success) {
				$('#calendar-url').attr('href', result.urls[0]).show();
			} else {
				window.location.href = result.auth_url;
			}
		}
		$('#export-loading').remove();
	}, 'json');
}

function getParameterByName( name,href ) {
	name = name.replace(/[\[]/,'\\\[').replace(/[\]]/,'\\\]');
	var regexS = '[\\?&]'+name+'=([^&#]*)';
	var regex = new RegExp( regexS );
	var results = regex.exec( href );
	if( results == null ) {
		return '';
	} else {
		return decodeURIComponent(results[1].replace(/\+/g, ' '));
	}
}

String.format = function(format) {
    var args = Array.prototype.slice.call(arguments, 1);
    return format.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number] 
        : match
      ;
    });
  };

function getSectionsScript() {
	var res = {};
	
	var semesters = ['spring', 'summer', 'fall'];
	var semData = $('.termblock')[0].innerHTML.split(' ');
	var year = semData[1];
	var semester = semesters.indexOf(semData[0].toLowerCase()) + 1;
	res.semester = year + semester;

	res.sectionList = [];
	var sectionRows = $('#listWarp > table > tbody > tr');
	for(var i = 1; i < sectionRows.length; ++i) {
		var sectionData = {};
		var row = $(sectionRows[i]);
		var data = row.find('td');
		sectionData.course = data[0].innerHTML;
		sectionData.section = $(data[2]).text();
		res.sectionList.push(sectionData);
	}
	window.prompt('Copy to clipboard:', JSON.stringify(res));
}