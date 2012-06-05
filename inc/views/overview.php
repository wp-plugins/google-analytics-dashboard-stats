<?php 
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.3
 * Project Name: Google Analytics Dashboard Stats
 */
?>
        <div class="clearfix" style="margin-bottom: 20px;">
			  <div class="stats first">
				 <p class="stat_title">Visits</p>
				 <p class="stat_number"><?php echo number_format($ga->getVisits()); ?></p>
			  </div>
			  <div class="stats last">
			     <p class="stat_title">Pageviews</p>
				 <p class="stat_number"><?php echo number_format($ga->getPageviews()); ?></p>
			  </div>
		</div>