<?php

function socialnetworks_form($form, &$form_state){
	
	$facebook = new Facebook();

  $twitter = new Twitter(variable_get('workshop_twitter_oauth_consumer_key', null), 
    variable_get('workshop_twitter_oauth_consumer_secret', null));

	$accessToken = variable_get('workshop_facebook_access_token', '');
  $oauthToken  = variable_get('workshop_twitter_oauth_token', '');

  $form['facebook'] = array(
    '#prefix' => '<p><b><h1>' . t('Configurações de redes sociais') . '</h1></b></p>
    	<p><i>Selecione uma rede social abaixo para integrar com o Gabinete Virtual:</i></p><br/>',
    '#type' => 'fieldset',
    '#title' => t('<font color="#666" size="3">Facebook</font>'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  if(empty($accessToken)){
    $form['facebook']['login'] = array(
      '#type' => 'fieldset',
      '#title' => t('<b>1&deg; PASSO -&nbsp;</b> Autorizar aplicativo do Gabinete Virtual'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['facebook']['login']['login_link'] = array(
      '#prefix' => '<p><font color="green"><p>' . t('Atenção: Você precisa se conectar com o Facebook para autorizar o aplicativo do Gabinete Virtual.') . '</font></p>',
      '#type' => 'link',
      '#title' => t('Clique aqui para autorizar o aplicativo no Facebook'),
      '#href' => $facebook->getUrlAuthFacebook(),
    );
  }

  $form['facebook']['accounts'] = array(
    '#type' => 'fieldset',
    '#title' => t('<b>2&deg; PASSO -&nbsp;</b> Selecionar página do Facebook'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  if(!empty($accessToken)){

    $pages = array();
    foreach ($facebook->getAllPages() as $value) {
      $pages[$value->access_token] = $value->name.t(" (".$value->category.")");
    }
    if(empty($pages)){
      $pages[$accessToken] = "Perfil Pessoal - (Nenhuma página encontrada)";
    }

    $form['facebook']['accounts']['pages'] = array(
      '#type'          => 'radios',
      '#title'         => t('Selecione uma página'),
      '#options'       => $pages,
      '#description'   => t('Selecione uma página acima para publicar notícias automaticamente'),
      '#required'      => TRUE,
      '#validated'     => TRUE,
    );

    $form['facebook']['accounts']['send'] = array(
        '#type' => 'submit',
        '#value' => '       ENVIAR       ',
        '#prefix' => '<div class="form-button">',
        '#suffix' => '</div>'
    );
  }

  $form['twitter'] = array(
    '#type' => 'fieldset',
    '#title' => t('<font color="#666" size="3">Twitter</font>'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  if(empty($oauthToken)){
    $form['twitter']['login'] = array(
      '#type' => 'fieldset',
      '#title' => t('<b>1&deg; PASSO -&nbsp;</b> Autorizar aplicativo do Gabinete Virtual'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['twitter']['login']['login_link'] = array(
      '#prefix' => '<p><font color="green"><p>' . t('Atenção: Você precisa se conectar ao Twitter para autorizar o aplicativo do Gabinete Virtual.') . '</font></p>',
      '#type' => 'link',
      '#title' => t('Clique aqui para autorizar o aplicativo no Twitter'),
      '#href' => $twitter->getUrlAuthTwitter(),
    );
  } else {
    $form['twitter']['confirmed'] = array(
      '#prefix' => '<p><font color="green"><p>' . t('O Twitter está configurado!') . '</font></p>',
      '#type' => 'link',
      '#title' => t(''),
      '#href' => '',
    );
  }

	return $form;
}

function socialnetworks_form_validate($form, &$form_state){
  if(empty($form_state['values']['pages'])){
    form_set_error("error", "Selecione uma página do Facebook antes de prosseguir!");
    drupal_goto("socialnetworks");
  }
}

function socialnetworks_form_submit($form, &$form_state){
   variable_set('workshop_facebook_access_token', $form_state['values']['pages']);
   drupal_set_message("As configurações do Facebook foram finalizadas com sucesso!");
}


function socialnetworks_facebook(){
	if(!empty($_GET['code'])){

		variable_set('workshop_facebook_code', $_GET['code']);
		$facebook = new Facebook();

		if($facebook->setAccessTokenFacebook($_GET['code'])){
      variable_set('workshop_facebook_token_expiration', time() + 86400*30*2);
			drupal_set_message("Aplicativo autorizado com sucesso!<p>
        Por favor verifique o <b>2&deg; PASSO.</b></p>");
		} else {
      form_set_error("error", "Não foi possível obter nenhuma resposta do Facebook, por favor tente novamente mais tarde");
    }
	} else {
		form_set_error("error", "Não foi possível obter nenhuma resposta do Facebook, por favor tente novamente mais tarde");
	}
  drupal_goto("socialnetworks");
}

function socialnetworks_twitter(){
  $twitter = new Twitter(variable_get('workshop_twitter_oauth_consumer_key', null), 
      variable_get('workshop_twitter_oauth_consumer_secret', null));
  if(!empty($_GET['oauth_verifier']) && !empty($_GET['oauth_token'])){

    if($twitter->oauthVerifier($_GET['oauth_token'], $_GET['oauth_verifier'])){
      drupal_set_message("As configurações do Twitter foram finalizadas com sucesso!");
    } else {
      form_set_error("error", "Não foi possível obter nenhuma resposta do Twitter, por favor tente novamente mais tarde");
    }
  }
  drupal_goto("socialnetworks");
}