<?php

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ INICIO NEWSLETTER @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#

# INDEX DA NEWSLETTER

function newsletter(){

	$newsletters = db_select('newsletter', 'nl')->distinct()
	->fields('nl', array('news_id', 'created'))
	->orderBy('created', 'DESC')
	->execute()
	->fetchAll();

	$news = array();

	foreach($newsletters as $n) {

		$log = db_select('newsletter_log', 'nl')
			->fields('nl', array('email_id'))
			->condition('nl.news_id', $n->news_id)	
			->execute();
		$sent = db_select('newsletter_send_control', 'nl')
			->fields('nl', array('email_id'))
			->condition('nl.sent', 1)	
			->condition('nl.news_id', $n->news_id)	
			->execute();

             	$send = db_select('newsletter_send_control', 'nl')
			->fields('nl', array('email_id'))
			->condition('nl.news_id', $n->news_id)	
			->execute();


		$n->num = $log->rowCount();
		$n->sent_num = $sent->rowCount();
		$n->send_num = $send->rowCount();
		$news[] = $n;
	}

	return theme('newsletters_page', array('newsletters' => $news));
}
# FIM INDEX DA NEWSLETTER



#  NEWSLETTER REPORT

function newsletter_report($newsletter_id){

	$query = db_select('newsletter_log', 'nl')
			->fields('nl', array('email_id'));
	$query->addExpression('count(nl.email_id)', 'count');
	$query->condition('nl.news_id', $newsletter_id);
	$query->groupBy('nl.email_id');
	$query->orderBy('count', 'DESC');
	$log = $query->execute()->fetchAll();
	return theme('newsletters_report_page', array('log' => $log));
}
# END NEWSLETTER REPORT




#GERAR FORMULÁRIO DE CRIAÇÃO DE NEWSLETTER

function newsletter_create(){

	$result = db_select('node', 'n')
	->fields('n', array('nid', 'title', 'created'))	
	->condition('n.type', 'workshop')	
	->orderBy('nid', 'DESC')
	->execute();

	$newsletter = array();

	foreach ($result as $res) {
		$newsletter[] = array(
			'nid' => $res->nid,
			'title' => $res->title,
			'created' => $res->created
		);
	}

	return theme('newsletters_page_create', array('newsletters' => $newsletter));
}
#FIM GERAR FORMULÁRIO DE CRIAÇÃO DE NEWSLETTER


#GERAR FORMULÁRIO DE EDIÇÃO (APÓS SER CRIADO)

function newsletter_edit(){

	if(!empty($_POST['news_items'])){
		$nids = explode(" ", $_POST['news_items']);

		$result = new stdClass();

		foreach ($nids as $nid) {
			if(is_numeric($nid))
				$result->$nid = node_load($nid);
		}
		return theme('newsletters_page_edit', array('nodes' => $result));
	} else {
		drupal_goto("/newsletter/create");
	}
}
#FIM GERAR FORMULÁRIO DE EDIÇÃO (APÓS SER CRIADO)


#SALVAR A EDIÇÃO DE UM NEWSLETTER

function newsletter_edit_save(){

	global $user;

	$news_id = isset($_POST['newsletter_id']) ? $_POST['newsletter_id'] : hash("adler32", time());

	foreach($_POST as $key => $value){
		$nid = explode('publish_title_', $key);
		if(array_key_exists(1, $nid))
			if(is_numeric($nid[1])){
				@$title[$nid[1]]   = $_POST['publish_title_'.$nid[1]];
				@$content[$nid[1]] = $_POST['publish_body_'.$nid[1]];
			}
	}
	foreach ($title as $key => $value) {
		try{
			$content[$key] = empty($content[$key]) ? '&nbsp;' : $content[$key];

			if(!isset($_POST['newsletter_id'])){
				$new = db_insert('newsletter')
				->fields(array(
					'uid' => $user->uid,
					'nid' => $key,
					'title' => $value,
					'content' => $content[$key],
					'created' => time(),
					'news_id' => $news_id,
				))->execute();
			} else {
				db_update('newsletter')
				->fields(array(
					'uid' => $user->uid,
					'title' => $value,
					'content' => $content[$key],
					'created' => time()
				))->condition('nid', $key)
				->condition('news_id', $news_id)
				->execute();
			}
		}catch(Exception $e){
			drupal_goto("/newsletter/create");
		}
	}

	drupal_set_message("Edição realizada com sucesso!");
	drupal_goto("/newsletter/item/".$news_id);
}
#FIM SALVAR A EDIÇÃO DE UM NEWSLETTER


