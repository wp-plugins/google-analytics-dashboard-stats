<?php 
/**
 * @package MM_STATS_Functions
 * @version 1.5.2
 * Project Name: Google Analytics Dashboard Stats
 */
?>
        <div id="all_traffic" class="<?php echo $class; ?>">
			<strong>Top Traffic Sources (Visits/Pageviews)</strong><br />
			<?php
			foreach($ga->getResults() as $result):
			?>
            <span><?php echo $result; ?></span>	<span class="num">(<?php echo number_format($result->getVisits()); ?>/<?php echo number_format($result->getPageviews()); ?>)</span><br />
			<?php
			endforeach
			?>
		</div>