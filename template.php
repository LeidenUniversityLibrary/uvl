<?php
/**
 * Theme override for theme_menu_link()
 */
function uvl_menu_link($variables) {
    $element = $variables['element'];
    $menu_name = $variables['element']['#original_link']['menu_name'];
    $sub_menu = '';
    $classes = implode (' ',$element['#attributes']['class']);

    //add class to menu item if it has children
    if ($element['#below']) {
      $sub_menu = drupal_render($element['#below']);
      $element['#attributes']['class'][] = 'cbp-placeholder';
      $sub_menu = '<div class="cbp-hrsub">
        <div class="cbp-hrsub-inner dc-centercontent"><div>'
        . $sub_menu . '</div></div>';
    }

    $output = l($element['#title'], $element['#href'],
      array('attributes' => $element['#attributes']));

    //create fragment link for <nolink> menu items in first level
    if ($element['#href'] == '<nolink>' && $element['#below']) {
      $output = l($element['#title'], NULL, array(
        'attributes' => $element['#attributes'],
        'fragment' => FALSE
      ));
    }
    //H4 element for <nolink> items in submenu
    if ($element['#href'] == '<nolink>' && !$element['#below']) {
      $output = '<h4>' . $element['#title'] . '</h4>';
    }
    //Change separator menu item to div element
    if ($element['#href'] == '<separator>') {
      $output = '</ul></div><div><ul>';
      return $output;
    }
    //Inject block content when url query contains block
    if (isset($element['#original_link']['options']['query'])) {
      $output = render_block_content(
        $element['#original_link']['options']['query']['block'],
        $element['#original_link']['options']['query']['delta']);
    }

    //Inject search block after last menu item
    if ($element['#original_link']['depth'] === '1' &&
      in_array('last', $element['#attributes']['class']) &&
      $menu_name == 'main-menu') {
      return '<li>' . $output . $sub_menu . '</li>' .
      '<li class="dc-menu-search">' .
      render_block_content('islandora_solr', 'simple') . '</li>';
    }

    return '<li classes="'.$classes.'">' . $output . $sub_menu . '</li>';
}

/**
 * Implements hook_form_alter().
 */
function uvl_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'islandora_solr_simple_search_form') {
    //change submit button markup
    $form['simple']['submit'] = array(
      '#type' => 'submit',
      '#suffix' => '<button type="submit" class="form-submit"><i class="fa fa-search"></i></button>',
      '#weight' => 1000,
    );
    //add placeholder to search textbox
    $form['simple']['islandora_simple_search_query']['#attributes']['placeholder'] = t('Search');
  }
}

/**
 * Helper function to find and render a block.
 */
function render_block_content($module, $delta) {
  $output = '';
  if ($block = block_load($module, $delta)) {
    if ($build = module_invoke($module, 'block_view', $delta)) {
      $delta = str_replace('-', '_', $delta);
      drupal_alter(array(
        'block_view',
        "block_view_{$module}_{$delta}"
      ), $build, $block);

      if (!empty($build['content'])) {
        return is_array($build['content']) ? render($build['content']) : $build['content'];
      }
    }
  }
  return $output;
}
