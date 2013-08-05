<?php

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ INICIO CONFIGURAÇÕES @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#

#FORMULÁRIO PRINCIPAL

function workshop_admin_settings()
{
  $form['workshop_menu'] = array(
    '#type'          => 'checkboxes',
    '#title'         => t('Funcionalidades disponíveis para o cliente'),
    '#options' 		   => array('Agenda' => t('Agenda'), 'Boletins' => t('Boletins'), 'Monitor' => t('Monitor'), 'Oficina de Criação' => t('Oficina de Criação'), 'Redes Sociais' => t('Redes Sociais')),
    '#default_value' => variable_get('workshop_menu', 0),
    '#description'   => t('Selecione as funcionalidades que estarão disponíveis para o cliente do Gabinete Virtual'),
    '#required'		   => TRUE,
  );

  $form['workshop_mail'] = array(
    '#type'          => 'textfield',
    '#title'         => t('E-mail da newsletter'),
    '#default_value' => variable_get('workshop_mail', 'gabinete-virtual@noreply.com'),
    '#description'   => t('E-mail que aparecerá como remetente na newsletter. Ex.: gabinete@virtual.com'),
    '#required' 	   => TRUE,
  );

  $form['workshop_mail_admin'] = array(
    '#type'          => 'textfield',
    '#title'         => t('E-mail do cliente'),
    '#default_value' => variable_get('workshop_mail_admin', ''),
    '#description'   => t('E-mail do cliente que receberá alertas do Gabinete Virtual'),
    '#required'      => TRUE,
  );

  $form['workshop_monitor_search'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Palavras monitoradas em redes sociais'),
    '#default_value' => variable_get('workshop_monitor_search', ''),
    '#description'   => t('Digite palavras que serão monitoradas no monitor do Gabinete Virtual. Ex.: Gabinete, Virtual, Urucum Brasil. OBS.: As palavras devem ser separadas por vírgula (,)'),
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_facebook_app_id'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Facebook App ID (Aplicativo)'),
    '#default_value' => variable_get('workshop_facebook_app_id', '408413069222439'),
    '#description'   => t('Informe o <b>App ID</b> do aplicativo do facebook. Parâmetro utilizado para publicações automáticas de matérias'),
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_facebook_app_secret'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Facebook App Secret (Aplicativo)'),
    '#default_value' => variable_get('workshop_facebook_app_secret', 'b87545ca904a6026b3bcf8cd3fa65b02'),
    '#description'   => t('Informe o <b>App Secret</b> do aplicativo do facebook. Parâmetro utilizado para publicações automáticas de matérias'),
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_facebook_uid'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Facebook User Id (Usuário)'),
    '#default_value' => variable_get('workshop_facebook_uid', ''),
    '#description'   => t('Este campo será preenchido automaticamente após o cliente configurar o Facebook com o aplicativo do Gabinete Virtual'),
    '#maxlength'     => 200,
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_facebook_access_token'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Facebook Access Token (Usuário)'),
    '#default_value' => variable_get('workshop_facebook_access_token', ''),
    '#description'   => t('Este campo será preenchido automaticamente após o cliente configurar o Facebook com o aplicativo do Gabinete Virtual'),
    '#maxlength'     => 200,
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_twitter_oauth_consumer_key'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Twitter oauth_consumer_key (Aplicativo)'),
    '#default_value' => variable_get('workshop_twitter_oauth_consumer_key', 'Wf8eATPuKApbaFIZTGD9Q'),
    '#description'   => t('Informe o <b>oauth_consumer_key</b> do aplicativo do Twitter. Parâmetro utilizado para publicações automáticas de matérias.'),
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_twitter_oauth_consumer_secret'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Twitter oauth_consumer_secret (Aplicativo)'),
    '#default_value' => variable_get('workshop_twitter_oauth_consumer_secret', 'QZoHvsrH8By0p5EgmFHEC4yiQcKD2BYtkAP3EvV4'),
    '#description'   => t('Informe o <b>oauth_consumer_secret</b> do aplicativo do Twitter. Parâmetro utilizado para publicações automáticas de matérias.'),
    '#required'      => FALSE,
  );

  $form['social_networks']['workshop_twitter_oauth_token'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Twitter oauth_token (Usuário)'),
    '#default_value' => variable_get('workshop_twitter_oauth_token', ''),
    '#description'   => t('Informe o <b>oauth_token</b> do cliente. Parâmetro utilizado para publicações automáticas de matérias.'),
    '#required'		   => FALSE,
  );

  $form['social_networks']['workshop_twitter_oauth_token_secret'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Twitter oauth_token_secret (Usuário)'),
    '#default_value' => variable_get('workshop_twitter_oauth_token_secret', ''),
    '#description'   => t('Informe o <b>oauth_token_secret</b> do cliente. Parâmetro utilizado para publicações automáticas de matérias.'),
    '#required'      => FALSE,
  ); 

  return system_settings_form($form);
}

function workshop_admin_settings_validate($form, &$form_state)
{
	if(!valid_email_address($form_state['values']['workshop_mail']))
		form_set_error("error", "Digite um e-mail válido!");
}