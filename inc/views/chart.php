<?php 
/**
 * @package GADS_STATS_Dashboard
 * @version 1.5.5
 * Project Name: Google Analytics Dashboard Stats
 */
?>
        <script type="text/javascript"> 
			google.load("visualization", "1", {packages:["corechart"]}); 
			google.setOnLoadCallback(drawChart);
			
			
			function drawChart() {   
			  var data = new google.visualization.DataTable();       
			  data.addColumn('string', 'Day');   
			  data.addColumn('number', 'Visits');
			  data.addColumn('number', 'Pageviews');
			  data.addRows([
			  <?php     
			  foreach($ch_result as $result) {         
			    echo '["'.date('M j',strtotime($result->getDate())).'", '.$result->getVisits().', '.$result->getPageviews().'],';     
			  }
			  ?>   
			]);
			var currentWidth = jQuery('#cc_chart').width();
			var chart = new google.visualization.AreaChart(document.getElementById('<?php echo $id; ?>_chart'));   
			  chart.draw(data, {width: currentWidth, height: 200, title: '',                     
			    colors:['#058dc7','#aadff3'],                     
			    areaOpacity: 0.1,                     
			    series: [{targetAxisIndex:0},{targetAxisIndex:1}],				
			    hAxis: {textPosition: 'in', showTextEvery: 5, slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
			    vAxes: [
				   {textPosition: 'in', slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } },
				   {textPosition: 'in', slantedText: false, textStyle: { color: '#058dc7', fontSize: 10 } }
				],	
			    pointSize: 5,                       
			    legend: 'in',                     
			    chartArea:{left:0,top:30,width:"100%",height:"100%"}   
			  }); 
			} // End of drawChart()
		</script>
		<div id="<?php echo $id; ?>_chart" class="chart">&nbsp;</div>