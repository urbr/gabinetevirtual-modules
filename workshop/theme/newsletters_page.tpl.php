<div id="box_container">
	<h2>Boletins</h2>
	<div id="content">

		<div class="options_list">
			<ul class="compact">

				<li><a class="button" href="?q=newsletter/create" title="Gerar Boletim" alt="Gerar Boletim">Gerar Boletim</a></li>
				<li><a class="button" href="newsletter-image-save?newsletter_id=314272" title="Gerar Convite" alt="Gerar Convite">Gerar Convite</a></li>
				<li><a class="button" href="?q=newsletter/email" title="Banco de emails" alt="Banco de emails">Banco de emails</a></li>

			</ul>
		</div>

		<table class="newsletter_items">
			<tr>
				<td>Data</td>
				<td>Item</td>

				<td>Enviar</td>
				<td>Enviar teste</td>
				<td>Editar</td>
				<td>Excluir</td>
				<td>Relatório de envio</td>

			</tr>

			<?php
				foreach($newsletters as $news){
					$title = db_query('SELECT title, created FROM newsletter WHERE news_id = :nid', array(':nid' => $news->news_id))->fetchField();
					$title = implode(" ", array_slice(explode(" ", $title), 0, 12));
					print ' <tr>';
					print ' 	<td>'.date("d-m-Y", $news->created).'</td><td> <a href="?q=newsletter/item/'.$news->news_id.'">'.$title.'</a></td>';
					print ' 	<td><a class="button" href="?q=newsletter/send/'.$news->news_id.'">Enviar</a> </td>';
					print ' 	<td><a class="button" href="?q=newsletter/send/test/'.$news->news_id.'">Teste</a> </td>';
					print ' 	<td><a class="button" href="?q=newsletter/edit/item/'.$news->news_id.'">Editar</a></td>';
					print ' 	<td><a class="button" href="?q=newsletter/delete/'.$news->news_id.'">Excluir</a></td>';
					print ' 	<td>0</td>';
					print ' </tr>';
				}
			?>

		</table>
		<br/>
		<p><a class="button" href="?q=newsletter/email/create" title="Cadastre seu e-mail para receber este boletim" alt="Cadastre seu e-mail para receber este boletim">Cadastre seu e-mail para receber este boletim</a></p>
	</div>
	
</div>	