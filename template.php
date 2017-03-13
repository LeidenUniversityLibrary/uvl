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

/**
 * Override or insert variables into the node template.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 */
function uvl_preprocess_node(&$vars) {
  // Get the node.
  $node = $vars['node'];

  if ($vars['view_mode'] == 'teaser') {
    // Adding hook suggestions
    switch ($node->type) {
      case 'more_item':
        $vars['theme_hook_suggestions'][] = 'node__more__teaser';
      break;
      case 'home_page_item':
        $vars['theme_hook_suggestions'][] = 'node__home_page_item__teaser';
        break;
    }
  }
}

/**
 * Implements hook_preprocess_theme().
 */
function uvl_preprocess(&$variables, $hook) {

  // Adding persisten url to islandor objects
  if(isset($variables['object'])) {
    $object = $variables['object'];
  }
  elseif (isset($variables['islandora_object'])){
    $object = $variables['islandora_object'];
  }
  else{
    $object = FALSE;
  }
  if($object){
    $url = '';
    if (module_exists("islandora_handle")) {
      if (isset($object['MODS'])) {
        $xpath = "/mods:mods/mods:identifier[@type='hdl']";
        $content = $object['MODS']->content;
        $domdoc = new DOMDocument();
        if ($domdoc->loadXML($content)) {
          $domxpath = new DOMXPath($domdoc);
          $domxpath->registerNamespace('mods', 'http://www.loc.gov/mods/v3');
          $domnodelist = $domxpath->query($xpath);
          if ($domnodelist->length > 0) {
            foreach ($domnodelist as $domnode) {
              $text = $domnode->textContent;
              if (isset($text) && strlen($text) > 0) {
                $url = $text;
                break;
              }
            }
          }
        }
      }
    }
    if (strlen($url) == 0) {
      $url = url("islandora/object/" . $object->id, array('absolute' => TRUE));
    }
    $variables['persistent_url'] = $url;
  }
}


function uvl_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = 10;
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first', 'dc-pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous', 'dc-pager-prev'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current', 'dc-pager-active'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next', 'dc-pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last', 'dc-pager-last'),
        'data' => $li_last,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}
function uvl_preprocess_item_list(&$vars) {

  // make sure we're dealing with a pager item list
  if (isset($vars['attributes']['class']) && in_array('pager', $vars['attributes']['class'])) {
    // Add an extra class to item list
    $vars['attributes']['class'][] = 'dc-searchresults-pager';

    // loop the items and find the first .pager-item
    foreach ($vars['items'] as $index => $item) {

      // adding classes to first previous next last and active iten
      if(in_array('pager-first', $item['class'] )){
        $vars['items'][$index]['class'][] = 'dc-pager-first';
      }
      if(in_array('pager-previous', $item['class'] )){
        $vars['items'][$index]['class'][] = 'dc-pager-prev';
      }
      if(in_array('pager-current', $item['class'] )){
        $vars['items'][$index]['class'][] = 'dc-pager-active';
      }
      if(in_array('pager-next', $item['class'] )){
        $vars['items'][$index]['class'][] = 'dc-pager-next';
      }
      if(in_array('pager-last', $item['class'] )){
        $vars['items'][$index]['class'][] = 'dc-pager-last';
      }
    }
  }
}