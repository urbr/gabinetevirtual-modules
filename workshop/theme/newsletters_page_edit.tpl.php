<script type="text/javascript" src="sites/all/libraries/tinymce_4.02b/js/tinymce/tinymce.min.js"></script>

<style type="text/css">

.button {
	-moz-border-radius:5px 5px 5px 5px;
	background:url("http://www.urucumbrasil.com.br/resources/pro-gabinete/images/bg_button_g.png") repeat-x scroll 0 0 transparent;
	color:#FFFFFF !important;
	display:inline-block;
	font-size:16px;
	margin-bottom:20px;
	padding:12px 20px 10px 16px;
	cursor:pointer;
	text-decoration:none !important;
	border:none;
}

textarea{
	font-family:'Lucida Sans Unicode', 'Lucida Grande', arial;
	margin-bottom:20px;
}

.button:hover {
	color:#CCC !important;
}


td img {
	display: block;
}


.boxNews{
	border-top:1px #CCC solid;
	margin-bottom:20px;
}

.boxNews LABEL{
	color:#666;
	display:block;
	font-size:14px;
	margin-bottom:5px;
	padding-top:30px;
}

.boxNews .textareaG{
	background:#F4F4F4;
	border: 1px solid #ccc;
	overflow:auto;
	padding:5px;
	width:505px;
}

.boxNews .textareaP{
	background:#F4F4F4;
	border: 1px solid #ccc;
	overflow:auto;
	padding:5px;
	width:170px;
}


.textareaG.publish_title{
	font-size:16px;
	font-weight:bold;
	height:80px;
}

.textareaG.description{
	font-size:14px;
	height:150px;
}

.textareaP.publish_title2{
	font-size:14px;
	font-weight:bold;
	height:80px;
	width:222px;
}

.textareaP.description{
	font-size:13px;
	height:150px;
	width:222px;
}

.outrosDestaques LABEL{
	color:#333;
	font-size:13px;
}

.outrosDestaques .description{
	background:#FFF;
	border:1px solid #CCC;
	font-size:13px;
	overflow:auto;
	padding:10px;
	width:140px;
}

</style>

