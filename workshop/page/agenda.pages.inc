<?php

function agenda_view(){

	global $user;

	if(!$user->uid){ drupal_goto('user'); }

	$events = db_select('agenda', 'ag')
	->fields('ag')
	->condition('ag.start_date', time()-31536000, '>=')
	->orderBy('created', 'DESC')
	->execute()
	->fetchAll();

	return theme('agenda_page', array('events' => $events));
}

function agenda_save(){

	global $user;

	if($user->uid){

		extract($_POST);

		try {

			$new = db_insert('agenda')
			->fields(array(
				'title' => $title,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'fname' => $user->name,
				'lname' => $user->name,
				'event_color' => $event_color,
				'text_color' => $text_color,
				'created' => time(),
				'status' => 1,
			))->execute();

		} catch(Exception $e){
			error();
		}
		sucess($new);
	} else {

                extract($_POST);

                try {

                        $new = db_insert('agenda')
                        ->fields(array(
                                'title' => $title,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'fname' => "Anônimo",
                                'lname' => "Anônimo",
                                'event_color' => $event_color,
                                'text_color' => $text_color,
                                'created' => time(),
				'status' => 0,
                        ))->execute();

                } catch(Exception $e){
                       error();
                }
                sucess($new);
	}
}

function agenda_update(){

	global $user;

	if($user->uid){
		if(isset($_POST['field']) && $_POST['field'] == 'date'){
			try{

				db_update('agenda')
				->fields(array(
					'start_date' => strtotime($_POST['start_date']),
					'end_date' => strtotime($_POST['end_date']),
				))
				->condition('id', $_POST['id'])
				->execute();

				sucess(1);

			} catch(Exception $e){
				echo json_encode(array('response' => 0)); die;
			}
		} else {
			drupal_goto("agenda");
		}
	}
	error();
}

function agenda_delete(){

	global $user;

	if($user->uid){
		if(isset($_POST['id'])){
			try{

				db_delete('agenda')
				->condition('id', $_POST['id'])
				->execute();

				sucess(1);

			} catch(Exception $e){
				echo json_encode(array('response' => 0)); die;
			}
		}
	}
	error();
}

function agenda_get_form($form, $id){
	return drupal_get_form($form, $id);
}

function agenda_update_form($form, &$form_state, $id){

	if(empty($id) || !is_numeric($id)) { drupal_goto("agenda"); }

	$event = db_select('agenda', 'ag')
	->fields('ag')
	->condition('ag.id', $id, '=')
	->execute()
	->fetchAll();

	foreach ($event as $ev) {

		$form['id'] = array(
			'#type' => 'hidden',
			'#default_value' => (!empty($id)) ? $id : "",
			'#required' => TRUE,
		);

		$form['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Título do evento'),
			'#description' => t('Digite um valor para o campo título'),
			'#default_value' => $ev->title,
			'#required' => TRUE,
		);

		$form['start_date'] = array(
		  '#type' => 'date_popup', 
		  '#default_value' => date("Y-m-d", $ev->start_date),
		  '#title' => t('Data inicial'),
		  '#description' => 'Dia inicial', 
		  '#required' => TRUE,
		);

		$form['end_date'] = array(
		  '#type' => 'date_popup', 
		  '#title' => t('Data final'),
		  '#default_value' => date("Y-m-d", $ev->end_date),
		  '#description' => 'Dia final', 
		  '#required' => TRUE,
		);
	}

    $form['enviar'] = array(
        '#type' => 'submit',
        '#value' => '       Salvar       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );

	return $form;
}

function agenda_update_form_validate($form, &$form_state){

	if(!empty($form_state['values']['id'])){
		if (!form_get_errors()){
			return;
		}
	}

	drupal_goto("agenda");
}

function agenda_update_form_submit($form, &$form_state){
	try{

		db_update('agenda')
		->fields(array(
			'title' => $form_state['values']['title'],
			'start_date' => strtotime($form_state['values']['start_date']),
			'end_date' => strtotime($form_state['values']['end_date']),
		))
		->condition('id', $form_state['values']['id'])
		->execute();

	} catch(Exception $e){
		form_set_error('error', 'Ocorreu um erro inesperado ao atualizar esse evento!');
	}

	drupal_set_message("Evento alterado com sucesso!");
	drupal_goto("agenda");
}

function sucess($value){
	echo json_encode(array('response' => $value)); die;
}

function error(){
	echo json_encode(array('response' => 0)); die;
}
