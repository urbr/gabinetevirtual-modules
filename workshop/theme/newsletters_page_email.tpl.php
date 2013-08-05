<div class="options_list">
	<ul class="compact">
		<li><a class="button" href="?q=newsletter" title="Boletins" alt="Boletins">Voltar para boletins</a></li>

	</ul>
</div>
<div class="options_list">
	<ul class="compact">
		<li><a class="button" href="?q=newsletter/email/create" title="Boletins" alt="Boletins">Adicionar novo contato</a></li>
		<li><a class="button" href="?q=import/importar_contatos">Importar planilha de contatos</a></li>
		<li><a class="button" href="?q=newsletter/filter">Filtros</a></li>
		<li><a class="button" href="?q=newsletter/email/dump/csv">Exportar emails cadastrados (Excel)</a></li>
	</ul>
</div>
<form name="search" action="?q=newsletter/user/search" method="POST">
	<p>Usuários na base: <a href="#" onclick="document.forms[0].submit();return false;"><?php print $users; ?></a></p>

	<div>
			<input type="hidden" value="20845" name="newsletter_id">
			<p>Busca por email: <input type="text" name="search_email"><input type="submit" value="buscar"> </p>
			<p>Busca por nome: <input type="text" name="search_name"><input type="submit" value="buscar"> </p>
			<p><a href="#" onclick="javascript:document.getElementById('filters').style.display = 'block'; return false">Filtrar no campo: </a></P>
	</div>
</form>

<?php if(isset($result)): ?>
	<div>
		<table border=2 cellpadding=5 cellspacing=5>
			<tr>
				<thread>
					<?php
						foreach($fields as $key => $value){
							$key = str_replace('field_', '', $key);
							print "<td><b>".strtoupper($key)."</b></td>";
						}
					?>
					<td><b>E-MAIL</b></td>
					<td><b>EDITAR</b></td>
				</thread>
			</tr>
				<?php
					foreach($result as $res){
						print "<tr>";
						$nid = (isset($res->nid)) ? $res->nid : $res->entity_id;
						$title = db_query("SELECT title FROM node WHERE nid = :nid", array("nid" => $nid))-> fetchField();
						foreach($fields as $key => $value){
							$key = str_replace('field_', '', $key);
							print "<td>";
							print db_query("SELECT field_".$key."_value 
												FROM field_data_field_$key
												WHERE entity_id = :nid", 
												array(':nid' => $nid))->fetchField();
							print "</td>";
						}
						print "<td>".$title."</td>";
						print "<td><a href='?q=newsletter/email/edit/".$nid."' style='color:#437EA1'>Editar</a> | 
						<a href='?q=newsletter/email/delete/".$nid."' style='color:#437EA1'>Excluir</a></td></tr>";
					}
				?>
		</table>
	</div>	
<?php endif; ?>
