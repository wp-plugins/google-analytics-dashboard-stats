<?php
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.5
 * Project Name: Google Analytics Dashboard Stats
.---------------------------------------------------------------------------.
|   Authors: Mike Mattner                                                   |
|   Copyright (c) 2012, Mike Mattner. All Rights Reserved.                  |
'---------------------------------------------------------------------------'
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
=====================================================================================
*/

class GADS_STATS_Dashboard {
    
	var $ga_email    = null;
	var $ga_password = null;
	var $ga_title    = null;
	var $ga_id       = null;
	var $plugin_dir  = null;
	var $options     = null;
    static $instance;
	
    public function __construct($in_dir,$in_options) {
		self::$instance    = $this;
		$this->options     = $in_options;
		$this->ga_email    = $this->options['email'];
	    $this->ga_password = $this->options['password'];
		$this->ga_title    = $this->options['label'];
		$this->ga_id       = $this->options['id'];
		$this->plugin_dir  = $in_dir;
	}			
	
	/*-------------------------------------------------'
	 * STATISTICS/ETC                                  '
	 *------------------------------------------------*/
    
	/*Date Range if None Given*/
	public function dateRange( $format = 'Y-m-d' ) {
		$end            = strtotime( '-1 day', time() );
		$start          = strtotime( '-30 days', $end );
		$dates          = array();
	    $dates['start'] = date($format,$start);
		$dates['end']   = date($format,$end);

	    return $dates;
    }
	
	/*Build Dashboard*/
	public function create_dashboard($start_date,$end_date,$version){
		?>
			<div class="stat_section">
			<strong>Stats for <?php echo $this->ga_title; ?></strong>
		<?php
		       $this->cc_count_stats($this->ga_id,$this->ga_title,$start_date,$end_date,$version);		        
		?>		
            </div>
			
		<?php
	}
	
	/* --------------------------------------------->
	 * Stat Modules
	 */
	public function cc_count_stats($profile_id,$title,$start_date,$end_date,$version){
			
			//CHART---------->
			$this->cc_stats_chart($profile_id,$title,$start_date,$end_date);
			 
			//OVERVIEW------->
			$this->cc_stats_overview($profile_id,$title,$start_date,$end_date);
			
			$att_b = '';
			if ($this->options['sources'] != 'false' || $this->options['content'] != 'false') {
			        if ($this->options['sources'] != 'false' && $this->options['content'] != 'false') {
					    $all_traffic_class = 'half-left';
						$top_content_class = 'half-right';
					} else {
					    $all_traffic_class = 'full';
						$top_content_class = 'full';
					}
					$att_b = ' small-border';
			?>
			    <div id="stat-container" class="clearfix">    		    
			    <?php 
			        //ALL TRAFFIC----->
					if ($this->options['sources'] == 'true') {
			            $this->cc_stats_all_traffic($profile_id,$title,$start_date,$end_date,$all_traffic_class);
					}
					
			        //TOP CONTENT---->
					if ($this->options['content'] == 'true') {
			            $this->cc_stats_top_content($profile_id,$title,$start_date,$end_date,$top_content_class);
					}
            
			    ?>
			    </div>
            <?php   
			}
			echo '<p class="small-attribution '.$att_b.'"><a href="options-general.php?page=gads-options">'.__('Settings', 'gads').'</a> | Version '.$version;
	}
	
	/*-----------------------\
	 * CHART ----------------|
	 *----------------------*/
    public function cc_stats_chart($profile_id,$title,$start_date,$end_date){
	        require_once 'gapi/gapi.class.php';
			$ga_chart = new gapi($this->ga_email,$this->ga_password);
			
			$ga_chart->requestReportData($profile_id, 
			                             array('date'),
										 array('visits','pageviews'), 
										 'date', 
                                         $filter=null, 
                                         $start_date, 
                                         $end_date, 
                                         $start_index=1,
										 $max_results=500);           
            $ch_result = $ga_chart->getResults();
									
			$tmp = $this->plugin_dir . '/inc/views/chart.php';

            ob_start();
	        include( $tmp );
	        $output = ob_get_contents();
	        ob_end_clean();
	        
	        echo $output;
	}
	
	/*-----------------------\
	 * OVERVIEW -------------|
	 *----------------------*/
    public function cc_stats_overview($profile_id,$title,$start_date,$end_date){
	        require_once 'gapi/gapi.class.php';
			$ga = new gapi($this->ga_email,$this->ga_password);
        
			$dimensions  = array('source');
			$metrics     = array('visits','pageviews');
			$sort_metric = '-visits';
                        			
			$ga->requestReportData($profile_id,      
                           $dimensions, 
                           $metrics, 
                           $sort_metric, 
                           $filter=null, 
                           $start_date, 
                           $end_date, 
                           $start_index=1, 
                           $max_results=50);
			
			
			$tmp = $this->plugin_dir . '/inc/views/overview.php';

            ob_start();
	        include( $tmp );
	        $output = ob_get_contents();
	        ob_end_clean();
	        
	        echo $output;
	}
	
	/*-----------------------\
	 * ALL TRAFFIC-----------|
	 *----------------------*/
	public function cc_stats_all_traffic($profile_id,$title,$start_date,$end_date,$class){
	        require_once 'gapi/gapi.class.php';
			$ga = new gapi($this->ga_email,$this->ga_password);
        
			$dimensions  = array('source');
			$metrics     = array('visits','pageviews');
			$sort_metric = '-visits';
                        			
			$ga->requestReportData($profile_id,      
                           $dimensions, 
                           $metrics, 
                           $sort_metric, 
                           $filter=null, 
                           $start_date, 
                           $end_date, 
                           $start_index=1, 
                           $max_results=5);
			
			$tmp = $this->plugin_dir . '/inc/views/all-traffic.php';
			
            ob_start();
	        include( $tmp );
	        $output = ob_get_contents();
	        ob_end_clean();
	        
	        echo $output;

	}
	
	/*----------------------------\
	 * TOP CONTENT ---------------|
	 *---------------------------*/
	public function cc_stats_top_content($profile_id,$title,$start_date,$end_date,$class){
	        
			require_once 'gapi/gapi.class.php';
            $gag = new gapi($this->ga_email,$this->ga_password);
        
			$dimensions  = array('pageTitle');
			$metrics     = array('visits','pageviews');
			$sort_metric = '-pageviews';
			$filter      = 'hostname!=www.parts123.com';
                        			
			$gag->requestReportData($profile_id,      
                           $dimensions, 
                           $metrics, 
                           $sort_metric, 
                           $filter, 
                           $start_date, 
                           $end_date, 
                           $start_index=1, 
                           $max_results=5);
			
            $tmp = $this->plugin_dir . '/inc/views/top-content.php';
			
            ob_start();
	        include( $tmp );
	        $output = ob_get_contents();
	        ob_end_clean();
	        
	        echo $output;
			
	}
		 
	
} //End class GADS_STATS_Dashboard
?>