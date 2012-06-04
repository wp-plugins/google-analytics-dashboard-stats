<?php
/**
 * @package MM_STATS_Functions
 * @version 1.5.1
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

class MM_STATS_Dashboard {
    
    var $properties = null;	
	var $plugin_dir = null;
    static $instance;
	
    public function __construct($in_properties) {
		self::$instance   = $this;
	    $this->properties = $in_properties;
		$this->plugin_dir = WP_PLUGIN_DIR . "/" . $this->properties['options']['directory'];
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
	
	public function buildTemplate($template) {
	    ob_start();
	    include( $template );
	    $output = ob_get_contents();
	    ob_end_clean();
	        
	    echo $output;
	}
	
	/*Build Dashboard*/
	public function create_dashboard($start_date,$end_date,$id){
		$properties = $this->properties;
		?>
			<div class="stat_section" id="<?php echo $id; ?>">
			<strong>Stats for <?php echo $this->properties[$id]['title']; ?></strong>
		<?php
		       $this->cc_count_stats($properties[$id]['id'],$properties[$id]['title'],$id,$start_date,$end_date);
        
		        echo '<p class="small-attribution"><a href="options-general.php?page=mm-ga-stats-options">'.__('Settings', 'mm_ga_stats').'</a> | Data provided by <a href="http://code.google.com/apis/analytics/docs/gdata/v3/gdataGettingStarted.html" target="_blank">Google Analytics API</a>.</p>';
		?>		
            </div>
			
		<?php
	}
	
	/* --------------------------------------------->
	 * Stat Modules
	 */
	public function cc_count_stats($profile_id,$title,$id,$start_date,$end_date){
			
			//CHART---------->
			$this->cc_stats_chart($profile_id,$title,$id,$start_date,$end_date);
			 
			//OVERVIEW------->
			$this->cc_stats_overview($profile_id,$title,$id,$start_date,$end_date);
			
			if (get_option('mm_ga_stats_sources') != 'false' || get_option('mm_ga_stats_content') != 'false') {
			        if (get_option('mm_ga_stats_sources') != 'false' && get_option('mm_ga_stats_content') != 'false') {
					    $all_traffic_class = 'half-left';
						$top_content_class = 'half-right';
					} else {
					    $all_traffic_class = 'full';
						$top_content_class = 'full';
					}
			?>
			    <div id="stat-container" class="clearfix">    		    
			    <?php 
			        //ALL TRAFFIC----->
					if (get_option('mm_ga_stats_sources') == 'true') {
			            $this->cc_stats_all_traffic($profile_id,$title,$id,$start_date,$end_date,$all_traffic_class);
					}
					
			        //TOP CONTENT---->
					if (get_option('mm_ga_stats_content') == 'true') {
			            $this->cc_stats_top_content($profile_id,$title,$id,$start_date,$end_date,$top_content_class);
					}
            
			    ?>
			    </div>
            <?php
			}
	}
	
	/*-----------------------\
	 * CHART ----------------|
	 *----------------------*/
    public function cc_stats_chart($profile_id,$title,$id,$start_date,$end_date){
	        require_once 'gapi/gapi.class.php';
			$ga_chart = new gapi(ga_email,ga_password);
			
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
    public function cc_stats_overview($profile_id,$title,$id,$start_date,$end_date){
	        require_once 'gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
        
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
	public function cc_stats_all_traffic($profile_id,$title,$id,$start_date,$end_date,$class){
	        require_once 'gapi/gapi.class.php';
			$ga = new gapi(ga_email,ga_password);
        
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
	public function cc_stats_top_content($profile_id,$title,$id,$start_date,$end_date,$class){
	        
			require_once 'gapi/gapi.class.php';
            $gag = new gapi(ga_email,ga_password);
        
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
		 
	
} //End class MM_STATS_Dashboard
?>