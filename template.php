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

    $options = array('attributes' => $element['#attributes']);
    if (url_is_external($element['#href'])) {
      $options['attributes']['target'] = '_blank';
    }
    if ($element['#href'] === 'user/login') {
      $options['query'] = drupal_get_destination();
    }
    $output = l($element['#title'], $element['#href'], $options);

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
    if (isset($element['#original_link']['options']['query']['block'])) {
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
      render_block_content('islandora_collection_search', 'islandora_collection_search') . '</li>';
    }
    return '<li classes="'.$classes.'">' . $output . $sub_menu . '</li>';
}

/**
 * Implements hook_form_alter().
 */
function uvl_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'islandora_collection_search_form') {
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
 * Implements hook_preprocess_page().
 */
function uvl_preprocess_page(&$vars, $hook) {
  // Unsets the ugly drupal message.
  unset($vars['page']['content']['system_main']['default_message']);
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
  if (isset($vars['attributes']['class'])) {
    if (!is_array($vars['attributes']['class'])) {
      $vars['attributes']['class'] = array($vars['attributes']['class']);
    }
  }
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

function uvl_preprocess_islandora_objects_subset(&$variables){

  // Only act on a collection page
  if (strpos(arg(2), 'collection:') !== false) {
    $pid = arg(2);
    // Check query if a content type is filled for this pid
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'collection')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->fieldCondition('field_pid', 'value', $pid, '=')
      ->range(0, 1);
    $result = $query->execute();

    // Check if there is a node
    if (isset($result['node'])) {
      // If we have a node disable this display.
      unset($variables['content']);
      unset($variables['pager']);
      unset($variables['display_links']);
    }
  }

}

function uvl_preprocess_islandora_book_page(&$variables) {
  // yuk, largely copied from large image
  $islandora_object = $variables['object'];
  $variables['islandora_object'] = $islandora_object;

  $repository = $islandora_object->repository;
  module_load_include('inc', 'islandora', 'includes/datastream');
  module_load_include('inc', 'islandora', 'includes/utilities');
  module_load_include('inc', 'islandora', 'includes/metadata');

  $variables['parent_collections'] = islandora_get_parents_from_rels_ext($islandora_object);
  $variables['metadata'] = islandora_retrieve_metadata_markup($islandora_object);
  $variables['description'] = islandora_retrieve_description_markup($islandora_object);

  $params = array();

  if (isset($islandora_object['JP2']) && islandora_datastream_access(ISLANDORA_VIEW_OBJECTS, $islandora_object['JP2'])) {
    // Get token to allow access to XACML protected datastreams.
    // Always use token authentication in case there is a global policy.
    module_load_include('inc', 'islandora', 'includes/authtokens');
    $token = islandora_get_object_token($islandora_object->id, 'JP2', 2);
    $jp2_url = url("islandora/object/{$islandora_object->id}/datastream/JP2/view",
      array(
        'absolute' => TRUE,
        'query' => array('token' => $token),
      ));
    // Display large image.
    $params['jp2_url'] = $jp2_url;
  }

  $viewer = islandora_get_viewer($params, 'islandora_large_image_viewers', $islandora_object);
  $variables['islandora_content'] = '';
  if ($viewer) {
    if (strpos($viewer, 'islandora-openseadragon') !== FALSE) {
      if (isset($islandora_object['JP2']) && islandora_datastream_access(ISLANDORA_VIEW_OBJECTS, $islandora_object['JP2'])) {
        $variables['image_clip'] = theme(
          'islandora_openseadragon_clipper',
          array('pid' => $islandora_object->id)
        );
      }
    }
    $variables['islandora_content'] = $viewer;
  }
  // If no viewer is configured just show the jpeg.
  elseif (isset($islandora_object['JPG']) && islandora_datastream_access(ISLANDORA_VIEW_OBJECTS, $islandora_object['JPG'])) {
    $params = array(
      'title' => $islandora_object->label,
      'path' => url("islandora/object/{$islandora_object->id}/datastream/JPG/view"),
    );
    $variables['islandora_content'] = theme('image', $params);
  }
  else {
    $variables['islandora_content'] = NULL;
  }

  // set the label of the book and also (re)set the parent_collections to the collections where the book is part of.
  if ($variables['book_object_id']) {
    $bookobj = islandora_object_load($variables['book_object_id']);
    if ($bookobj) {
      $variables['book_object_label'] = $bookobj->label;

      $variables['parent_collections'] = islandora_get_parents_from_rels_ext($bookobj);
    }
  }
}

function uvl_preprocess_islandora_ead(&$variables) {
  drupal_add_js(drupal_get_path('theme', 'uvl') . '/js/ead.js', 'file');
  $islandora_object = $variables['object'];
  $variables['islandora_object'] = $islandora_object;

  $repository = $islandora_object->repository;
  module_load_include('inc', 'islandora', 'includes/datastream');
  module_load_include('inc', 'islandora', 'includes/utilities');
  module_load_include('inc', 'islandora', 'includes/metadata');

  $variables['parent_collections'] = islandora_get_parents_from_rels_ext($islandora_object);
  $variables['metadata'] = islandora_retrieve_metadata_markup($islandora_object);
  $variables['description'] = islandora_retrieve_description_markup($islandora_object);
}

function uvl_preprocess_islandora_newspaper(array &$variables) {
  if (isset($variables['islandora_content_render_array']['tabs'])) {
    drupal_add_js(drupal_get_path('theme', 'uvl') . '/js/newspaper.js', 'file');
    drupal_add_js(drupal_get_path('theme', 'uvl') . '/js/lazyimages.js', 'file');
    drupal_add_css(drupal_get_path('theme', 'uvl') . '/css/newspaper.css', 'file');
    $tabs = $variables['islandora_content_render_array']['tabs']; 
    $years = element_children($tabs);
    $yearselect = array(
      '#id' => 'islandora_newspaper_select_year',
      '#type' => 'select',
      '#title' => t('Year'),
      '#options' => array(),
      '#prefix' => "<DIV>",
      '#suffix' => "</DIV>",
    );
    $issues = array(
    );
    $useimagecache = module_exists('islandora_imagecache');
    if ($useimagecache) {
      module_load_include('inc', 'islandora_imagecache', 'includes/utilities');
    }
    foreach ($years as $year) {
      $yearselect['#options'][$year] = $tabs[$year]['#title'];
      $issueselect = array(
        '#type' => 'markup',
        '#markup' => '',
      );
      $months = element_children($tabs[$year]);
      foreach ($months as $month) {
        $days = element_children($tabs[$year][$month]);
        foreach ($days as $day) {
          foreach ($tabs[$year][$month][$day] as $dayarray) {
            if ($useimagecache) {
              $object = menu_get_object('islandora_object', 2, $dayarray['#path']);
              if ($object) {
                $tnpath = islandora_imagecache_retrieve_image_cache_image($object); 
              }
            }
            if (!isset($tnpath)) {
              $tnpath = $dayarray['#path'] . '/datastream/TN/view';
            }
            $link = url($dayarray['#path']);
            $text = $dayarray['#text'];
            $issueselect['#markup'] .= "<dl><dt><a href=\"$link\"><img src=\"/sites/all/themes/uvl/img/loading.gif\" data-src=\"$tnpath\"/></a></dt><dd><a href=\"$link\">$text</a></dd></dl>";
          }
        }
      }
      $issueselect['#markup'] = "<div class=\"newspaperissues year$year\">" . $issueselect['#markup'] . "</div>";
      $issues[$year] = $issueselect;
    }
    $variables['islandora_content_render_array'] = array(
      'yearselect' => $yearselect,
      'issues' => array(
        '#type' =>  'fieldset',
        'issues' =>  $issues,
      ),
    );
  }
}

function uvl_preprocess_islandora_newspaper_issue(array &$variables) {
  module_load_include('inc', 'islandora', 'includes/utilities');
  module_load_include('inc', 'islandora_newspaper', 'includes/utilities');
  $object = $variables['object'];
  $parentid = islandora_newspaper_get_newspaper($object);
  if ($parentid) {
    $parent = islandora_object_load($parentid);
    if ($parent) {
      $variables['parent_collections'] = islandora_get_parents_from_rels_ext($parent);
    }
  }
}

function uvl_preprocess_islandora_newspaper_page(array &$variables) {
  module_load_include('inc', 'islandora_paged_content', 'includes/utilities');
  $object = $variables['object'];
  $results = $object->relationships->get(ISLANDORA_RELS_EXT_URI, 'isPageOf');
  $result = reset($results);
  $parentid = isset($result['object']['value']) ? $result['object']['value'] : FALSE;
  if ($parentid) {
    $parent = islandora_object_load($parentid);
    if ($parent) {
      $variables['issue_object_id'] = $parent->id;
      $variables['issue_object_label'] = $parent->label;
    }
  }
}

function uvl_preprocess_islandora_compound_prev_next(array &$variables) {
  if (module_exists('islandora_imagecache')) {
    module_load_include('inc', 'islandora_imagecache', 'includes/utilities');

    foreach ($variables['themed_siblings'] as &$sibling) {
      $sib_obj = islandora_object_load($sibling['pid']);
      $sibling['TN'] = islandora_imagecache_retrieve_image_cache_image($sib_obj, 'TN', 'compound_object_nav');
    }
  }
}
