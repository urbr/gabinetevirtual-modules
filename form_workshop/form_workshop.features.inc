<?php
/**
 * @file
 * form_workshop.features.inc
 */

/**
 * Implements hook_node_info().
 */
function form_workshop_node_info() {
  $items = array(
    'workshop' => array(
      'name' => t('Oficina de Criação'),
      'base' => 'node_content',
      'description' => t('Oficina para publicações de matérias'),
      'has_title' => '1',
      'title_label' => t('Título'),
      'help' => '',
    ),
  );
  return $items;
}
