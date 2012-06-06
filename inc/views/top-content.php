<?php 
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.5
 * Project Name: Google Analytics Dashboard Stats
 */
?>
        <div id="top_content" class="<?php echo $class; ?>">
			<strong>Top Content (Pageviews)</strong><br />
			<?php
			foreach($gag->getResults() as $result):
			?>
            <span><?php echo $result; ?></span>	<span class="num">(<?php echo number_format($result->getPageviews()); ?>)</span><br />
			<?php
			endforeach
			?>
		</div>