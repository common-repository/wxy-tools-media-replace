<?php 
/*
	Plugin Name: WXY Tools Media Replace
	Plugin URI: http://www.wxytools.com
	Description: Upload files to the media tab and if there is a file already uploaded with that filename, the new file will replacde the old one.
	Version: 1.0.0
	Author: Clarence "exoboy" Bowman
	Author URI: https://www.bowmandesignworks.com
	License: GPL2
*/

// ***********************************************************************
// ONLY LOAD IF IN THE ADMIN AREA!
// ***********************************************************************
if( is_admin() )
{

	// ***********************************************************************
	// plugin version
	// ***********************************************************************
	$wxy_tools_media_replace_version = '1.0.0';


	/*************************************************************************
	* perform at: PLUGIN ACTIVATION
	*
	*/
	register_activation_hook( __FILE__, 'wxy_tools_media_replace_activation');

	function wxy_tools_media_replace_activation()
	{
		// turn on our media replace plugin
		update_option( "wxy_tools_media_replace_onoff", sanitize_text_field( "on" ), false );
		update_option( "wxy_tools_media_replace_messages", sanitize_text_field( "show" ), false );
	};

	/*************************************************************************
	* perform at: PLUGIN DE-ACTIVATION
	*
	*/
	register_deactivation_hook( __FILE__, 'wxy_tools_media_replace_deactivation');

	function wxy_tools_media_replace_deactivation()
	{
		// remove any stored options
		delete_option( "wxy_tools_media_replace_data" );
		delete_option( "wxy_tools_media_replace_onoff" );
		delete_option( "wxy_tools_media_replace_messages" );

	};


	/*************************************************************************
	* perform at: PLUGIN UN-INSTALL
	*
	*/
	register_uninstall_hook ( __FILE__, 'wxy_tools_media_replace_uninstall' );

	function wxy_tools_media_replace_uninstall()
	{
		// remove any stored options
		delete_option( "wxy_tools_media_replace_data" );
		delete_option( "wxy_tools_media_replace_onoff" );
		delete_option( "wxy_tools_media_replace_messages" );
	};

	// this sends any server data to the client-side JS
	add_action( 'admin_enqueue_scripts', 'wxy_tools_media_replace_send_to_js' );

	// hook to use current attachment upload to find existence of any possible duplicates
	// (happens before thumbnail/sizes generation)
	add_filter('wp_handle_upload_prefilter', 'wxy_tools_media_replace_upload_filter' );

	// retrieves any past attqachment uploads from WP option and restore their meta data, removes original (if needed)
	add_action( 'admin_init', 'wxy_tools_media_replace_update_meta' );
	
	// adds cache expiration meta tag to head of admin pages
	add_action( 'admin_head', 'wxy_tools_media_replace_add_to_head' );


	/*****************************************************
	* show current status of plugin in media and new-media screens
	* 
	*/
	add_action('admin_notices', 'wxy_tools_media_replace_admin_notice');

	function wxy_tools_media_replace_admin_notice()
	{
		global $pagenow;

		$show_on = array( "upload.php", "media-new.php" );


		// only show status messages if messages is set to: show
		$messages = get_option( "wxy_tools_media_replace_messages", null );
		$messages = sanitize_text_field( $messages );

		if ( $messages == null || $messages == "hide" )
		{
			return;
		}

		// these are the media libray page and the add new pages in the media section
		if ( in_array( $pagenow, $show_on ) )
		{
			// get our option value
			$status = get_option( "wxy_tools_media_replace_onoff", null );

			if( $status == null )
			{
				$status = "on";
			}

			if( $status != null )
			{
				echo '<div class="notice notice-warning is-dismissible">';

				// if the plugin in ON, show this
				if( $status == "on" )
				{
					?>
						<p class="wxy-tools-media-replace-css-p-on">Media Replace is:&nbsp;&nbsp;ON
						<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>

						<p class="wxy-tools-media-replace-css-p-off" style="display:none;">Media Replace is:&nbsp;&nbsp;OFF
						<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>
					<?php
					
				} else {

					?>
						<p class="wxy-tools-media-replace-css-p-on" style="display:none;">Media Replace is:&nbsp;&nbsp;ON
						<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>

						<p class="wxy-tools-media-replace-css-p-off">Media Replace is:&nbsp;&nbsp;OFF
						<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>
					<?php

				}

				// styles for all the above
				?>
					<style>
						.wxy-tools-media-replace-css-p-on { font-size:16px;font-weight:bold;color:#1e810e;background-color:#ace3a3;padding:0px;padding:8px !important; }
						.wxy-tools-media-replace-css-p-off { font-size:16px;font-weight:bold;color:#000;background-color:#FFF;padding:0px;padding:8px !important; }

						.wxy-tools-media-replace-info { width:auto;height:auto;position:relative;margin:0px 0px 0px 15px;display:inline-block;background-color:#EEE;color:#666;font-size:14px;padding:3px 15px;border:solid 1px #CCC;border-radius:6px !important; }
						.wxy-tools-media-replace-info:hover { cursor:pointer;color:#FFF;background-color:#666;border:solid 1px #000; }
					</style>
					
					<!-- close our container -->
					</div>
				<?php

			}
		}
	};

	/********************************************************
	* handle showing our current plugin status (on/off) in pages, posts, anywhere you can add meta boxes
	*
	*/
	add_action( "add_meta_boxes", "wxy_add_custom_meta_box" );
	
	function wxy_add_custom_meta_box()
	{
		// only add meta boxes if messages are set to: show
		$messages = get_option( "wxy_tools_media_replace_messages", null );
	
		if ( $messages != null && $messages == "show" )
		{
			add_meta_box( "wxy-media-replace-box", "Replace Media", "wxy_custom_meta_box_markup", array(), "side", "high", null);
		}
	}
	
	function wxy_custom_meta_box_markup()
	{
		?>
			<div>
		
				<p class="wxy-tools-media-replace-css-p-on">Media Replace is:&nbsp;&nbsp;ON
					<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>
				
				<p class="wxy-tools-media-replace-css-p-off" style="display:none;">Media Replace is:&nbsp;&nbsp;OFF
					<a href="<?php echo esc_url( admin_url() . 'options-general.php?page=wxy_tools_media_replace_settings_page' ) ?>" class="wxy-tools-media-replace-info">Settings</a></p>

			<!-- styles for all the above -->
				<style>
					.wxy-tools-media-replace-css-p-on { margin:0px;font-size:16px;font-weight:bold;color:#1e810e;background-color:#ace3a3;padding:0px;padding:8px !important;display:block;width:100%;height:auto;position:relative;text-align:center;box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;line-height:1.8em; }
					.wxy-tools-media-replace-css-p-off { margin:0px;font-size:16px;font-size:16px;font-weight:bold;color:#000;background-color:#FFF;padding:0px;padding:8px !important;display:block;width:100%;height:auto;position:relative;text-align:center;box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;line-height:1.8em; }
					
					.wxy-tools-media-replace-info { width:auto;height:auto;position:relative;margin:0px 0px 0px 10px;display:inline-block;background-color:#EEE;color:#666;font-size:14px;padding:3px 15px;border:solid 1px #999;border-radius:6px !important; }
					.wxy-tools-media-replace-info:hover { cursor:pointer;color:#FFF;background-color:#666;border:solid 1px #000; }
				</style>
				
			<!-- close our container -->
			</div>
		<?php
	}	

	/**
	* load cache expiration tag into head of admin pages
	* should only load in admin
	* 
	**/
	function wxy_tools_media_replace_add_to_head() {
		// add cache control meta for all admin pages (to cover anywhere they can upload attaachments)
		echo '<meta http-equiv="cache-control" content="no-cache">';
	};

	/**
	* checks to see if any meta data needs to be restored from previous upload attachment replacements
	* stored as an option in WordPress
	* 
	*/
	function wxy_tools_media_replace_update_meta()
	{
		global $wpdb;
		
		// load our option to see if any attachment resources need to be updated
		$result = get_option( "wxy_tools_media_replace_data", null );		
		
		// only run our operations if there is option data!
		if( $result != null )
		{
			$option = json_decode( $result, true );
		
			// option data is saved as an array of objects in json
			foreach( $option as $entry )
			{
				// find the new post to update
				$name = $entry[ "meta_data" ][ "file" ];
				$name = sanitize_text_field( $name );

				// see if we have an existing file that matches this upload's filename EXACTLY
				$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT `post_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_wp_attached_file' AND `meta_value` = %s", $name ));

				// if there is no  match, check for a post with a partial match since filenames may look like this: 2021/03/screenshot.png
				if( $post_id == null )
				{
					// look for a post that contains a partial match to our name using the "/" at the beginning of it
					$query = "SELECT `post_id` FROM `otw_freight_solutions`.`otw89_postmeta` WHERE `meta_key` = '_wp_attached_file' AND `meta_value` LIKE '%/".$name."'";

					$posts = $wpdb->get_results( $query, OBJECT );

					$post_id = $posts[0]->post_id;
					
					$post_id .= "XX";
				}

				// copy our file if it has duplicates
				$dups = isset( $entry[ "duplicates" ] ) ? $entry[ "duplicates" ] : null;
				$sizes = isset( $entry[ "meta_data" ][ "sizes" ] ) ? $entry[ "meta_data" ][ "sizes" ] : null;

				// see if we have duplicates! make copies
				if( count( $dups ) > 1 )
				{
					// first entry is always the source
					$src = array_shift( $dups );
					$src_path = str_replace( $name, "", $src );

					foreach( $dups as $dest )
					{
						// move the primary image (not thumbs) destination entries already have their path in the string
						if( file_exists( $size_src ) )
						{
							copy( $src, $dest );
						}

						// get a base path to use for copying the other sizes in the source location to the destination location
						$dest_path = str_replace( $name, "", $dest );

						// now copy the other sizes
						foreach( $sizes as $size )
						{
							$size_src = $src_path . $size[ "file" ];
							$size_dest = $dest_path . $size[ "file" ];

							if( file_exists( $size_src ) )
							{
								copy( $size_src, $size_dest );
							}
						}
					}
				}
				
				// now, update our "Alternative Text" field meta data with the old image's, post id, meta_key, meta_value
				$alt = $entry[ "alt_data" ];
				update_post_meta( $post_id, '_wp_attachment_image_alt', $alt );
				
				$meta = $entry[ "meta_data" ];
				update_post_meta( $post_id, '_wp_attachment_metadata', $meta );
				
				// next, update our post data
				$post_data = $entry[ "post_data" ];
				$args = array();
				
				$args[ "ID" ] = $post_id;
				$args[ "post_content" ] = $post_data[ "post_content" ];
				$args[ "post_title" ] = $post_data[ "post_title" ];
				$args[ "post_excerpt" ] = $post_data[ "post_excerpt" ];
				$args[ "post_status" ] = $post_data[ "post_status" ];
				$args[ "comment_status" ] = $post_data[ "comment_status" ];
				$args[ "ping_status" ] = $post_data[ "ping_status" ];
				$args[ "post_password" ] = $post_data[ "post_password" ];
				$args[ "to_ping" ] = $post_data[ "to_ping" ];
				$args[ "pinged" ] = $post_data[ "pinged" ];
				$args[ "post_content_filtered" ] = $post_data[ "post_content_filtered" ];
				$args[ "post_parent" ] = $post_data[ "post_parent" ];
				$args[ "menu_order" ] = $post_data[ "menu_order" ];
				$args[ "comment_count" ] = $post_data[ "comment_count" ];
				$args[ "filter" ] = $post_data[ "filter" ];

				$update = wp_update_post( $args, true );
			}
			
			// lastly, remove our option so it does not run repeatedly
			delete_option( "wxy_tools_media_replace_data" );
		
		} else {
			$option = array();
		}
	};
	
	/**
	* recursively search for a specific file
	*
	* @var	array	$result	matching entries and their paths
	* @arg	string	$dir	directory to search in for file
	* @arg	string	$find_file	filename to search for
	* @var	array	$result	list of matching files
	**/
	function wxy_tools_media_replace_find_file( $dir, $find_file)
	{
		$files = scandir($dir);
		$result = array();

		foreach ($files as $key => $value)
		{
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);

			if(!is_dir($path)) {

				if ( strpos( $value, $find_file ) > -1 )
				{
					array_push( $result, $path );
				}

			} else if ($value != "." && $value != "..") {

				$temp = wxy_tools_media_replace_find_file($path, $find_file);

				if ( $temp != null )
				{ 
					array_push( $result, $temp[0] );
				}
			}  
		 }

		 return $result;
	};
			

	/**
	* checks uploaded file to see if it already exists in our site's attachment posts
	* removes it if it exists, so WP will replace it instead of adding -1, -2, etc. to filename
	* 
	* @var object	$file	the uploaded file object passed from WP
	*/
	function wxy_tools_media_replace_upload_filter( $file ) {
		
		global $wpdb;

		// the current filename of the upload
		$name = $file[ "name" ];
		$name = sanitize_text_field( $name );
		
		// see if we have an existing file that matches this upload's filename EXACTLY
		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT `post_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_wp_attached_file' AND `meta_value` = %s", $name ));
		
		// if there is no  match, check for a post with a partial match since filenames may look like this: 2021/03/screenshot.png
		if( $post_id == null )
		{
			// look for a post that contains a partial match to our name using the "/" at the beginning of it
			$query = "SELECT `post_id` FROM `otw_freight_solutions`.`otw89_postmeta` WHERE `meta_key` = '_wp_attached_file' AND `meta_value` LIKE '%/".$name."'";

			$posts = $wpdb->get_results( $query, OBJECT );
			
			$post_id = $posts[0]->post_id;
		}

		// now get the rest of our data for this item
		$alt = $wpdb->get_var( $wpdb->prepare( "SELECT `meta_value` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_wp_attachment_image_alt' AND `post_id` = %s", $post_id ));
		
		$meta = $wpdb->get_var( $wpdb->prepare( "SELECT `meta_value` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_wp_attachment_metadata' AND `post_id` = %s", $post_id ));

		$meta = unserialize( $meta );

		// make sure to add the filename to the meta data, in case there is no data for this attachment
		$meta[ "file" ] = $name;

		// if there is a result, then there is a pre-existing entry
		if( $post_id != null && strlen( $post_id ) > 0 )
		{
			// get the rest of this attachment's meta data
			$post = get_post( $post_id, OBJECT );
			
			// if there is an exisiting post, delete it!
			if( $post != null )
			{
				// get our saved option containing meta data to restore from old attachment
				$option = get_option( "wxy_tools_media_replace_data", null );
				
				if( $option != null )
				{
					$option = json_decode( $option, true );
				} else {
					$option = array();
				}

				// transfer our field values from the old object to a new one for use later
				$post_data = array();
				$post_data[ "post_content" ] = $post->post_content;// description
				$post_data[ "post_title" ] = $post->post_title;
				$post_data[ "post_excerpt" ] = $post->post_excerpt;// title
				$post_data[ "comment_status" ] = $post->comment_status;
				$post_data[ "ping_status" ] = $post->ping_status;
				$post_data[ "to_ping" ] = $post->to_ping;
				$post_data[ "pinged" ] = $post->pinged;
				$post_data[ "post_content_filtered" ] = $post->post_content_filtered;
				$post_data[ "post_parent" ] = $post->post_parent;
				$post_data[ "menu_order" ] = $post->menu_order;
				$post_data[ "comment_count" ] = $post->comment_count;
				$post_data[ "filter" ] = $post->filter;
				$post_data[ "post_status" ] = $post->post_status;
				$post_data[ "post_password" ] = $post->post_password;									
				
				// look for duplicates in the uploads folder and save their locations for update later
				$dir_obj = wp_upload_dir();
				$dir = $dir_obj[ "path" ];
				$dups = wxy_tools_media_replace_find_file( $dir, $name);
		
				// assemble all our data into a single object
				$data = array();
				$data[ "post_data" ] = $post_data;
				$data[ "alt_data" ] = $alt;
				$data[ "meta_data" ] = $meta;
				$data[ "duplicates" ] = $dups;//paths to duplicate entries in other folder in uploads

				// add it to our array of data objects in the option array
				array_push( $option, $data );
				$option = json_encode( $option );

				// update our option with our new data
				update_option( "wxy_tools_media_replace_data", $option, false );
	
				// remove the old attachment (true = force delete)
				wp_delete_attachment( $post_id, true );
			}
		}
	
		// pass back the unaltered file object to complete the upload
		// when WP looks for a matching record, it will not find it and so will leave the filename unaltered
		return $file;
	}
	
	/**
	* enque JS and send any values needed client-side
	*
	*/
	function wxy_tools_media_replace_send_to_js()
	{	
		// be sure we are in the admin section
		global $wxy_tools_media_replace_version;

		// array to hold client-side JS key-values
		$data = array();

		// FORMS: this is the URL to send all forms to!
		$data["form_submission_path"] = admin_url( 'admin-ajax.php' );

		// the location of our javascript for the plugin
		$javascript = plugins_url('', __FILE__ ) . '/js/wxy-tools-media-replace-scripts.js';

		// register our script, localize it, then enqueue it
		wp_register_script( 'wxy-media-replace-ajax-js', $javascript, array( 'jquery' ), $wxy_tools_media_replace_version, true );
		wp_localize_script( 'wxy-media-replace-ajax-js', 'wxy_media_replace_admin_vars', $data );
		wp_enqueue_script( 'wxy-media-replace-ajax-js' );

	};
	
	/****************************************************************
	* AJAX: handle all form submissions here
	*
	*/
	add_action( 'wp_ajax_wxy_tools_media_replace_request', 'wxy_tools_media_replace_post_handler' );

	function wxy_tools_media_replace_post_handler()
	{	
		// see which action to take....
		$action = isset( $_POST[ "wxy_action" ] ) ? sanitize_text_field( $_POST[ "wxy_action" ] ) : null;

		// noting to do...
		$result = "fail";

		if( $action != null )
		{
		
			switch (true)
			{
				case $action == "status-on":
					// turn our plugin ON
					update_option( "wxy_tools_media_replace_onoff", sanitize_text_field( "on" ), false );
					
					$result = "success";
					break;
					
				case $action == "status-off":
					// turn our plugin OFF
					update_option( "wxy_tools_media_replace_onoff",  sanitize_text_field( "off" ), false );
					
					$result = "success";
					break;
					
				case $action == "messages-show":
					// turn our status messages ON (show)
					update_option( "wxy_tools_media_replace_messages",  sanitize_text_field( "show" ), false );
					
					$result = "success";
					break;
					
				case $action == "messages-hide":
					// turn our status messages OFF (hide)
					update_option( "wxy_tools_media_replace_messages",  sanitize_text_field( "hide" ), false );
					
					$result = "success";
					break;
			}
			

		}

		// return our result
		echo $result;
		exit();
	}


	/****************************************************************
	* ADD our settings and help page(S)...
	*
	*/
	add_action('admin_menu', 'wxy_tools_replace_plugin_create_menu');

	function wxy_tools_replace_plugin_create_menu() {

		//create new top-level menu
		//add_menu_page('My Cool Plugin Settings', 'Cool Settings', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );
		add_options_page('WXY Tools Media Replace > Settings', 'WXY Tools Media Replace', 'administrator', 'wxy_tools_media_replace_settings_page' , 'wxy_tools_media_replace_settings_page' );

		// call register settings function
	//	add_action( 'admin_init', 'register_wxy_tools_replace_settings' );

	}

	/****************************************************************
	* OPTIONS-CONTROL PANE: this is where all the user-facing controls are...
	*
	*/
	function wxy_tools_media_replace_settings_page()
	{
		// include our external file
		include( "options/wxy-tools-media-replace-options.php" );
	}

	/**
	* register our options to save so the system can filter out extraneous data if sent to the server on POST's
	*
	*/
	/*
	function register_wxy_tools_replace_settings() {
		//register our settings
		register_setting( 'wxy-tools-media-replace-settings-group', 'wxy_tools_replace_autosave' );
	}
*/

};// close is_admin
?>