#GERAR FORMULÁRIO DE EDIÇÃO DE NEWSLETTER PELO ID

function newsletter_edit_item($newsletter_id){

	if(!empty($newsletter_id)){

		$query = db_select('newsletter', 'nl')
		->fields('nl', array('nid'))
		->condition('nl.news_id', $newsletter_id, '=')	
		->orderBy('id', 'ASC')
		->execute()
		->fetchAll();

		$result = new stdClass();

		foreach ($query as $res) {
				$nid = $res->nid;

				$result->$nid = db_select('newsletter', 'nl')
				->fields('nl', array('title', 'content'))
				->condition('nl.nid', $nid, '=')
				->condition('nl.news_id', $newsletter_id, '=')	
				->orderBy('nid', 'DESC')
				->execute()
				->fetchAll();
		}

		return theme('newsletters_page_edit', array('nodes' => $result, 'news_id' => $newsletter_id));

	} else {
		drupal_goto("/newsletter/create");
	}
}
#FIM MONTAR FORMULÁRIO DE EDIÇÃO DE NEWSLETTER PELO ID


#VISUALIZAR NEWSLETTER PELO ID

function newsletter_view($newsletter_id){

	$result = db_select('newsletter', 'nl')
	->fields('nl')
	->condition('nl.news_id', $newsletter_id, '=')
	->orderBy('nl.id', 'ASC')
	->execute()
	->fetchAll();

	foreach(($obj = $result) as $res){
		$title = $res->title;
		break;
	}

	if($result){
		print theme('newsletters_page_published', array('nodes' => $result, 'news_id' => $newsletter_id, 'title' => $title));
	} else {
		drupal_goto();
	}
}
#FIM VISUALIZAR NEWSLETTER PELO ID

#FORMULÁRIO P/ ENVIO DE E-MAIL

function newsletter_send($form, $news_id){
	return drupal_get_form($form, $news_id);
}

function newsletter_send_form($form, &$form_state, $news_id){

	$result = db_select('node', 'n')
	->fields('n', array('nid'))
	->condition('n.type', 'contatos', '=')
	->execute();

	$fields = field_info_instances("node", "contatos");

	foreach (getFieldsNewsletter() as $value) {
		if(isset($fields[$value]))
			unset($fields[$value]);
	}

	while ($res = $result->fetchObject()) {
		foreach ($fields as $key => $value) {

			$name = str_replace('field_', '', $key);

			$field = db_select("field_data_".$key, 'f')
				->fields('f', array("field_".$name."_value"))
				->condition('f.entity_id', $res->nid, '=')
				->execute()
				->fetchField();
			
			$values[$key][$field] = $field;
			$options[$key] = $name;
		}
	}

	$form['news_id'] = array(
		'#type' => 'hidden',
		'#value' => (!empty($news_id)) ? $news_id : null,
	);

	$form['field'] = array(
	    '#type' => 'fieldset',
	    '#collapsible' => FALSE,
	    '#collapsed' => FALSE,  
	);

	/*
	$form['field']['all'] = array(
	  '#type' => 'radios', 
	  '#default_value' => 1,
	  '#options' => array(0 => "Sim", 1 => "Não"),
	  '#prefix' => '<div class="form-item-wrapper"><font color="red">* SELECIONAR TODAS AS CATEGORIAS</font>',
	  '#tree' => TRUE,
	);
	*/

	//Comentar esse bloco caso o bloco acima seja descomentado
	$form['field']['all'] = array(
	  '#type' => 'hidden', 
	  '#default_value' => 1,
	  '#prefix' => '<div class="form-item-wrapper"><font color="red">* SELECIONE AS CATEGORIAS ABAIXO PARA ENVIAR O NEWSLETTER</font>',
	);

	if(isset($options)){
		foreach ($options as $key => $value) {
			$form['field'][$value] = array(
			  '#type' => 'checkboxes', 
			  '#options' => array("all" => "Todos") + $values[$key],
			  '#prefix' => '<p>&nbsp;</p><span class="form-label">'.strtoupper($value).' <span class="form-required-mark">(*)</span></span>',
			  '#tree' => TRUE,
			  '#required' => FALSE
			);
		}
	}

        $form['enviar'] = array(
        	'#type' => 'submit',
        	'#value' => 'Enviar',
        	'#prefix' => '<p>&nbsp;</p>',
        	'#suffix' => '</div>'
    	);

	return $form;
}

