<?php

class WorkShop {
	
    function initialize() { }

    public static function queueMail($html = "", $object, $subject = 'BOLETIM VIRTUAL', $news_id){
    	if(!empty($html) && $object instanceof stdClass){

    		$mail = (isset($object->title)) ? $object->title : $object->mail;
    		$nid  = (isset($object->nid)) ? $object->nid : $object->uid;

		$html = str_replace("1change_email1", $mail, $html);
		$html = str_replace("1email_id1", $nid, $html);
		$html = str_replace("1change_href1", "http://".$_SERVER['SERVER_NAME'].
			"/newsletter/email/cancel/".$nid."/".$mail, $html);

		$body[]  = $html;
	    	$params['subject'] = $subject;
	   	$params['body']    = $body;
		if($nid != 0) {
			db_insert('newsletter_send_control') 
				->fields(array(
				  'email_id' => $nid,
				  'subject' => $subject,
				  'body' => $html,
				  'sent' => 0,
				  'error' => 0,
				  'date' => time(),
				  'news_id' => $news_id,
				))->execute();
		} else {		
			db_insert('newsletter_send_control') 
                                ->fields(array(
                                  'email_id' => $nid,
                                  'subject' => $subject,
                                  'body' => $html,
                                  'sent' => 0,
                                  'error' => 0,
                                  'date' => time(),
                                  'email' => $mail,
                                  'news_id' => $news_id,
                                ))->execute();
		}
	        return true;	
    	}
    	return false;
    }
	

    public static function cronSendMail(){
		variable_set('newsletter_send_running', 1);	
		$query = db_select('newsletter_send_control', 'n');
		$query->fields('n', array('email_id','subject','body','news_id','email'));
		$query->condition('n.sent', 0);
		$query->condition('n.error', 0);
		$query->range(0,1000);	
		$result = $query->execute()->fetchAll();

		foreach($result as $r) {
			if ($r->email_id != 0) {
				(Workshop::sendMail($r->body, node_load($r->email_id), $r->subject)) ? Workshop::success($r->news_id, $r->email_id) : Workshop::error($r->news_id, $r->email_id);
			} else {
				$email = new stdClass();
				$email->mail = $r->email;
				$email->nid = 0;
				(Workshop::sendMail($r->body, $email, $r->subject)) ? Workshop::success($r->news_id, $r->email_id, $r->email) : Workshop::error($r->news_id, $r->email_id, $r->email);
			}
		}
		variable_set('newsletter_send_running', 0);	
    }
	
    public static function sendMail($html = "", $object, $subject = 'BOLETIM VIRTUAL'){
    	if(!empty($html) && $object instanceof stdClass){

    		$mail = (isset($object->title)) ? $object->title : $object->mail;
    		$nid  = (isset($object->nid)) ? $object->nid : $object->uid;

		$html = str_replace("1change_email1", $mail, $html);
		$html = str_replace("1email_id1", $nid, $html);
		$html = str_replace("1change_href1", $_SERVER['SERVER_NAME'].
			"/newsletter/email/cancel/".$nid."/".$mail, $html);

		$body[]  = $html;
	    	$params['subject'] = $subject;
	   	$params['body']    = $body;
		
		return drupal_mail('workshop', 'newsletter', $mail, 
		    	language_default(), $params, variable_get('workshop_mail', 'boletim-virtual@norepley.com'), TRUE);
    	}
    	return false;
    }
    public static function error($news_id, $email_id, $email = NULL){
		$update = db_update('newsletter_send_control') // Table name no longer needs {}
		  ->fields(array(
		    'error' => 1,
		    'date' => time(),
		  ))
		->condition('email', $email)
		->condition('news_id', $news_id)
		->condition('email_id', $email_id)
		->execute();
    }
    public static function success($news_id, $email_id, $email = NULL){
		$udpate = db_update('newsletter_send_control') // Table name no longer needs {}
		  ->fields(array(
		    'sent' => 1,
		    'date' => time(),
		  ))
		  ->condition('email', $email)
		  ->condition('news_id', $news_id)
		  ->condition('email_id', $email_id)
		  ->execute();

    }
	
	# Dump de contatos em formato CSV
	public static function dumpCsv(){
		drupal_add_http_header('Cache-Control', 'max-age=60, must-revalidate');
		drupal_add_http_header('Content-Disposition', 'attachment; filename="contatos_dia_'.date("d-m-Y", time()).'_.csv"');
		drupal_add_http_header('Content-type', 'text/csv; charset=utf-8');

		$i = 1;
		$delim = ',';
		$columns = $rows = array();
		$columns[] = "EMAIL";

		$result = db_query("SELECT title FROM node WHERE type = 'contatos' ORDER BY nid ASC");
		foreach ($result as $res)
			$rows[0][] = $res->title;

		$fields = field_info_instances("node", "contatos");
		foreach ($fields as $key => $value) {
			$key = strtoupper(str_replace('field_', '', $key));
			$columns[] = $key;
			$result = db_query("SELECT field_".$key."_value AS value
				FROM field_data_field_$key ORDER BY entity_id ASC");
			foreach ($result as $res)
				$rows[$i][] = $res->value;
			$i++;
		}
		print implode($delim, $columns)."\n";

		$max = count($rows[0]);
		$max2 = count($rows);

		for($j=0;$j<$max;$j++){
			for($k=0;$k<$max2;$k++){
				@print $rows[$k][$j] ? $rows[$k][$j] : 'vazio';
				@print $delim;
			}
			@print "\n";
		}
	}
	# Codifica uma string (p/ pesquisa em redes sociais)
	public static function encodeTerms($terms){
		return urlencode(str_replace(",", "\" OR \"", "\"".$terms."\""));
	}
}
