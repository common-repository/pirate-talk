<?php 
/*
Plugin Name: Pirate Day
Version: 1.1.1
Plugin URI: http://bandonrandon.wordpress.com/pirate-day/
Description: Makes your site talk like a pirate on pirate day, using http://developer.yahoo.net/blog/archives/2009/09/ahoy_mates_conv.html
Author: Brooke Dukes, John Tindell
Author URI: http://bandonrandon.com

*/

// Specify Hooks/Filters
register_activation_hook(__FILE__, 'tlapd_setting_defaults');
add_action('admin_init', 'tlapd_plugin_admin_init' );
add_action('admin_menu', 'tlapd_admin_menu');

// Define default option settings
function tlapd_setting_defaults() {
		$array = array("tlapd_show_message"=>"on", "tlapd_always_on"=>"Only on Talk Like a Pirate Day");
		update_option('tlapd_plugin_options', $array);
}

// Add sub page to the Settings Menu and load scripts
function tlapd_admin_menu() {
	add_options_page('Pirate Day', 'Pirate Day', 'manage_options','pirate_day', 'tlapd_admin_options');
} 

// Add Admin Settings to the sub page
function tlapd_admin_options() {
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
  
?>

<div class="wrap">
		<div class="icon32"><img src="<?php echo WP_PLUGIN_URL ?>/pirate-talk/inc/treasure-icon.png"/><br></div>
		<h2>Pirate Day Settin's</h2>
		<form action="options.php" method="post">
		<?php settings_fields('tlapd_plugin_options');?>
		<?php do_settings_sections('pirate_day'); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save \'Em'); ?>" />
		</p>
		</form>
		</div>
<?php
}
// Register our settings. Add the settings section, and settings fields
function tlapd_plugin_admin_init(){
register_setting( 'tlapd_plugin_options', 'tlapd_plugin_options', 'tlapd_settings_validate' );
add_settings_section('tlapd_plugin_main', 'Main Plugin settin\'s', 'tlapd_plugin_main_section_title','pirate_day');
add_settings_field('tlapd_setting_always_on', 'When to talk like a pirate?', 'tlapd_setting_always_on','pirate_day', 'tlapd_plugin_main');
} 

function tlapd_plugin_main_section_title() {
echo "<p>Edit t' settin's down yander to change how the plugin be working</p>";
} 


function tlapd_setting_show_message() {
	$options = get_option('tlapd_plugin_options');
	if($options['tlapd_show_message']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='show_message' name='tlapd_plugin_options[tlapd_show_message]' type='checkbox' />";
}

function tlapd_setting_always_on() {
	$options = get_option('tlapd_plugin_options');
	$items = array("Only on Talk Like a Pirate Day", "Always");
	foreach($items as $item) {
		$checked = ($options['tlapd_always_on']==$item) ? ' checked="checked" ' : '';
		echo "<label><input ".$checked." value='$item' name='tlapd_plugin_options[tlapd_always_on]' type='radio' /> $item</label><br />";
	}
}

// validate our options
function tlapd_settings_validate($input) {
	return $input; // return validated input
}

function tlapd_load_pirate(){ 
$pirate_script='<script src="http://l.yimg.com/d/lib/ydn/js/pirate1252961643.js"></script>';
$options = get_option('tlapd_plugin_options');
	if($options['tlapd_always_on']!="Always"){ //if we only want to talk like a pirate on pirate day
	$current_time =  current_time('mysql', '0'); //get current blog time
	$ts =strtotime($current_time); //parse the sql blog time to a php useable format
	$check_date = date('m/d', $ts);  // put the date in a format we can check
	$pirate_day = "09/19"; // this is pirate day matey 
		if((!is_admin()) && ($check_date == $pirate_day)){
			echo $pirate_script;}
		
	}
	else{ //we be pirate ever' day. 
		echo $pirate_script;
	}
}
add_action( 'wp_footer', 'tlapd_load_pirate' );

?>
