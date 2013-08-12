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