<?php
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.3
 *
 * Plugin Name: Google Analytics Dashboard Stats
 * Plugin URI: http://mikemattner.com/project/
 * Description: By default, this displays an overview of visits and pageviews on your dashboard. You can also view top content, and top visit sources.
 * Author: Mike Mattner
 * Version: 1.5.3
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

class GADS_STATS_Functions {
   	var $plugin_dir = null;
	var $plugin_url = null;
	static $instance;
	
    public function __construct() {
		self::$instance = $this;
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->plugin_url = plugin_dir_url( __FILE__ );
		$this->init();
	}

	public function init() {
	    
		isset($_REQUEST['_wp_gads_nonce']) ? add_action('admin_init',array($this,'gads_options_save') ) : null;  
		add_filter( 'plugin_action_links', array($this,'gads_plugin_action_links'), 10, 3 ); // add settings page to menu
		add_action( 'admin_menu', array($this,'gads_options_menu') ); // options page
		add_action( 'admin_init', array($this,'gads_set_defaults') ); // set default values on first run
		
        add_action( 'wp_dashboard_setup', array($this,'my_custom_dashboard_widgets') ); //add site specific dashboard widgets
		
		add_action( 'admin_notices', array($this,'checkSettings') ); //display notice if not setup
		add_action( 'admin_enqueue_scripts', array($this,'ann_style') ); //add javascript, css to admin
		
		add_action('wp_head', array($this,'trackingCode') ); //Add Google Analytics Tracking Code
		
	}
	
	public function gads_set_defaults() { 
		/*	
	    * Inserts previous option values on first initialization for <1.5.3 upgrade compatability, otherwise
		* sets default options on initialization.
		
		<1.5.3 options:
		  'mm_ga_stats_email',
		  'mm_ga_stats_password',
		  'mm_ga_stats_prop_id',
		  'mm_ga_stats_prop_label',
		  'mm_ga_stats_sources',
		  'mm_ga_stats_content',
		  'mm_ga_stats_ga_check',
		  'mm_ga_stats_code'
		*/
		$test = get_option('mm_ga_stats_email');
		if( $test != FALSE ) {
		    $options = array(
		        'email'    => get_option('mm_ga_stats_email'),
			    'password' => get_option('mm_ga_stats_password'),
			    'id'       => get_option('mm_ga_stats_prop_id'),
			    'label'    => get_option('mm_ga_stats_prop_label'),
			    'sources'  => get_option('mm_ga_stats_sources'),
			    'content'  => get_option('mm_ga_stats_content'),
			    'ga_check' => get_option('mm_ga_stats_ga_check'),
			    'code'     => get_option('mm_ga_stats_code'),
		    );
			$this->gads_remove_old();
	    } else {
		    $options = array(
		        'email'    => '',
			    'password' => '',
			    'id'       => '',
			    'label'    => 'My Site',
			    'sources'  => 'false',
			    'content'  => 'false',
			    'ga_check' => 'false',
			    'code'     => '',
		    );
		}
        
        if( get_option( 'gads_email' ) == FALSE ) {	
		    update_option('gads_email', $options['email']);
		    update_option('gads_password', $options['password']);
		    update_option('gads_prop_id', $options['id']);
		    update_option('gads_prop_label', $options['label']);
            update_option('gads_sources', $options['sources']);
		    update_option('gads_content', $options['content']);
		    update_option('gads_ga_check', $options['ga_check']);
		    update_option('gads_code', $options['code']);
		}
	
	}

	function gads_remove_old() {
		// removes <1.5.3 options
		delete_option('mm_ga_stats_email');
		delete_option('mm_ga_stats_password');
		delete_option('mm_ga_stats_prop_id');
		delete_option('mm_ga_stats_prop_label');
		delete_option('mm_ga_stats_sources');
		delete_option('mm_ga_stats_content');
		delete_option('mm_ga_stats_ga_check');
		delete_option('mm_ga_stats_code');
	}
	
	/**
	* Returns current plugin version.
	*
	* @return string Plugin version
	*/
	public function plugin_get_version() {
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		return $plugin_version;
	}

