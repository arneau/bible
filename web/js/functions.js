$('.toggle_adoptees').click(function() {
	$(this).toggleClass('active');
	$('#topics_page').toggleClass('hide_adoptees');
});

$('[data-action]').blur(function() {
	var action = $(this).attr('data-action');
	if (action == 'update_tag_translation_relevant_words') {
		$.get('api.php?' + action + '&tag_translation=' + $(this).attr('data-tag-translation') + '&relevant_words=' + $(this).val());
	}
});

function getUniqueNumbers(reference_string) {

	// Handle reference string
	var unique_numbers_array = [];
	reference_string.split(',').map(function(reference_string_part) {

		// Append single word to words to highlight array (if applicable)
		if (reference_string_part.indexOf('-') === -1) {
			unique_numbers_array.push(reference_string_part * 1);
		} else {

			// Define words to highlight part limit
			var reference_string_part_limits = reference_string_part.split('-');

			// Append individual words to words to highlight array (if applicable)
			for (current_word = reference_string_part_limits[0] * 1; current_word <= reference_string_part_limits[1] * 1; current_word++) {
				unique_numbers_array.push(current_word);
			}

		}

	});

	// Return unique numbers array
	return unique_numbers_array;

}

function applyTagHighlighter(tag_highlighter_id, relevant_words, unhighlight_first) {

	// Unhighlight all tag highlighter words (if applicable)
	if (unhighlight_first) {
		$('blockquote[data-tag-highlighter=' + tag_highlighter_id + '] .word').removeClass('highlighted');
	}

	// Get words to highlight array
	words_to_highlight_array = getUniqueNumbers(relevant_words);

	// Highlight applicable tag highlighter words to highlight
	words_to_highlight_array.map(function(word_number) {
		$('blockquote[data-tag-highlighter=' + tag_highlighter_id + '] .word[data-word=' + word_number + ']').addClass('highlighted');
	});

}

function editTagHighlighter(tag_highlighter_id) {

	// Make display changes
	$('blockquote[data-tag-highlighter=' + tag_highlighter_id + ']').css('border-left-color', '#fd4').find('.relevant_words .edit').hide().next().css('display', 'inline-block');

	// Unhighlight all tag highlighter words
	$('blockquote[data-tag-highlighter=' + tag_highlighter_id + '] .word').removeClass('highlighted');

	// Define relevant words
	if (typeof relevant_words === 'undefined') {
		relevant_words = [];
	}

	// Define relevant words for the applicable tag highlighter
	relevant_words[tag_highlighter_id] = [];

	// Add listeners to words
	$('blockquote[data-tag-highlighter=' + tag_highlighter_id + '] .word').mousedown(function() {

		// Capture first word of selection
		first_word = $(this).attr('data-word');

	}).mouseup(function() {

		// Clear selection
		clearSelection();

		// Capture last word of selection
		last_word = $(this).attr('data-word');

		// Determine latest range of words
		var latest_word_range;
		if (first_word == last_word) {
			latest_word_range = first_word;
		} else {
			latest_word_range = first_word + '-' + last_word;
		}

		// Highlight relevant words
		applyTagHighlighter(tag_highlighter_id, latest_word_range);

		// Append selection to relevant words
		relevant_words[tag_highlighter_id].push(latest_word_range);

	});

}

function updateTagHighlighter(tag_highlighter_id) {

	// Remove listeners from words
	$('blockquote[data-tag-highlighter=' + tag_highlighter_id + '] .word').off('mousedown').off('mouseup');

	// Make display changes
	$('blockquote[data-tag-highlighter=' + tag_highlighter_id + ']').css('border-left-color', '#eee').find('.relevant_words .confirm').hide().prev().css('display', 'inline-block');

	// Submit relevant words to API
	$.get('api.php?update_tag_highlighter&tag_highlighter_id=' + tag_highlighter_id + '&tag_highlighter_relevant_words=' + relevant_words[tag_highlighter_id].join(','));

}

function deleteLesson(lesson_id) {

	// Send request to API and reload
	$.get('api.php?delete_lesson&lesson_id=' + lesson_id, function() {
		location = 'lessons.php';
	});

}

function moveLesson(lesson_id, parent_lesson_id) {

	// Send request to API and reload
	$.get('api.php?move_lesson&lesson_id=' + lesson_id + '&parent_lesson_id=' + parent_lesson_id, function() {
		location.reload();
	});

}

function moveTagToLesson(tag_id, lesson_id) {
	$.get('api.php?move_tag_to_lesson&tag_id=' + tag_id + '&lesson_id=' + lesson_id, function() {
		location.reload();
	});
}

function addTopicLesson(topic_id, lesson_id) {

	// Send request to API and reload
	$.get('api.php?add_topic_lesson&topic_id=' + topic_id + '&lesson_id=' + lesson_id, function() {
		location.reload();
	});

}

function deleteTag(tag_id) {

	// Send request to API and reload
	$.get('api.php?delete_tag&tag_id=' + tag_id, function() {
		location.reload();
	});

}

function clearSelection() {

	// Clear selection in Firefox
	if (window.getSelection && window.getSelection().removeAllRanges) {
		window.getSelection().removeAllRanges();
	}

	// Clear selection in Chrome
	if (window.getSelection && window.getSelection().empty) {
		window.getSelection().empty();
	}

}

$('form[data-type=api]').submit(function() {
	return submitForm(this);
});

function submitForm(form) {

	// Submit form data to API and reload
	$.get('api.php?' + $(form).attr('action') + '&' + $(form).serialize(), function() {

		// Reload page as per action
		if ($(form).attr('action') == 'edit_topic') {
			location.reload();
		} else {
			location = location + '&order_passages_by=date_tagged';
		}

	});

	// Stop default form submission
	return false;
}

function showPopup(popup_id) {

	// Show popup by ID
	$('#' + popup_id + '.popup').css('display', 'flex').find('input[type=text]').first().select();

}

// Add listeners to popups
$('.popup').each(function() {
	$(this).click(function() {
		$(this).hide();
	}).children().first().click(function(event) {
		event.stopPropagation();
	});
});

$('.passage').draggable({
	handle: '.handle',
	helper: 'clone',
	revert: true,
});
$('.lesson_list_item').draggable({
	helper: 'clone',
	revert: true,
}).droppable({
	accept: '.passage, .lesson_list_item',
	hoverClass: 'below',
	tolerance: 'pointer',
	drop: function(event, ui) {
		if (ui.draggable.is('.passage')) {
			moveTagToLesson(ui.draggable.attr('data-tag-id'), $(this).attr('data-lesson-id'))
		} else if (ui.draggable.is('.lesson_list_item')) {
			moveLesson(ui.draggable.attr('data-lesson-id'), $(this).attr('data-lesson-id'));
		}
	}
});
$('.topic_list_item').droppable({
	accept: '.lesson_list_item, .topic_list_item',
	hoverClass: 'below',
	tolerance: 'pointer',
	drop: function(event, ui) {
		addTopicLesson($(this).attr('data-topic-id'), ui.draggable.attr('data-lesson-id'));
	}
});

function filterLessonFamilies(input) {

	var search_string = $(input).val().toLowerCase();

	//$('.lesson_family').show().removeMark();

	if (search_string.length > 2) {

		$('.lesson_family').each(function() {

			var lesson_family_text = $(this).text().toLowerCase();

			if (lesson_family_text.indexOf(search_string) !== -1) {
				//$(this).mark(search_string, {
				//	className: 'match'
				//});
			} else {
				$(this).hide();
			}

		});

	}

}