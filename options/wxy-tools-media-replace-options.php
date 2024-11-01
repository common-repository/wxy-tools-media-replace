<?php
	/*
		options and documentation for WXY Tools Stickyscroll Plugin
		(c)2016-Present Clarence "exoboy" Bowman and Bowman Design Works.
		WXY Tools™ at http://www.wxytools.com
		
	*/
	
	// load our options data
	$status = get_option( "wxy_tools_media_replace_onoff", null );
	$status = sanitize_text_field( $status );

	$messages = get_option( "wxy_tools_media_replace_messages", null );
	$messages = sanitize_text_field( $messages );
	
	if ($status == null )
	{
		$status = "on";
	}
	
	if ( $messages == null )
	{
		$messages = "show";
	}

?>

<style>
	.wxy-tools-media-replace-saving { width:auto;position:relative;display:none;left:10px;top:5px;color:#83090B; }
	.wxy-tools-media-replace-gif { position:relative;display:inline-block;top:5px; }
</style>

<!-- ============================= -->
<!-- page content starts here OPEN --><div style="width:80%;height:auto;position:relative;display:block;margin:0px auto;">
<br /><br /><h1>WXY Tools Media Replace</h1>
	
<p style="font-size:18px;">Currently, when you upload an item to the media library in WordPress, it looks to see if there are any other files previously uploaded that have the same filename as your upload.</p>
<p style="font-size:18px;">If there is a match, WordPress changes the new filename by adding a "-1", "-2", "-3", etc. to the end of it. So, "myfile.jpg" would become "myfile-1.jpg".</p>
<p style="font-size:18px;">This also means that you will have to find all of the links in your site that point to the old file and change them to the new file.</p>
<p style="font-size:18px;">With this utility, you do not need to do that any more.</p>
<p style="font-size:18px;">When Media Replace is turned on, it tells WordPress to replace the old file with the new one, instead of renaming it. That way, you can update an image and it will replace all occurances in your site at once. No muss, no fuss!</p>

<!-- content spacer --><div style="width:100%;height:25px;position:relative;display:block;float:none;clear:both;"></div>

<h1>Plugin Settings</h1>
<div style="widt:100%;height:auto;display:block;padding:30px;border:solid 1px #666;">
	<h2>Media Replace On/Off</h2>
	<p style="font-size:18px;">Turn this option on to tell WordPress to replace, not rename, matching upload filenames. Turning it off will go back to WordPress's normal renaming convention.</p>

	<div>
		<select class="wxy-tools-media-replace-status-select">

		<?php
			if ( $status == "on" )
			{
				?>
					<option value="on" selected>WXY Tools Media Replace is: ON</option>
					<option value="off">WXY Tools Media Replace is: OFF</option>'
				<?php
			} else {
				?>
					<option value="on">WXY Tools Media Replace is: ON</option>
					<option value="off" selected>WXY Tools Media Replace is: OFF</option>
				<?php
			}
		?>

		</select>

		<div class="wxy-tools-media-replace-saving">Saving <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/busy-timer-animation.gif' ) ?>" class="wxy-tools-media-replace-gif" /></div>
		
	</div>

	<!-- content spacer --><div style="width:100%;height:45px;position:relative;display:block;float:none;clear:both;"></div>

	<h2>Status Messages Hide/Show</h2>
	<p style="font-size:18px;">Select whether to show or hide the status messages that tell you whether Media Replace is on or off. We don't recommend hiding them because if you are working with more than one person on a site and they turn it off, you will not be able to tell, unless you come to this settings page. It is included merely as a convenience for those who really do not like having the status messages.</p>

	<div>

		<select class="wxy-tools-media-replace-messages-select">

		<?php
			if ( $messages == "show" )
			{
		?>
			<option value="show" selected>Show Status Messages</option>
			<option value="hide">Hide Status Messages</option>
		<?php
		
			} else {
		?>
			<option value="show">Show Status Messages</option>
			<option value="hide" selected>Hide Status Messages</option>
		<?php
			}
		?>

		</select>
		
		<div class="wxy-tools-media-replace-saving">Saving <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/busy-timer-animation.gif' ) ?>" class="wxy-tools-media-replace-gif" /></div>
	</div>

</div>

<!-- content spacer --><div style="width:100%;height:65px;position:relative;display:block;float:none;clear:both;"></div>
	
<h1>Love this Plugin?</h1>
<p style="font-size:18px;">Then send me buck (or more if you are feeling generous)! <br />Charge on PayPal will show up as 'Bowman Design Works', my parent company. By contributing, you will also be added to our direct support list where you can email us directly.</p>

<!-- content spacer --><div style="width:100%;height:25px;position:relative;display:block;float:none;clear:both;"></div>

<div id="paypal-button-container" style="width:350px;"></div>
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD" data-sdk-integration-source="button-factory"></script>
<script>
  paypal.Buttons({
      style: {
          shape: 'pill',
          color: 'gold',
          layout: 'vertical',
          label: 'pay',
          
      },
      createOrder: function(data, actions) {
          return actions.order.create({
              purchase_units: [{
                  amount: {
                      value: '1'
                  }
              }]
          });
      },
      onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
              alert('Transaction completed by ' + details.payer.name.given_name + '!');
          });
      }
  }).render('#paypal-button-container');
</script>

<!-- content spacer --><div style="width:100%;height:25px;position:relative;display:block;float:none;clear:both;"></div>
<!-- divider bar --><div style="width:90%;height:2px;background-color:#666;position:relative;"></div>
<!-- content spacer --><div style="width:100%;height:25px;position:relative;display:block;float:none;clear:both;"></div>
	<span style="font-size:18px;font-style:italic;display:block;width:65%;height:auto;position:relative;text-align:left;"><a href="http://www.wxytools.com">"WXY Tools"</a> and all content in this plugin are &copy;2016-Present Clarence "exoboy" Bowman and <a href="http://www.wxytools.com">wxytools.com</a> and may not be altered or sold without prior written permission.</span>

<!-- page content ends here CLOSE --></div>
<!-- ============================= -->