jQuery(document).ready(function($) {
	$(document).on('click', '.firehawk-crm .grid-item', function(event) {
		console.log('Grid item clicked');
		var contentLink = $(event.currentTarget).data('link');

		if (contentLink) {
			console.log('Adding loading animation');
			addLoadingAnimation(event.currentTarget); // Add the loading animation

			if (event.ctrlKey || event.metaKey) {
				window.open(contentLink, '_blank');
			} else {
				document.location = contentLink;
			}
		}
	});
});

function addLoadingAnimation(element) {
	console.log('Adding loading animation to element');
	element.classList.add('loading-animation');
}

function removeLoadingAnimation(element) {
	console.log('Removing loading animation from element');
	element.classList.remove('loading-animation');
}