<div style="background:#eee; padding:10px 0;">
	<form method="POST"  action="?q=newsletter/edit/save">
		<?php
			if(!empty($news_id))
				print '<input type="hidden" name="newsletter_id" value="'.$news_id.'">'; 
		?>
		<p align="center" style="font-family:'Lucida Sans Unicode', 'Lucida Grande', arial; font-size:13px; border:1px solid #FFEF97; width:735px; margin:0 auto; background:#FFFFCC; padding:10px;"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/ic_atencao.png" style="vertical-align:middle; margin-right:5px;" /> Aqui você pode <strong>editar</strong> os <strong>títulos</strong> e <strong>descrições</strong> antes de gerar o boletim.</a></p>

		<table width="778px" border="0" bgcolor="#FFFFFF" style="border-collapse:collapse; margin:10px auto; font-family:'Lucida Sans Unicode', 'Lucida Grande', arial; -moz-border-radius-bottomright:12px; -moz-border-radius-bottomleft:12px; -moz-border-radius-topright:12px; 	-moz-border-radius-topleft:12px;">
			<tr>
				<td width="20"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/spacer.gif" width="20" height="15" /></td>
				<td>&nbsp;</td>
				<td width="20"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/spacer.gif" alt="" width="20" height="15" /></td>
			</tr>
			<tr>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td colspan="2"><table width="100%" border="0" style="border-collapse:collapse;">
					<tr>
						<td valign="top">
									<?php 
										$i = 1;
										$others = array();
										foreach ($nodes as $node) {

											$node = (is_array($node)) ? $node[0] : $node;
											$content = (is_array($node)) ? $node[0]->content : (isset($node->body)) ? $node->body['und'][0]['value'] : $node->content;
											$content = (count((array)$nodes) == 1) ? $content : substr(drupal_html_to_text($content), 0, 170);
											$content_ = substr(drupal_html_to_text($content), 0, 110);

											if($i < 3){
												print '<table border="0" cellpadding="3" class="boxNews">';
											} else if($i < 5) {
												$style = ($i%2!=0) ? 'float:left' : 'float:right';
												print '<table border="0" style="'.$style.';" cellpadding="3" class="boxNews">';
											} elseif($i >= 5) {
												$others[$i][] = $node->nid; 
												$others[$i][] = $node->title;
												$others[$i][] = $content;
											}
											if($i < 5){
				                				print '       <tr>';
				                				print ($i < 3) ? '<td colspan="2">' : '<td colspan="3" valign="top">';
				                				print '				<label>Título da mat&eacute;ria '.$i.':</label>';
												$class = ($i < 3) ? 'publish_title textareaG' : 'publish_title2 textareaP';
												print '				<textarea class="'.$class.'" name="publish_title_'.$node->nid.'" rows="3" cols="28">'.$node->title.'</textarea>';
												print '			 </td>
															  </tr>
															  <tr>';
												$colspan = ($i < 3) ? 'colspan="2"' : '';
												print '		 	<td '.$colspan.' valign="top">';
												print '				<label>Descri&ccedil;&atilde;o da mat&eacute;ria '.$i.':</label>';
												$class = ($i < 3) ? 'description textareaG' : 'description textareaP';
												$cont = ($i < 3) ? $content : $content_;
												print '				<textarea class="'.$class.'" name="publish_body_'.$node->nid.'" rows="3" cols="28">'.$cont.'</textarea>';
												print '			 </td>
															  </tr>';
												print '	   </table>';
											}
											$i++;
										}
										if($i <= 2){
											?>
												<script type="text/javascript">
													tinyMCE.init({
													   mode : "textareas", 
													   theme : "modern",	
													   valid_elements :"*[*]",
													   editor_selector :"description textareaG",
												       height: "200",
													});
												</script>
											<?php
										}
									?>

									<td valign="top" width="231" border="0" style="border-collapse:collapse;">
										<table border="0" style="border-collapse:collapse; -moz-border-radius-bottomleft:10px; -moz-border-radius-topleft:10px;" height="100%">
											<tr>
												<td><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/spacer.gif" alt="" width="20" height="23" /></td>
												<td bgcolor="#EDEDED" id="colAux"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/spacer.gif" alt="" width="20" height="23" /></td>
												<td width="170" bgcolor="#EDEDED">

												<!-- Bloco -->
												<?php 
													if(true){
													    print '<table>
													        	 <tr>
									                     			<td colspan="2" style="padding-top:10px;"><h2><font size="2" color="#000000">OUTROS DESTAQUES</font></h2></td>
									                        	 </tr>
									                    	   </table>';
									                    foreach ($others as $key => $value) {

												            print '<table cellpadding="2" class="outrosDestaques">
														  		  		<tr>
												                    		<td valign="top">
																    		<label>T&iacute;tulo da mat&eacute;ria '.$key.':</label>
																    		<textarea class="description" name="publish_title_'.$others[$key][0].'" rows="3" cols="28">'.$others[$key][1].'</textarea></td>
										                	  	  		</tr>
											                   	   </table>';
											            }
											        }
											    ?>

													<table width="100%" style="margin-top:20px;">
														<tr>
															<td width="90">

																<p><a href="http://www.urucumbrasil.com.br"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/bt_site.gif" border="0" /></a></p></td>
																<td width="34"><!-- Redes Sociais -->

																	<p style="margin:0 0 12px 0; padding:0;"><a href="#"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/ic_twitter.gif" alt="Twitter" border="none;" /></a></p>
																	<p style="margin:0 0 12px 0; padding:0;"><a href="#"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/ic_facebook.gif" alt="Facebook" border="none;" /></a></p>
																</tr>
															</table>

															<!-- FIM COLUNA AUXILIAR --></td>
															<td bgcolor="#EDEDED"><img src="http://www.urucumbrasil.com.br/resources/newsletter/elvino/images/spacer.gif" alt="" width="20" height="23" /></td>

														</tr>
													</table></td>
												</tr>
											</table></td>
										</tr>
										<tr>

										</table>

										<table width="140" align="center">
											<tr>
												<td colspan="3"><input type="submit" class="button"></td>
											</tr>
										</table>

									</form>
								</div>