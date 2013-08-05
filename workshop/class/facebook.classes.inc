<?php

/**
 * @file
 * Facebook Class
 * 
 * @package          Gabinete Virtual
 * @copyright        Copyright (c) 2013, Urucum Brasil
 * 
 * @author           Fellipe
 * @description
 *   Classe responsável por acessar a API do Facebook
 */

class Facebook {

	private $redirect_uri;
	
	public function Facebook()
	{
		$this->redirect_uri = url("socialnetworks/facebook/", array('absolute'=>TRUE));
	}

	public function getUrlAuthFacebook()
	{
		return "http://www.facebook.com/dialog/oauth?"
		   . "client_id=" . variable_get('workshop_facebook_app_id')
		   . "&redirect_uri=" . urlencode($this->redirect_uri)
		   . "&scope=publish_stream";
	}

	public function setAccessTokenFacebook($code)
	{
		if(!empty($code))
		{
		    $token_url="https://graph.facebook.com/oauth/access_token?"
		      . "client_id=" . variable_get('workshop_facebook_app_id')
		      . "&client_secret=" . variable_get('workshop_facebook_app_secret')
		      . "&redirect_uri=" . urlencode($this->redirect_uri)
		      . "&code=" . $code;

		    parse_str(file_get_contents($token_url), $params);

		    variable_set('workshop_facebook_access_token', $params['access_token']);
		    variable_set('workshop_facebook_uid', $this->getUserId($params['access_token']));

		    return (!empty($params)) ? true : false;
		}
		return false;
	}

	public function getUserId($access_token)
	{
	    $uid_url = "https://graph.facebook.com/me?fields=id&access_token=".$access_token;
	    return json_decode(file_get_contents($uid_url))->id;
	}

	public function getAllPages()
	{
		$pages_url = "https://graph.facebook.com/me/accounts?access_token="
			. variable_get('workshop_facebook_access_token');

		return json_decode(file_get_contents($pages_url))->data;
	}

	public function publishMessageFacebook($message = '', $link = '')
	{
		if(!empty($message) && !empty($link))
		{ 
		    $graph_url= "https://graph.facebook.com/"
		      . variable_get('workshop_facebook_uid') . "/feed?"
		      . "message=" . urlencode($message)
		      . "&link=" . urlencode($link)
		      . "&type=link"
		      . "&ref=foo"
		      . "&method=POST"
		      . "&access_token=" . variable_get('workshop_facebook_access_token');

		    parse_str(file_get_contents($graph_url), $params);

		    return (!empty($params)) ? true : false;
		}
		return false;
	}
}
?>