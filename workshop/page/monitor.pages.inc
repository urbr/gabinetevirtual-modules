<?php

function monitor_view(){
	$term = variable_get('workshop_monitor_search', '');
	return theme('monitor_page', array(
		'terms' => Workshop::encodeTerms($term), 
		'orig' => $term)
	);
}

function monitor_search(){
	return theme('monitor_page', array(
		'terms' => Workshop::encodeTerms($_POST['terms']),
		'orig' => $_POST['terms'])
	);
}

function monitor_search_twitter($term){
	echo file_get_contents("http://apps.urucumbrasil.com.br/gabinetevirtual/twitter_api_11/web/search_tweets.php?q=".rawurlencode($term));
}
