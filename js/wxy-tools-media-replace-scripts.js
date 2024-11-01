// ALL CODE ©2020-Present Clarence "exoboy" Bowman and WXY Tools, http://www.wxytools.com
// this code may not be distributed or copied without prior written consent of the author.

// begin encapsulation
(function($){

	var admin_vars = wxy_media_replace_admin_vars;
	var WP_FORM_SUBMISSION_PATH = admin_vars[ "form_submission_path" ];

	jQuery( document ).on("DOMContentLoaded", function(){
		//DOMContentLoaded
		wxy_tools_media_replace_startup();
	});

	// ********************************************************************
	// PAGE ONREADY (STARTUP)
	// ********************************************************************
	function wxy_tools_media_replace_startup()
	{

		// refresh the browser window to remove any duplicate thumbnails from replaced images
		try {
			var wxy_wp_uploader = wp.Uploader.queue;
		} catch (e) {
			wxy_wp_uploader = null;
		}

		if( wxy_wp_uploader != null )
		{
				wp.Uploader.queue.on('reset', function() {
				// force media refresh

				if( wp.media.frame.content != null )
				{
					try {
						wp.media.frame.content.get().collection._requery(true);
					} catch (e) {
						window.location.reload();
					}
				} else {
					window.location.reload();
				}
			});
		}

		// listen for changes in the STATUS select menu in our settings page
		$( ".wxy-tools-media-replace-status-select" ).on( "change", function()
		{
			var val = $( this ).val();
			var msg = $( this ).parent().find( ".wxy-tools-media-replace-saving" );
			$( msg ).show();
			
			// submit a request to change our setting
			submit_request( "status-" + val );
		
		});
		
		// listen for changes in the MESSAGES select menu in our settings page
		$( ".wxy-tools-media-replace-messages-select" ).on( "change", function()
		{
			var val = $( this ).val();
			var msg = $( this ).parent().find( ".wxy-tools-media-replace-saving" );
			$( msg ).show();
			
			// submit a request to change our setting
			submit_request( "messages-" + val );
		
		});
		
	};
	
	
	// ---------------------------------------------------
	// quietly send our request in the background
	// ---------------------------------------------------
	function submit_request( action )
	{
		// build some data to send
		var data = {};
		data[ "wxy_action" ] = action;
		data[ "action" ] = "wxy_tools_media_replace_request";
		
		// create a form object to submit
		var formData = new FormData();
		
		for(var property in data )
		{
			if( data.hasOwnProperty( property ) )
			{
				// add this to our form
				formData.append( property, data[ property ] );
			}
		}
		
		// submit via ajax
		jQuery.ajax({
			"url": WP_FORM_SUBMISSION_PATH,
			"type":"POST",
			"action": "wxy_tools_media_replace_request",
			"method":"POST",
			"data": formData,
			"processData": false,
			"contentType": false,

			"success": function( responseText, statusText, jqXHR, form )
				{
					// show our result
					// process the result from the server
					process_response_text( responseText );
				},

			"fail": function(){},

			"error": function(xhr, textStatus, errorThrown) {}
		});
	};


 	// -----------------------------------------------------
	// process our form submission request results
	// -----------------------------------------------------
	function process_response_text( responseText )
	{	
		// hide our saving messages....
		$( ".wxy-tools-media-replace-saving" ).hide();
	
	};


// end encapsulation
})(jQuery);