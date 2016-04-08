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
			for (current_word = reference_string_part_limits[0] * 1; current_word <= reference_string_part_limits[1] * 1; current_word ++) {
				unique_numbers_array.push(current_word);
			}

		}

	});

	// Return unique numbers array
	return unique_numbers_array;

}

function highlightTagTranslationWords(tag_translation_id, words_to_highlight_string, unhighlight_first = false) {

	// Unhighlight all tag translation words (if applicable)
	if (unhighlight_first) {
		$('blockquote[data-tag-translation=' + tag_translation_id + '] .word').removeClass('highlighted');
	}

	// Get words to highlight array
	words_to_highlight_array = getUniqueNumbers(words_to_highlight_string);

	// Highlight applicable tag translation words to highlight
	words_to_highlight_array.map(function(word_number) {
		$('blockquote[data-tag-translation=' + tag_translation_id + '] .word[data-word=' + word_number + ']').addClass('highlighted');
	});

}

function editTagTranslationRelevantWords(tag_translation_id) {

	// Hide edit icon and show confirm icon
	$('blockquote[data-tag-translation=' + tag_translation_id + '] .relevant_words .edit').hide().next().css('display', 'inline-block');

	// Unhighlight all tag translation words
	$('blockquote[data-tag-translation=' + tag_translation_id + '] .word').removeClass('highlighted');

	// Define relevant words
	if (typeof relevant_words === 'undefined') {
		relevant_words = [];
	}

	// Define relevant words for the applicable tag translation
	relevant_words[tag_translation_id] = [];

	// Add listeners to words
	$('blockquote[data-tag-translation=' + tag_translation_id + '] .word').mousedown(function() {

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
		highlightTagTranslationWords(tag_translation_id, latest_word_range);

		// Append selection to relevant words
		relevant_words[tag_translation_id].push(latest_word_range);

	});

}

function confirmTagTranslationRelevantWords(tag_translation_id) {

	// Hide confirm icon and show edit icon
	$('blockquote[data-tag-translation=' + tag_translation_id + '] .relevant_words .confirm').hide().prev().css('display', 'inline-block');

	// Submit relevant words to API
	$.get('api.php?update_tag_translation_relevant_words&tag_translation=' + tag_translation_id + '&relevant_words=' + relevant_words[tag_translation_id].join(','));

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