<?php

# Retorna um formulário de um tipo de conteúdo

function workshop_get_node_type_form($type)
{
  ctools_include('node.pages', 'node', '');
  global $user;
  $node = new stdClass;
  $node->type = !empty($type) ? $type : '';
  $node->uid = !empty($user->uid) ? $user->uid : '';
  $node->name = !empty($user->name) ? $user->name : '';
  $node->language = 'en';
  
  return drupal_get_form($type . '_node_form', $node);
}