function newsletter_send_form_validate($form, &$form_state){
	$status = FALSE;
	if(!empty($form_state['values']['news_id'])){
		if (!form_get_errors()){
			if($form_state['values']['all'] == "1"){ //não selecionar nenhuma categoria
				foreach($form_state['values'] as $key => $value){
					if(is_array($form_state['values'][$key])){
						foreach($form_state['values'][$key] as $k){
							if($k != "0")
								$status = TRUE;
						}
					}
				}
			}
		}
	}

	if($form_state['values']['all'] == "1" && !$status)
		form_set_error("field", t("Selecione uma condição antes de enviar o e-mail!"));
}

function newsletter_send_form_submit($form, &$form_state){

	set_time_limit(0);

	$news_id = $form_state['values']['news_id'];

	$result = db_select('newsletter', 'nl')
	->fields('nl')
	->condition('nl.news_id', $news_id, '=')	
	->orderBy('nl.id', 'ASC')
	->execute()
	->fetchAll();

        foreach(($obj = $result) as $res){
                $title = $res->title;
                break;
        }

	$html = theme('newsletters_page_published', array('nodes' => $result, 'news_id' => $news_id, 'mail' => TRUE, 'title' => $title));

	foreach($result as $r){
		$subject = $r->title;
	}

	$where = "";
	$leftjoin = array();
	$entry = 0;

	if(@$form_state['values']['all']['all'] == 1){

		$fields = field_info_instances("node", "contatos");

		foreach (getFieldsNewsletter() as $value) {
			if(isset($fields[$value]))
				unset($fields[$value]);
		}

		$oldKey = "";

		foreach($fields as $key => $value){
			$key = str_replace('field_', '', $key);
			foreach ($form_state['values'][$key] as $k => $v){
				if($v){
					if($v != 'all'){
						$entry++;
						if($oldKey != $key)
							$leftjoin[] = "LEFT JOIN field_data_field_$key as $key on $key.entity_id = n.nid";
						$condition[] = "$key.field_".$key."_value = '$v'";
						$where = ($oldKey != $key) ? @implode(" AND ", $condition) : @implode(" OR ", $condition);
						$oldKey = $key;
					}
				}
			}
		}
	}

	$query = "SELECT distinct title, nid FROM node AS n ".implode(" ", $leftjoin)." WHERE ".$where;
	$type  = "n.type = 'contatos' AND status <> 0";
	$query = ($entry == 0) ? $query.$type : $query.' AND '.$type;

	$fail = $success = 0;
	foreach (db_query($query) as $res){
		$q = 'select email_id from newsletter_send_control where email_id = :email_id and news_id = :news_id';
		if(db_query($q, array(':email_id' => $res->nid, ':news_id' => $news_id))->rowCount() == 0) 
			(Workshop::queueMail($html, $res, $subject,$news_id)) ? $success++ : $fail++;
	}
	drupal_set_message("<p><b>Newsletter enviado com sucesso!</b></p><p><i>Relatório:</i></p>
		<p>Total de e-mails enviados: <b>$success</b>
		<br><font color='red'>Total de e-mails não enviados: <b>$fail</b></font></p>");
	drupal_goto("newsletter");
}
#FIM FORMULÁRIO P/ ENVIO DE E-MAIL

function newsletter_counter($newsletter_id, $email_string){

	$email = explode(".", $email_string);
	$email_id = $email[0];


	db_insert('newsletter_log') // Table name no longer needs {}
				->fields(array(
				  'news_id' => $newsletter_id,
				  'email_id' => $email_id,
				  'ip'  => ip_address(),
				  'date'  => time(),
				))->execute();
				
	// Serve file download.
  	drupal_add_http_header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
  	drupal_add_http_header('Content-Type', 'image/gif');
	drupal_add_http_header('Content-Transfer-Encoding', 'binary');
  	drupal_add_http_header('Content-Length', filesize('/tmp/t.gif'));
	readfile($_SERVER['DOCUMENT_ROOT'].'/'.drupal_get_path('module', 'workshop').'/images/t.gif');
  	drupal_exit();
}



#ENVIO DE NEWSLETTER (TESTE)

function newsletter_send_test($news_id){

	global $user;

	$result = db_select('newsletter', 'nl')
	->fields('nl')
	->condition('nl.news_id', $news_id, '=')	
	->orderBy('nl.id', 'ASC')
	->execute()
	->fetchAll();

        foreach(($obj = $result) as $res){
                $title = $res->title;
                break;
        }

	$html = theme('newsletters_page_published', array('nodes' => $result, 'news_id' => $news_id, 'mail' => TRUE, 'title' => $title));

	if($user->uid){
		if(Workshop::sendMail($html, $user, "BOLETIM VIRTUAL - TESTE"))
			drupal_set_message("Um newsletter de teste foi enviado para o e-mail: <b>".$user->mail."</b>");
		else
			form_set_error("error", "Não foi possível enviar esse newsletter por e-mail!");
	} else {
		form_set_error("error", "Você não está logado!");
	}
	drupal_goto("newsletter");
}
#FIM ENVIO NEWSLETTER (TESTE)


#FORMULÁRIO P/ DELETAR UM BOLETIM

function newsletter_delete($form, $news_id){
	return drupal_get_form($form, $news_id);
}

function newsletter_delete_form($form, &$form_state, $news_id){

	$form['news_id'] = array(
		'#type' => 'hidden',
		'#value' => (!empty($news_id)) ? $news_id : null,
	);

	$form['field']['action'] = array(
	  '#type' => 'radios',
	  '#default_value' => 1,
	  '#options' => array(0 => "Sim", 1 => "Não"),
	  '#prefix' => '<div class="form-item-wrapper"><span class="form-label">
	  	Você deseja realmente excluir esse Newsletter
	  	<span class="form-required-mark">(*)</span>',
	  '#suffix' => '</span>',
	  '#tree' => TRUE,
	);

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       OK       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

	return $form;
}

function newsletter_delete_form_validate($form, &$form_state){

	if(!empty($form_state['values']['news_id'])){
		if (!form_get_errors()){
			return;
		}
	}

	drupal_goto("newsletter");
}

function newsletter_delete_form_submit($form, &$form_state){

	try{
		if($form_state['values']['action'] == "0"){ //action = sim
			db_delete('newsletter')
			->condition('news_id', $form_state['values']['news_id'])
			->execute();
			drupal_set_message("Newsletter excluido com sucesso!");
		}
	} catch(Exception $e){
		drupal_goto("newsletter");
	}

	drupal_goto("newsletter");
}

#FIM FORMULÁRIO P/ DELETAR UM BOLETIM

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ FIM NEWSLETTER @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ INICIO BANCO DE E-MAILS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#


#RETORNA A INDEX DO BANCO DE E-MAILS

function newsletter_email(){

	$sum = db_query("SELECT COUNT(nid) FROM node WHERE type = 'contatos'")->fetchField();

	return theme("newsletters_page_email", array("users" => $sum, "filters" => NULL));
}

#FORMULÁRIO P/ CADASTRAR UM E-MAIL (USUÁRIO)

function newsletter_email_create($form){
	return drupal_get_form($form);
}

function newsletter_email_create_form($form, &$form_state){

	$fields = field_info_instances("node", "contatos");

	$form['email'] = array(
		'#type' => 'textfield',
		'#title' => t('E-MAIL'),
		'#description' => t('Digite um valor para o campo e-mail'),
		'#required' => TRUE,
	);

	foreach($fields as $key => $value){
		$key = str_replace('field_', '', $key);
		$form[$key] = array(
			'#type' => 'textfield',
			'#title' => t(strtoupper($key)),
			'#description' => t('Digite um valor para o campo '.$key),
			'#required' => FALSE,
		);
	}

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       OK       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

	return $form;
}

function newsletter_email_create_form_validate($form, &$form_state){
	if (!form_get_errors()){
		return;
	}
	drupal_goto("newsletter/email");
}

function newsletter_email_create_form_submit($form, &$form_state){
	try {

		global $user;

		$fields = field_info_instances("node", "contatos");

		$time = strtotime("now");
	    $newNode = (object) NULL;
	    $newNode->title = $form_state['values']['email'];
	    $newNode->type = 'contatos';
	    $newNode->uid = $user->uid;
	    $newNode->created = $time;
	    $newNode->changed = $time;
	    $newNode->status = 1;
	    $newNode->comment = 2;
	    $newNode->promote = 1;
	    $newNode->sticky = 0;
	    $newNode->language = 'und';
  
		node_object_prepare($newNode);

		node_save($newNode);

		foreach($fields as $key => $value){
			$k = str_replace('field_', '', $key);
			$v = $form_state['values'][$k];
			$v = (!is_numeric($v)) ? "'$v'" : $v;
			db_query("INSERT INTO field_data_field_$k VALUES ('node', 'contatos', 0, ".$newNode->nid.", ".$newNode->nid.", 'und', 0, $v, 'full_html')");
		}

		drupal_set_message("Contato adicionado com sucesso ao banco de emails!");

	} catch(Exception $e){
		form_set_error("error", "Aconteceu um erro inesperado ao cadastrar o contato! Por 
			favor tente novamente!");
	}
	drupal_goto("newsletter/email");
}
#FIM FORMULÁRIO P/ CADASTRAR UM E-MAIL (USUÁRIO)

#FORMULÁRIO P/ EDITAR UM E-MAIL (USUÁRIO)

function newsletter_email_edit($form, $entity_id){
	return drupal_get_form($form, $entity_id);
}

function newsletter_email_edit_form($form, &$form_state, $entity_id){

	$fields = field_info_instances("node", "contatos");

	$mail = db_query("SELECT title FROM node WHERE nid = :nid", 
		array(':nid' => $entity_id))->fetchField();

	$form['entity_id'] = array(
		'#type' => 'hidden',
		'#required' => TRUE,
		'#default_value' => (!empty($entity_id)) ? $entity_id : "",
	);

	$form['email'] = array(
		'#type' => 'textfield',
		'#title' => t('E-MAIL'),
		'#description' => t('Digite um valor para o campo e-mail'),
		'#required' => TRUE,
		'#default_value' => (!empty($mail)) ? $mail : "",
	);

	foreach($fields as $key => $value){
		$key = str_replace('field_', '', $key);

		$value = db_query("SELECT field_".$key."_value 
								FROM field_data_field_$key
								WHERE entity_id = :nid", 
								array(':nid' => $entity_id))->fetchField();
		$form[$key] = array(
			'#type' => 'textfield',
			'#title' => t(strtoupper($key)),
			'#description' => t('Digite um valor para o campo '.$key),
			'#required' => FALSE,
			'#default_value' => (!empty($value)) ? $value : "",
		);
	}

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       OK       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

	return $form;
}

function newsletter_email_edit_form_validate($form, &$form_state){
	if (!form_get_errors()){
		if(!empty($form_state['values']['entity_id']))
			return;
	}
	drupal_goto("newsletter/email");
}

function newsletter_email_edit_form_submit($form, &$form_state){

	try {

		$fields = field_info_instances("node", "contatos");

		db_query("UPDATE node SET title = :mail WHERE nid = :nid", 
			array(":mail" => $form_state['values']['email'], ":nid" => $form_state['values']['entity_id']));

		foreach($fields as $key => $value){
			$key = str_replace('field_', '', $key);
			$v = $form_state['values'][$key];
			$v = (!is_numeric($v)) ? "'$v'" : $v;
			db_query("UPDATE field_data_field_$key 
						SET field_".$key."_value = $v 
						WHERE entity_id = :eid", array(':eid' => $form_state['values']['entity_id']));
		}

		drupal_set_message("Edição realizada com sucesso!");

	} catch(Exception $e){
		form_set_error("error", "Aconteceu um erro inesperado ao editar este usuário! Por favor
			tente novamente!");
	}

	drupal_goto("newsletter/email");
}
#FIM FORMULÁRIO P/ EDITAR UM E-MAIL (USUÁRIO)


#FORMULÁŔIO P/ EXCLUIR UM E-MAIL (USUÁRIO)

function newsletter_email_delete($form, $entity_id){
	return drupal_get_form($form, $entity_id);
}

function newsletter_email_delete_form($form, &$form_state, $entity_id){

	$form['entity_id'] = array(
		'#type' => 'hidden',
		'#required' => TRUE,
		'#default_value' => (!empty($entity_id)) ? $entity_id : "",
	);

	$form['field']['action'] = array(
	  '#type' => 'radios',
	  '#default_value' => 1,
	  '#options' => array(0 => "Sim", 1 => "Não"),
	  '#prefix' => '<div class="form-item-wrapper"><span class="form-label">
	  	Você deseja realmente excluir esse e-mail?
	  	<span class="form-required-mark">(*)</span>',
	  '#suffix' => '</span>',
	  '#tree' => TRUE,
	);

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       OK       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

    return $form;
}

function newsletter_email_delete_form_validate($form, &$form_state){
	if (!form_get_errors()){
		if(!empty($form_state['values']['entity_id']))
			return;
	}
	drupal_goto("newsletter/email");
}

function newsletter_email_delete_form_submit($form, &$form_state){

	try{
		if($form_state['values']['action'] == "0"){ //action = sim
			node_delete($form_state['values']['entity_id']);
			drupal_set_message("E-mail excluido com sucesso!");
		}
	} catch(Exception $e){
		form_set_error("error", "Aconteceu um erro inesperado ao excluir esse e-mail! Por favor
			 tente novamente!");
	}

	drupal_goto("newsletter/email");
}

#FIM FORMULÁŔIO P/ EXCLUIR UM E-MAIL (USUÁRIO)


#FORMULÁRIO P/ CONFIGURAR FILTROS DO E-MAIL (USUÁRIO)

function newsletter_filter_form($form, &$form_state){

	$fields = field_info_instances("node", "contatos");

	foreach ($fields as $key => $value) {

		$status = db_select('newsletter_filter', 'nf')
		->fields('nf', array('status'))
		->condition('nf.field', $key)
		->execute()
		->fetchField();

		$form['hidden'] = array(
		  '#type' => 'hidden', 
		  '#default_value' => 1,
		  '#prefix' => '<div class="form-item-wrapper"><font color="red">* SELECIONE OS FILTROS DISPONÍVEIS PARA O ENVIO DE NEWSLETTERS</font></div><p>',
		);

		$form['fields'][$key] = array(
		  '#type' => 'radios',
		  '#default_value' => $status,
		  '#options' => array(1 => "Ativado", 0 => "Desativado"),
		  '#prefix' => '<div class="form-item-wrapper"><span class="form-label">
		  	'.strtoupper(str_replace('field_', '', $key)).'
		  	<span class="form-required-mark">(*)</span>',
		  '#suffix' => '</span></div>',
		  '#tree' => TRUE,
		);

	}

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       ENVIAR       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

    return $form;
}

function newsletter_filter_form_validate($form, &$form_state){
	if (!form_get_errors()){
		return;
	}
	drupal_goto("newsletter/filter");
}

function newsletter_filter_form_submit($form, &$form_state){

	$filters = db_select('newsletter_filter', 'nf')
	->fields('nf', array('field'))
	->execute()
	->fetchAll();

	$fields = field_info_instances("node", "contatos");

	try {

        foreach ($fields as $key => $value) {
            $update = db_update('newsletter_filter')
            ->fields(array(
                    'status' => @(int)$form_state['values'][$key]
            ))
            ->condition('field', $key)
            ->execute();

            if(!$update){
                db_insert('newsletter_filter')
                ->fields(array(
                        'field' => $key,
                        'status' => @(int)$form_state['values'][$key],
                ))->execute();
            }
        }

		drupal_set_message("Filtros configurados com sucesso!");

	} catch(Exception $e) {
		form_set_error("error", "Não foi possível salvar as suas configurações, tente novamente!");
	}
}

#FIM FORMULÁRIO P/ CONFIGURAR FILTROS DO E-MAIL (USUÁRIO)


#BUSCAR CAMPOS DESATIVADOS DO FILTRO

function getFieldsNewsletter(){

	$filters = array();

	$query = db_select('newsletter_filter', 'nf')
	->fields('nf', array('field'))
	->condition('nf.status', 0)
	->execute();

	while($field = $query->fetchObject()) {
		$filters[] = $field->field;
	}

	return $filters;
}

#FIM BUSCAR CAMPOS DESATIVADOS DO FILTRO


#FUNÇÃO P/ BUSCAR USUÁRIOS PELO NOME OU E-MAIL

function newsletter_user_search(){

	try {
		if(isset($_POST['search_email']) && !empty($_POST['search_email'])){

			@$mail = $_POST['search_email'];

			$result = db_query("SELECT nid FROM node 
								WHERE title LIKE '%".$mail."%'
								AND type = 'contatos' ORDER BY nid ASC");

		}elseif(isset($_POST['search_name']) && !empty($_POST['search_name'])){

			@$name = $_POST['search_name'];

			$result = db_query("SELECT entity_id FROM field_data_field_nome 
								WHERE field_nome_value LIKE '%".$name."%'
								ORDER BY entity_id ASC");

		} else {
			$result = db_query("SELECT nid FROM node WHERE type = 'contatos'");
		}
	} catch(Exception $e){
		drupal_goto("newsletter/email");
	}

	if($result){
		$sum = db_query("SELECT COUNT(nid) FROM node WHERE type = 'contatos'")->fetchField();
		$fields = field_info_instances("node", "contatos");
		return theme("newsletters_page_email", array("users" => $sum, "result" => $result, "fields" => $fields));
	}

	drupal_goto("newsletter/email");
}
#FIM FUNÇÃO P/ BUSCAR USUÁRIOS PELO NOME OU E-MAIL


#EXPORTAR EMAILS CADASTRADOS

function email_dump($type){
	switch ($type) {
		case 'csv':
		    Workshop::dumpCsv();
			break;
		default:
			drupal_goto("newsletter/email");
			break;
	}
    return;
}
#FIM EXPORTAR EMAILS CADASTRADOS


#CANCELAR INSCRIÇÃO

function newsletter_email_cancel($nid, $email){
	//Por segurança é necessário informar o NID e EMAIL do usuário
	try {
		db_update('node')
		->fields(array('status' => 0))
		->condition('nid', $nid)
		->condition('title', $email)
		->condition('type', 'contatos')
		->execute();
	} catch(Exception $e) {
		form_set_error("error", "Não foi possível cancelar a sua inscriçao! Por favor 
			tente novamente mais tarde!");
	}
	drupal_set_message("A inscriçao do e-mail: <b>$email</b> foi cancelada com sucesso!");
	drupal_goto();
}
#FIM CANCELAR INSCRIÇÃO

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ FIM BANCO DE E-MAILS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#
