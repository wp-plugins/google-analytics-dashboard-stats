<?php
/**
 * @package MM_STATS_Functions
 * @version 1.5
 *
 * Plugin Name: Google Analytics Dashboard Stats
 * Plugin URI: http://mikemattner.com/project/
 * Description: By default, this displays an overview of visits and pageviews on your dashboard. You can also view top content, and top visit sources.
 * Author: Mike Mattner
 * Version: 1.5
 * Author URI: http://mikemattner.com/
 * License: GPL
 
=====================================================================================
Copyright (C) 2012 Mike Mattner

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================*/

require_once('inc/config.php');

class MM_STATS_Functions {
    
	var $properties = null;
	var $plugin_dir = null;
	var $plugin_url = null;
	static $instance;
	
    public function __construct($in_properties) {
		self::$instance = $this;
		$this->properties = $in_properties;
		$this->plugin_dir = WP_PLUGIN_DIR . "/" . $this->properties['options']['directory'];
		$this->plugin_url = plugin_dir_url( __FILE__ );
		$this->init();
	}

	public function init() {
	    
		isset($_REQUEST['_wp_mm_ga_stats_nonce']) ? add_action('admin_init',array($this,'mm_ga_stats_options_save') ) : null;  
		add_filter( 'plugin_action_links', array($this,'mm_ga_stats_plugin_action_links'), 10, 3 ); // add settings page to menu
		add_action( 'admin_menu',array($this,'mm_ga_stats_options_menu') ); // options page
		
        add_action( 'wp_dashboard_setup', array($this,'my_custom_dashboard_widgets') ); //add site specific dashboard widgets
		
		add_action( 'admin_notices', array($this,'checkSettings') ); //display notice if not setup
		
	}
	
	//display notice if no settings are set
	public function checkSettings(){
       $message = 'Setup Google Analytics Dashboard Stats to use. <a href="options-general.php?page=mm-ga-stats-options">Visit Settings Page</a>';
	   if(current_user_can('manage_options')) {
	      if (get_option('mm_ga_stats_email') == '') {
             $this->showMessage($message, true);
	      }
	   }
    }
    	
	/**
	 * Generic function to show a message to the user using WP's
	 * standard CSS classes to make use of the already-defined
	 * message colour scheme.
	 *
	 * @param $message The message you want to tell the user.
	 * @param $errormsg If true, the message is an error, so use
	 * the red message style. If false, the message is a status
	  * message, so use the yellow information message style.
	 */
	function showMessage($message, $errormsg = false)
	{
		if ($errormsg) {
			echo '<div id="message" class="error">';
		}
		else {
			echo '<div id="message" class="updated fade">';
		}

		echo "<p><strong>$message</strong></p></div>";
	}  
	
	//Add settings option on plugins page
	public function mm_ga_stats_plugin_action_links($links, $file) {
	    $plugin_file = basename(__FILE__);
	    if (basename($file) == $plugin_file) {
		    $settings_link = '<a href="options-general.php?page=mm-ga-stats-options">'.__('Settings', 'mm_ga_stats').'</a>';
		    array_unshift($links, $settings_link);
	    }
	    return $links;
    }
	
	/* ========================================== */
	/* === Analytics Dashboard Stats SETTINGS === */
	/* ========================================== */

	/*
	 * Analytics Dashboard Stats Admin Options Save
	 */
	public function mm_ga_stats_options_save() {
		if(wp_verify_nonce($_REQUEST['_wp_mm_ga_stats_nonce'],'mm_ga_stats')) {
			if ( isset($_POST['submit']) ) {
				( function_exists('current_user_can') && !current_user_can('manage_options') ) ? die(__('Cheatin&#8217; uh?', 'mm_ga_stats')) : null;
								
				isset($_POST['mm_ga_stats_email'])      ? update_option('mm_ga_stats_email', stripslashes ( strip_tags($_POST['mm_ga_stats_email'] ) ))            : update_option('mm_ga_stats_email', '');
				isset($_POST['mm_ga_stats_password'])   ? update_option('mm_ga_stats_password', stripslashes ( strip_tags($_POST['mm_ga_stats_password'] ) ))      : update_option('mm_ga_stats_password', '');
				isset($_POST['mm_ga_stats_prop_id'])    ? update_option('mm_ga_stats_prop_id', stripslashes ( strip_tags($_POST['mm_ga_stats_prop_id'] ) ))        : update_option('mm_ga_stats_prop_id', '');
				isset($_POST['mm_ga_stats_prop_label']) ? update_option('mm_ga_stats_prop_label', stripslashes ( strip_tags($_POST['mm_ga_stats_prop_label'] ) ))  : update_option('mm_ga_stats_prop_label', '');
                isset($_POST['mm_ga_stats_sources'])    ? update_option('mm_ga_stats_sources', 'true')                                                             : update_option('mm_ga_stats_sources', 'false');
				isset($_POST['mm_ga_stats_content'])    ? update_option('mm_ga_stats_content', 'true')                                                             : update_option('mm_ga_stats_content', 'false');
			}
		}
	}

	/*
	 * Analytics Dashboard Stats Options Page
	 */
	public function mm_ga_stats_options_page() {
	   $tmp = $this->plugin_dir . '/inc/views/options-page.php';
	   
	   ob_start();
	   include( $tmp );
	   $output = ob_get_contents();
	   ob_end_clean();
	   
	   $message = 'Make sure to setup two part authentication on your account and setup a unique application password. This is required to use this plugin with Core Reporting API 2.4.';
	   if(current_user_can('manage_options')) {
	      if (get_option('mm_ga_stats_email') == '') {
             $this->showMessage($message, true);
	      }
	   }
	   echo $output;
	}
	
	/*
	 * Add Options Page to Settings menu
	 */
	public function mm_ga_stats_options_menu() {
		if(function_exists('add_submenu_page')) {
			add_options_page(__('Analytics Stats', 'mm_ga_stats'), __('Analytics Stats', 'mm_ga_stats'), 'manage_options', 'mm-ga-stats-options', array($this,'mm_ga_stats_options_page'));
		}
	}
	
	//add custom widget to dashboard
    public function my_custom_dashboard_widgets() {
	    add_action('admin_head', array($this,'ann_style'));
        wp_add_dashboard_widget('custom_stat_widget', 'Google Stats', array($this,'dashboard_stats_item'));
    }
	
	//custom dashboard widget styles
    public function ann_style() {
        echo '<link rel="stylesheet" href="' . $this->plugin_url . '/assets/css/stats.css">';
		echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
    }
	
	//...and finally our dashboard widget
	public function dashboard_stats_item() {
	    if ( get_option('mm_ga_stats_email') != '' && get_option('mm_ga_stats_password') != '' && get_option('mm_ga_stats_prop_id') != '' && get_option('mm_ga_stats_prop_label') != '' ) {
	        $cc_db = $this->plugin_dir . '/inc/class.dashboard.php';
	        require_once($cc_db);
		    $db = new MM_STATS_Dashboard($this->properties);
		    $id = 'property';	 			 
			 
		    $dates      = $db->dateRange();
		    $start_date = $dates['start'];
		    $end_date   = $dates['end'];
			 
		    $db->create_dashboard($start_date,$end_date,$id);
		} else {
		
		   echo '<a href="options-general.php?page=mm-ga-stats-options">'.__('Please update your Dashboard Analytics settings.', 'mm_ga_stats').'</a>';
		   
		}
    }
 
}

$mm_stats = new MM_STATS_Functions($properties);

?>