	//display notice if no settings are set
	public function checkSettings(){
	    $check  = false;
		$errors = array();
		$i      = true;

		if(get_option('gads_email') == ''){
			$errors[] = 'Analytics Email';
			$check    = true;
		}

		if(get_option('gads_password') == ''){
			$errors[] = 'Analytics Password';
			$check    = true;
		}
			
		if(get_option('gads_prop_id') == ''){
			$errors[] = 'Property ID';
			$check    = true;
		}
			
	    if(get_option('gads_prop_label') == ''){
			$errors[] = 'Property Label';
			$check    = true;
		}
		
        $list = '';
        foreach ($errors as $a ) {
	       $list .= $a.', ';
        }
		$list = substr($list, 0, -2);
		
		$message = 'Please setup the following to use Google Analytics Dashboard Stats: '.$list.'. <a href="options-general.php?page=gads-options">Visit Settings Page</a>';
	    
		if(current_user_can('manage_options')) {
	        if ($check) {
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
	public function gads_plugin_action_links($links, $file) {
	    $plugin_file = basename(__FILE__);
	    if (basename($file) == $plugin_file) {
		    $settings_link = '<a href="options-general.php?page=gads-options">'.__('Settings', 'gads').'</a>';
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
	public function gads_options_save() {
		if(wp_verify_nonce($_REQUEST['_wp_gads_nonce'],'gads')) {
			if ( isset($_POST['submit']) ) {
				( function_exists('current_user_can') && !current_user_can('manage_options') ) ? die(__('Cheatin&#8217; uh?', 'gads')) : null;
								
				isset($_POST['gads_email'])      ? update_option('gads_email', stripslashes ( strip_tags($_POST['gads_email'] ) ))            : update_option('gads_email', '');
				isset($_POST['gads_password'])   ? update_option('gads_password', stripslashes ( strip_tags($_POST['gads_password'] ) ))      : update_option('gads_password', '');
				isset($_POST['gads_prop_id'])    ? update_option('gads_prop_id', stripslashes ( strip_tags($_POST['gads_prop_id'] ) ))        : update_option('gads_prop_id', '');
				isset($_POST['gads_prop_label']) ? update_option('gads_prop_label', stripslashes ( strip_tags($_POST['gads_prop_label'] ) ))  : update_option('gads_prop_label', '');
                isset($_POST['gads_sources'])    ? update_option('gads_sources', 'true')                                                      : update_option('gads_sources', 'false');
				isset($_POST['gads_content'])    ? update_option('gads_content', 'true')                                                      : update_option('gads_content', 'false');
				isset($_POST['gads_ga_check'])   ? update_option('gads_ga_check', 'true')                                                     : update_option('gads_ga_check', 'false');
				isset($_POST['gads_code'])       ? update_option('gads_code', stripslashes (html_entity_decode( $_POST['gads_code'] )) )      : update_option('gads_code', '');
				
			}
		}
	}

	/*
	 * Analytics Dashboard Stats Options Page
	 */
	public function gads_options_page() {
	   $tmp = $this->plugin_dir . '/inc/views/options-page.php';
	   
	   ob_start();
	   include( $tmp );
	   $output = ob_get_contents();
	   ob_end_clean();
	   
	   $message = 'Make sure to setup a unique application password. This is required to use this plugin with Core Reporting API 2.4.';
	   if(current_user_can('manage_options')) {
	      if (get_option('gads_email') == '') {
             $this->showMessage($message, true);
	      }
	   }
	   echo $output;
	   
	   $version = $this->plugin_get_version();
	   
	   echo '<p>Google Analytics Dashboard Stats ' . $version . '.</p>';
	   
	}
	
	/*
	 * Add Options Page to Settings menu
	 */
	public function gads_options_menu() {
	    global $gads_options_page;
		
		if(function_exists('add_submenu_page')) {
			$gads_options_page = add_options_page(__('Google Analytics Dashboard Stats', 'gads'), __('Analytics Stats', 'gads'), 'manage_options', 'gads-options', array($this,'gads_options_page'));
		    add_action('load-'.$gads_options_page, array($this,'gads_admin_add_help_tab') );
		}
	}
	
	public function gads_admin_add_help_tab() {
	    global $gads_options_page;
	    $screen = get_current_screen();
	    if ($screen->id != $gads_options_page)
		    return;
		
		$screen->add_help_tab( array(
            'id'      => 'gads-connect',
            'title'   => __('Google Analytics Account Login', 'gads'),
            'content' => "
			<p>In order to display Analytics data on your dashboard, you'll need to provide your Google Analytics account login data&mdash;but you can't user your normal password. The <a href=\"http://code.google.com/apis/analytics/docs/gdata/v3/gdataGettingStarted.html\" target=\"_blank\">Core Reporting API 2.4</a> requires that you setup a unique application password.</p>
			<p><strong>How do I do set up an application password?</strong> The process itself is actually pretty simple.</p>
			<ol>
			   <li>First you'll need to setup up <a href=\"https://support.google.com/accounts/bin/answer.py?hl=en&topic=1056283&answer=185839\" target=\"_blank\">2-Step verification</a> on your Google Account.</li>
			   <li>After you've followed the steps outlined in that article, follow the steps required to sign in using an <a href=\"https://support.google.com/accounts/bin/answer.py?hl=en&answer=185833&topic=1056283&ctx=topic\" target=\"_blank\">application specific password</a>.</li>
			</ol>
			<p>Once you've completed those steps, use the password you generate as your Analytics password.</p>
			",
        ));
		
		$screen->add_help_tab( array(
            'id'      => 'gads-property',
            'title'   => __('Profile Information', 'gads'),
            'content' => "
			<p>In order to show your data on the dashboard, you'll need to provide your Profile ID</p>
			<p><strong>Where can I find my 'profile id?'</strong> Finding your Profile ID is fairly easy. When logged into your Analytics account, and viewing the reports for the site you're planning to use on the dashboard, click on the 'Admin' tab. In the 'Admin' tab under 'Profiles' you'll click on 'Profile Settings' and your Profile ID should be right under Profile Name.</p>
			<p><strong>What is my 'profile label?'</strong> This is just a way to identify the account on the dashboard and has no real relation to Google Analytics data. Label it whatever you like.</p>
			",
        ));
		
		$screen->add_help_tab( array(
            'id'      => 'gads-display',
            'title'   => __('Dashboard Widget Display Options', 'gads'),
            'content' => "
			<p><strong>What gets displayed on my dashboard?</strong> The default widget displays a chart showing pageviews and visits, as well as the total numbers of the same. This plugin also provides the option to display the top 5 sources and top 5 most viewed pages. This is just a simple overview, but gives you a general idea of how your site is performing using basic standard metrics.</p>
			",
        ));

		$screen->add_help_tab( array(
            'id'      => 'gads-code',
            'title'   => __('Tracking Code', 'gads'),
            'content' => "
			<p>This is a very basic way to add tracking code to your site's <code>head</code> tags, so I suggest using the Asynchronous tracking method. Just copy and paste your code, make sure to enable the option, and you should be set. This <a href=\"http://support.google.com/googleanalytics/bin/answer.py?hl=en&answer=174090\" target=\"_blank\">Google Analytics support article</a> provides more information.</p>
			",
        ));
		
    }
	
	//add custom widget to dashboard
    public function my_custom_dashboard_widgets() {
        wp_add_dashboard_widget('custom_stat_widget', 'Google Stats', array($this,'dashboard_stats_item'));
    }
	
	//custom dashboard widget styles
    public function ann_style() {
		wp_register_script( 'jsapi', 'https://www.google.com/jsapi', false, null );
        wp_enqueue_script( 'jsapi' );
		wp_register_style( 'ga-stats', $this->plugin_url . '/assets/css/stats.css' );
        wp_enqueue_style( 'ga-stats' );
    }
	
	//...and finally our dashboard widget
	public function dashboard_stats_item() {
	    if ( get_option('gads_email') != '' && get_option('gads_password') != '' && get_option('gads_prop_id') != '' && get_option('gads_prop_label') != '' ) {
	        $cc_db = $this->plugin_dir . '/inc/class/class.dashboard.php';
	        require_once($cc_db);
		    $db = new GADS_STATS_Dashboard($this->plugin_dir);	 			 
			
            $version    = $this->plugin_get_version();			
		    $dates      = $db->dateRange();
		    $start_date = $dates['start'];
		    $end_date   = $dates['end'];
			 
		    $db->create_dashboard($start_date,$end_date,$version);
		} else {
		
		   echo '<a href="options-general.php?page=gads-options">'.__('Please update your Dashboard Analytics settings.', 'gads').'</a>';
		   
		}
    }
	
	public function trackingCode() {
	    if (get_option('gads_ga_check') == 'true') {
		   $tracking_code  = stripslashes( get_option('gads_code') );
		   echo "\n".'<!-- *** Google Analytics Tracking Code **** -->'."\n";
		   echo $tracking_code . "\n";
		   echo '<!-- *** /Google Analytics Tracking Code *** -->'."\n";
		}
	}
 
}

$mm_stats = new GADS_STATS_Functions();

?>