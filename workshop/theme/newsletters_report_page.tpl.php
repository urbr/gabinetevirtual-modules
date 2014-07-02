<div id="box_container">
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
					print ' 	<td>'.$title.'</td>';
					print ' 	<td>'.$l->count.'</td>';
					print ' 	<td>'.date("d-m-Y H:i", $time).'</td>';
					print ' </tr>';
				}
			?>

		</table>
	</div>
	
</div>	
