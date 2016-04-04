$('.toggle_adoptees').click(function() {
	$(this).toggleClass('active');
	$('#topics_page').toggleClass('hide_adoptees');
});
$('[data-action]').blur(function() {
	var action = $(this).attr(data-action), value
});