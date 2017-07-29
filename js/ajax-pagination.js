alert( 'Script Is Enqueued' )

var ajaxpagination = {"ajaxurl":"http:\/\/wordpress.local\/wp-admin\/admin-ajax.php"};

$(document).on( 'click', '.nav-links a', function( event ) {
	event.preventDefault();
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'ajax_pagination'
		},
		success: function( result ) {
			alert( result );
		}
	})
})
