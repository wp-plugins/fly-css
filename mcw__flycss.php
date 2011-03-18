<?php
/*
Plugin Name: Fly CSS
Plugin URI: http://craigatx.com
Description: Add CSS to every page on front end or admin screens.
Author: Craig WIlliams
Version: .9
Author URI: http://craigatx.com
Copyright: 2011
Generator: http://www.craigatx.com/plugin-generator/
Generated: Wed, 16 Mar 2011 12:23:06 -0500

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	//die('Sorry, but you cannot access this page directly.');
}


if (!class_exists("mcw_FlyCSS")) {

class mcw_FlyCSS {
	// Class Variables

	var $localization_domain = "mcw__flycss";
	var $options_name = 'mcw__flycss_options';
	var $options = array();
	function __construct() {
	
		
		//Language Setup
		$locale = get_locale();
		$mo = dirname(__FILE__) . "/languages/" . $this->localization_domain . "-".$locale.".mo";
		load_textdomain($this->localization_domain, $mo);

		
		//Initialize the options
		//This is REQUIRED to initialize the options when the plugin is loaded!
		$this->get_options();
        
		if ( is_admin() ) {
			// Back end
			add_action("admin_menu", array(&$this,"admin_menu_link"));

			add_action("init", array(&$this,"enqueue_backend_files"));

		} else {
			// Front end

			add_action("init", array(&$this,"enqueue_frontend_files"));
		
		}

	}
	
	function mcw_FlyCSS() {
		$this->__construct();
	}
	
	function enqueue_backend_files() {
		wp_enqueue_style("mcw__flycss-frontend-css", plugin_dir_url( __FILE__ ) . "get_css.php?side=back");
	}	
	function enqueue_frontend_files() {
		wp_enqueue_style("mcw__flycss-frontend-css", plugin_dir_url( __FILE__ ) . "get_css.php?side=front");
	}
	/**
	* @desc Adds the options subpanel
	*/
	function admin_menu_link() {
		//If you change this from add_options_page, MAKE SURE you change the filter_plugin_actions function (below) to
		//reflect the page filename (ie - options-general.php) of the page your plugin is under!
		add_options_page('Fly CSS', 'Fly CSS', 10, basename(__FILE__), array(&$this,'admin_options_page'));
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
	}
	
	/**
	* Adds settings/options page
	*/
	function admin_options_page() { 
		if($_POST['mcw_-flycss-save']){
		if (! wp_verify_nonce($_POST['_wpnonce'], 'mcw_-flycss-update-options') ) 
			die('Whoops! There was a problem with the data you posted. Please go back and try again.'); 
		
		$this->options['custom_css'] = addcslashes(stripslashes($_POST['custom_css']), "'");
		$this->options['admin_custom_css'] = addcslashes(stripslashes($_POST['admin_custom_css']), "'");
			
		$this->save_admin_options();
		
		echo '<div class="updated"><p>Success! Your changes were sucessfully saved!</p></div>';
		}
		echo '<div class="wrap">
		<h2>Fly CSS</h2>
		<p>Fly CSS lets you add custom CSS to either the front end (every theme, every page) or the admin screens.  
		You can use it to temporarily tweak themes or hide annoying nag messages in the admin pages, among other things.</p>
		<form method="post" id="mcw_-flycss-options">';
		wp_nonce_field('mcw_-flycss-update-options');
		echo '<label for="custom_css">'.__('Enter custom Front End CSS here:', $this->localization_domain).'</label>';
		echo '<br />';
		echo '<textarea id="custom_css" name="custom_css" cols="55" rows="12">'.$this->options['custom_css'].'</textarea>';
		echo '<br /><br />';
		echo '<label for="admin_custom_css">'.__('Enter custom Back End CSS here:', $this->localization_domain).'</label>';
		echo '<br />';
		echo '<textarea id="admin_custom_css" name="admin_custom_css" cols="55" rows="12">'.$this->options['admin_custom_css'].'</textarea>';
		echo '<br />';
		echo '<p class="submit"><input type="submit" class="button" value="'.esc_attr( 'Submit', $this->localization_domain ).'" name="mcw_-flycss-save" /></p>';
		echo '</form>';
	}
	
	/**
	* @desc Adds the Settings link to the plugin activate/deactivate page
	*/
	function filter_plugin_actions($links, $file) {
	   //If your plugin is under a different top-level menu than Settiongs (IE - you changed the function above to something other than add_options_page)
	   //Then you're going to want to change options-general.php below to the name of your top-level page
	   $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
	   array_unshift( $links, $settings_link ); // before other links

	   return $links;
	}
	
	/**
	* Retrieves the plugin options from the database.
	* @return array
	*/
	function get_options() {
		//Don't forget to set up the default options
		if (!$the_options = get_option($this->options_name)) {
			$the_options = array('custom_css'=>'', 'admin_custom_css'=>'');
			update_option($this->options_name, $the_options);
		}
		$this->options = $the_options;
	}
	/**
	* Saves the admin options to the database.
	*/
	function save_admin_options(){
		return update_option($this->options_name, $this->options);
	}
        

	
	

} //End Class mcw_FlyCSS
} // end if

//instantiate the class
if (class_exists("mcw_FlyCSS")) {
    $mcw_FlyCSS_instance = new mcw_FlyCSS();
}
?>
