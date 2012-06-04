<?php
define('ga_email','mmattner@corvettecentral.com');
define('ga_password','ghvhmjqqepkavabs');
define('cc_profile_id','889881');

require 'gapi.class.php';

$ga = new gapi(ga_email,ga_password);

$dimensions  = array('source');
$metrics     = array('visits','pageviews','transactionrevenue',);
$sort_metric = '-visits';

$ga->requestReportData(cc_profile_id,      
                           $dimensions, 
                           $metrics, 
                           $sort_metric, 
                           $filter=null, 
                           $start_date=null, 
                           $end_date=null, 
                           $start_index=1, 
                           $max_results=10)
?>
<table>
<tr>
  <th>Source</th>
  <th>Visits</th>
  <th>Pageviews</th>
  <th>Revenue</th>
</tr>
<?php
foreach($ga->getResults() as $result):
?>
<tr>
  <td><?php echo $result ?></td>
  <td><?php echo $result->getVisits() ?></td>
  <td><?php echo $result->getPageviews() ?></td>
  <td><?php echo $result->getTransactionRevenue() ?></td>
</tr>
<?php
endforeach
?>
</table>

<table>
<tr>
  <th>Total Pageviews</th>
  <td><?php echo $ga->getPageviews() ?>
</tr>
<tr>
  <th>Total Visits</th>
  <td><?php echo $ga->getVisits() ?></td>
</tr>
<tr>
  <th>Revenue</th>
  <td><?php echo $ga->getTransactionRevenue() ?></td>
</tr>
</table>