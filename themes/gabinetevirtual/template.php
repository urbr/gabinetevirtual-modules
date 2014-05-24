<?php

/**
 * Add body classes if certain regions have content.
 */
function workshop_preprocess_html(&$variables) {

  # Evitar conflitos jQuery nas páginas informadas
  if(arg(0) != 'workshop' && arg(0) != 'newsletter' && arg(0) != 'batch')
  	drupal_add_js(drupal_get_path('module', 'workshop').'/theme/agenda/js/jquery-core/jquery-1.4.2-ie-fix.min.js', array('weight' => -20));  
  if(arg(0) == 'newsletter'){
	drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/jquery.min.js', array('weight' => -20));
	drupal_add_js(drupal_get_path('module', 'workshop').'/theme/js/jquery-ui-min.js', array('weight' => -19));
  }

  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }

  if (!empty($variables['page']['triptych_first'])
    || !empty($variables['page']['triptych_middle'])
    || !empty($variables['page']['triptych_last'])) {
    $variables['classes_array'][] = 'triptych';
  }

  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }

  // Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 6', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function workshop_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Override or insert variables into the page template.
 */
function workshop_process_page(&$variables) {

  global $user;

  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }

  if(arg(0) == 'agenda'){
    $variables['img_header'] = '/themes/bolsa/img/bolsafamilia.png';
  }

  if($user->uid != 0){
    $variables['name'] = $user->name;
    $variables['mail'] = $user->mail;
    $variables['config'] = t('<a href="@url">Editar Configurações</a>', array('@url' => url('user/'.$user->uid.'/edit')));
    $variables['pass'] = t('<a href="@url">Mudar minha Senha</a>', array('@url' => url('user/'.$user->uid.'/edit')));
    $variables['online'] = workshop_get_users_online();
  }

  if($user->uid){
	$variables['avatar'] = theme('user_picture', array('account' => user_load($user->uid), 'getsize' => TRUE, 'attributes' => array('class' => 'thumb', 'width' => '175', 'height' => '175')));
	if(empty($variables['avatar']))
		$variables['avatar'] = '<img class="portrait" src="/themes/workshop/images/no_picture.jpg">';
	$variables['uid'] = $user->uid;
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function workshop_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'workshop') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function workshop_process_maintenance_page(&$variables) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Override or insert variables into the node template.
 */
function workshop_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the block template.
 */
function workshop_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Implements theme_menu_tree().
 */
function workshop_menu_tree($variables) {
  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function workshop_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '"' . $variables['attributes'] .'>' . $output . '</div>';

  return $output;
}

function workshop_links__system_main_menu($variables) {
  $html = "<ul>";
  $html .= "<li>".l('Gabinete', 'agenda')."</li>";
  $html .= "<li>".l('Site', '')."</li>";
  //$html .= "<li>".l('Agenda', 'agenda')."</li>";
  $html .= "<li>".l('Oficina de criação', 'workshop')."</li>";
  $html .= "<li>".l('Boletins', 'newsletter')."</li>";
  $html .= "<li>".l('Conteúdos', 'admin/content')."</li>";
  $html .= "<li>".l('Galeria', 'workshop/gallery')."</li>";
  $html .= "<li>".l('Monitor', 'monitor')."</li>";
  $html .= "<li>".l('Perguntas', 'workshop/listaperguntas')."</li>";
  //$html .= "<li>".l('Redes Sociais', 'socialnetworks')."</li>";
  $html .= "<li>".l('Sair', 'user/logout')."</li>";
  $html .= "</ul>";
  return $html;
}

function workshop_get_users_online(){
  global $user;
  $number = db_query('SELECT COUNT(uid) AS number FROM {users} WHERE status = 1')->fetchField();
  if (user_access('access content')) {
  // Count users with activity in the past defined period.
    $time_period = variable_get('user_block_seconds_online', 60);

  // Perform database queries to gather online user lists.
  //$guests = db_query('SELECT COUNT(sid) AS count FROM {sessions} WHERE timestamp >= :time AND uid = 0', array(':time'=> time() - $time_period))->fetchObject();//removed db_fetch_object
  //$guests_hostname = db_query('SELECT hostname FROM {sessions} WHERE timestamp >= :time AND uid = 0', array(':time'=> time() - $time_period));
  //$total_guests = db_query('SELECT COUNT(hostname) FROM {sessions} WHERE timestamp >= :time AND uid = 0', array(':time'=> time() - $time_period))->fetchField();
    
    //$whos_online = db_query('SELECT uid, name, access FROM {users} WHERE access >= :time AND uid != 0 AND uid != :uid ORDER BY access DESC', array(':time' => time() - $time_period, ':uid' => $user->uid));
    $allusers    = db_query('SELECT uid, name, access FROM {users} WHERE uid != 0 AND status = 1 AND uid != :uid ORDER BY name', array(':uid' => $user->uid));
    $total_users = db_query('SELECT COUNT(uid), name, access FROM {users} WHERE access >= :time AND uid != 0 GROUP BY name, access ORDER BY access DESC', array(':time'=> time() - $time_period))->fetchField();
    $output = "";
    foreach ($allusers as $u) {
      @$output .= '<div class="user_chat">
                    <a href="/user/'.$u->uid.'"><img width="37" class="portrait" src="/themes/workshop/images/no_picture.jpg"></a>
                    <span>';
      @$output .= '<a href="javascript:void(0)" onclick="javascript:chatWith('.$u->uid.',\''.$u->name.'\')">'.substr($u->name, 0, 15).'</a></span>';
      $img = ($u->access >= time() - $time_period) ? 'online' : 'offline';
      @$output .= '<img class="online" src="/themes/workshop/images/'.$img.'.png">';
      @$output .= '</div>';
   }

   return $output;
 }
}
