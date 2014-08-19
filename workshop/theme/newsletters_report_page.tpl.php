<?php
drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/Chart.js');
drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/jquery-1.10.2.js');
drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/jquery-ui.js');
?>
<div id="box_container">
	<?php  foreach($log as $l){
		$title = db_query('SELECT title FROM node WHERE nid = :nid', array(':nid' => $l->email_id))->fetchField();
		$time = db_query('SELECT date FROM newsletter_log WHERE email_id = :email_id order by date asc limit 1', array(':email_id' => $l->email_id))->fetchField();
	
		$arr[date("d-m-Y", $time)][$time] = $l->count;

	}
        ?>
	 <?php ksort($arr); ?>
	<div id="tabs">
	<ul>
	   <?php foreach($arr as $key => $value): ?>
   	   <li><a href="#<?php print $key; ?>"><?php print $key; ?></a></li>
	   <?php endforeach; ?>
  	</ul>
	<?php foreach($arr as $key => $value): ?>
		<canvas id="<?php print $key; ?>" height="400" width="480"></canvas>
	<?php endforeach; ?>
	</div>
	<h2>Boletins</h2>
	<div id="content">


		<table class="newsletter_items">
			<tr>
				<td>Email</td>
				<td>Quantidade</td>

				<td>Horário</td>

			</tr>

			<?php
				foreach($log as $l){
					$title = db_query('SELECT title FROM node WHERE nid = :nid', array(':nid' => $l->email_id))->fetchField();
			                $time = db_query('SELECT date FROM newsletter_log WHERE email_id = :email_id order by date asc limit 1', array(':email_id' => $l->email_id))->fetchField();

					print ' <tr>';
					print ' 	<td>'. $title.'</td>';
					print ' 	<td>'.$l->count.'</td>';
					print ' 	<td>'.date("d-m-Y H:i", $time).'</td>';
					print ' </tr>';
				}
			?>

		</table>
	</div>
	
</div>

<?php $data = array(); ?>
<?php foreach($arr as $dia => $time): ?>
        <?php foreach($time as $timestamp => $total): ?>
                <?php @$label[date("d-m-Y", $timestamp)][date("H", $timestamp)] = date("H", $timestamp); ?>
                <?php @$data[date("d-m-Y", $timestamp)][date("H", $timestamp)] += $total; ?>
        <?php endforeach; ?>
<?php endforeach; ?>	

<script>
<?php $i = 0; ?>
<?php foreach($data as $key => $value): ?>
		<?php ksort($label[$key]); ?>
		 <?php ksort($data[$key]); ?> 
		var lineChartData<?php print $i; ?> = {
			labels : [<?php print '"' . implode(':00","', $label[$key]) . ':00"'; ?>],
			datasets : [
				{
					label: "<?php print $key; ?>",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php print '"' . implode('","', $data[$key]) . '"'; ?>]
				},
				
			]

		}
<?php $i++; ?>
<?php endforeach; ?>
	window.onload = function(){
		<?php $j = 0; ?>
		<?php foreach($data as $key => $value): ?>
			var ctx = document.getElementById("<?php print $key; ?>").getContext("2d");
			window.myLine = new Chart(ctx).Line(lineChartData<?php print $j; ?>, {
				responsive: true
			});
		<?php  $j++; ?>
		<?php  endforeach; ?>
	}

</script>
<script>
$(function() {
   $( "#tabs" ).tabs();
});
</script>
