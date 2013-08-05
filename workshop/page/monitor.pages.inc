<?php
#@@@@@@@@@@@@@@@@@@@@@@@ INICIO DO MONITOR DE REDES SOCIAIS @@@@@@@@@@@@@@@@@@@@@@@@@#

# EXIBE O MONITOR COM BASE NOS PARAMETROS SETADOS EM CONFIGURAÇÕES

function monitor_view()
{
	$term = variable_get('workshop_monitor_search', '');
	return theme('monitor_page', array(
		'terms' => Workshop::encodeTerms($term), 
		'orig' => $term)
	);
}
# FIM DO MONITOR


# REALIZA UMA BUSCA COM BASE NOS PARAMETROS INFORMADOS PELO USUÁRIO

function monitor_search()
{
	return theme('monitor_page', array(
		'terms' => Workshop::encodeTerms($_POST['terms']),
		'orig' => $_POST['terms'])
	);
}
# FIM DA BUSCA