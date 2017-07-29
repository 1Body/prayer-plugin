<script type='text/javascript'>
/* <![CDATA[ */
var spyr_params = {"ajaxurl":"http:\/\/example.com\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>

jQuery( document ).ready( function() {
	jQuery( '.spyr_adp_link' ).click( function( e ) {
		var link = this;
		var id   = jQuery( link ).attr( 'data-id' );
		var nonce = jQuery( link ).attr( 'data-nonce' );

		// This is what we are sending the server
		var data = {
			action: 'spyr_adp_ajax_delete_post',
			pid: id,
			nonce: nonce
		}
		// Change the anchor text of the link
		// To provide the user some immediate feedback
		jQuery( link ).text( 'Trying to delete post' );

		// Post to the server
		jQuery.post( spyr_params.ajaxurl, data, function( data ) {
			// Parse the XML response with jQuery
			// Get the Status
			var status = jQuery( data ).find( 'response_data' ).text();
			// Get the Message
			var message = jQuery( data ).find( 'supplemental message' ).text();
			// If we are successful, add the success message and remove the link
			if( status == 'success' ) {
				jQuery( link ).parent().after( '<p><strong>' + message + '</strong></p>').remove();
			} else {
				// An error occurred, alert an error message
				alert( message );
			}
		});
		// Prevent the default behavior for the link
		e.preventDefault();
	});
});
