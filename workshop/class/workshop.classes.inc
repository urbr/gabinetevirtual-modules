<?php

class WorkShop {
	
    function initialize() { }

    public static function sendMail($html = "", $object, $subject = 'BOLETIM VIRTUAL'){
    	if(!empty($html) && $object instanceof stdClass){

    		$mail = (isset($object->title)) ? $object->title : $object->mail;
    		$nid  = (isset($object->nid)) ? $object->nid : $object->uid;

		$html = str_replace("1change_email1", $mail, $html);
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
	    for($j=0;$j<=$i;$j++){
	    	for($k=0;$k<=$i;$k++)
	    		@print $rows[$k][$j].$delim;
	    	print "\n";
		}
	}

	# Codifica uma string (p/ pesquisa em redes sociais)
	public static function encodeTerms($terms){
		return urlencode(str_replace(",", "\" OR \"", "\"".$terms."\""));
	}
}
