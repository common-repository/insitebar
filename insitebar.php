<?php
/*
Plugin Name: InsiteBar Plugin for Wordpress
Plugin URI: http://www.insitebar.com/
Description: Add the InsiteBar social toolbar to your Wordpress website
Author: InsiteBar
Version: 0.6
Author URI: http://www.insitebar.com/
*/

// add the Insitebar menu page to admin
add_action('admin_menu', 'ib_plugin_menu');

// get the Insitebar options menu page
function ib_plugin_menu() {
  add_options_page('InsiteBar Options', 'InsiteBar', 'manage_options', 'ib-unique-identifier', 'ib_settings_page');
}

// displays the page content for the Test settings submenu
function ib_settings_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'ibsiteid';
    $hidden_field_name = 'vw_submit_hidden';
    $data_field_name = 'vw_site_id';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Congratulations !<br/><br/><br/> <br/>you have successfully added the Insite bar to your web site.<br/><br/><br/> the Insite bar is a social application web toolbar,<br/><br/><br/>to start adding more social application to your Insite bar visit the Insite applications market place at <a href="http://www.insitebar.com">insitebar.com</a>', 'menu-test' ); ?></strong></p></div>
<?php

    }
    // Now display the settings editing screen
    echo '<div class="wrap">';
    // header
    echo "<h2>" . __( 'Insite Bar Plugin Settings', 'menu-test' ) . "</h2>";
    // settings form
?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Enter your InsiteBar Site ID or register for free at www.insitebar.com:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
</p><hr />
<p>To change your Insite bar settings and add new applications login to the InsiteBar <a href="http://www.insitebar.com/admin" target="_blank">Admin Console</a></p>
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
}

// run VM mandated scripts
function ib_hello() {
	$ibsiteid = get_option("ibsiteid");
	if($ibsiteid == null){
		$ibsiteid = '84007836873fea6cce535cc9746e2c22';
	}
?>
<script type="text/javascript">
	var srcURL = 'http://saas.insitebar.com/vw';
	SL = {siteId : '<?php echo $ibsiteid ?>', renderQ: []};
	(function(){
		var d=document,e='createElement',a='appendChild',g='getElementsByTagName',i=d[e]('iframe');
		i.id='VW-iframe'; 
		i.style.display='none'; 
		i.width=i.height='1px';
		d[g]("body")[0][a](i);
		SL.x = function(w) { 
			var d=w.document, s=d[e]("script");
			s.type="text/javascript"; 
			s.async=true;
			s.src=('https:'==d.location.protocol?srcURL.replace('http:','https:') : srcURL)+'/lava/sociaLava.js.jsp?siteId='+SL.siteId;d[g]("head")[0][a](s);
		}; 
		var c = i.contentWindow.document;
		c.open().write('<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\"><body onload=\"parent.SL.x(window)\" style=\"margin:0\"></'+'body></html>');
		c.close();
	})();
	

	// push down fixed position wpadminbar 		
	function ib_fixAdmin() {
	  if (!VW && !VW.Options) {
		setTimeout(ib_fixAdmin , 2000);
	 	return;
	  }
	  if (VW.Options.WindowManager.location == 'top') { 
	  	var $el = document.getElementById('wpadminbar');
		if($el) $el.style.top='29px'; 
	  }  
	}
	
	setTimeout(ib_fixAdmin , 2000);
</script>

<?php
}

// add InsiteBar required scripts at footer (after body loads).
add_action('wp_footer', 'ib_hello');

?>