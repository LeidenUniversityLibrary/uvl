<?php
/**
 * @file
 * Template file to style output.
 */

?>

<div class="dc-sidebox dc-sidebox-right">
      <?php

      // Render the detail tools block
      $block = module_invoke_all('detail_tools_block_view');

      $block['list']['#type'] = 'ul';
      $block['list']['#theme'] = 'item_list';

      if (isset($block['list']['#attributes']['class'])) {
        $block['list']['#attributes']['class'] = array_unique($block['list']['#attributes']['class']);
      }

      // Add the print button for EAD
      if (isset($object) && in_array('islandora:eadCModel', $object->models)) {
        $block['list']['#items'][] = l(
          '<span>print</span><i class="fa fa-print" aria-hidden="true"></i>',
          'javascript:window.print();',
          array('attributes' => array('title' => 'print'),'html' => TRUE, 'external' => TRUE)
        );
      }

      print render($block);
      ?>

      <?php if (isset($issue_object_label)): ?>
      <h3 class="dc-sidebox-header"><?php print t('In issue'); ?></h3>
      This page is part of:
      <ul class="dc-related-searches">
        <li>
        <?php
          print $issue_object_id ? l($issue_object_label, "islandora/object/{$issue_object_id}") : t('Orphaned page (no associated issue)');
        ?>
        </li>
      </ul>
      <?php endif; ?>

      <?php if (isset($book_object_label)): ?>
      <h3 class="dc-sidebox-header"><?php print t('In book'); ?></h3>
      This page is part of:
      <ul class="dc-related-searches">
        <li>
        <?php
          print $book_object_id ? l($book_object_label, "islandora/object/{$book_object_id}") : t('Orphaned page (no associated book)');
        ?>
        </li>
      </ul>
      <?php endif; ?>

      <?php if (isset($parent_collections)): ?>
        <div>
          <h3 class="dc-sidebox-header"><?php print t('Digital Collections'); ?></h3>
          <?php print t('This item can be found in the following digital collections:'); ?>
          <ul class="dc-related-searches">
            <?php foreach ($parent_collections as $collection): ?>
              <li><?php print l($collection->label, "islandora/object/{$collection->id}"); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php $ead_block = views_embed_view('part_of_ead_view', 'default'); ?>
      <?php if ($ead_block && strpos($ead_block, '<span class="field-content ead-button"></span>') === FALSE && strpos($ead_block, '<span class="field-content ead-button">') !== FALSE): ?>
         <h3 class="dc-sidebox-header"><?php print t('Collection Guide'); ?></h3>
         This item can be found in the following collection guide:
         <ul class="dc-related-searches">
           <li>
             <?php print $ead_block; ?>
           </li>
         </ul>
      <?php endif; ?>

      <?php $part_of_album_block = views_embed_view('part_of_photo_album_view', 'default'); ?>
      <?php $album_parts_block = views_embed_view('photo_album_parts_view', 'default'); ?>
      <?php if ($part_of_album_block && strpos($part_of_album_block, '<span class="field-content album-button"></span>') === FALSE && strpos($part_of_album_block, '<span class="field-content album-button">') !== FALSE): ?>
         <h3 class="dc-sidebox-header"><?php print t('Photo album'); ?></h3>
         Show more items in this photo album:
         <ul class="dc-related-searches">
           <li>
             <?php print $part_of_album_block; ?>
           </li>
         </ul>
      <?php elseif ($album_parts_block && strpos($album_parts_block, '<span class="field-content album-button"></span>') === FALSE && strpos($album_parts_block, '<span class="field-content album-button">') !== FALSE): ?>
         <h3 class="dc-sidebox-header"><?php print t('Photo album'); ?></h3>
         Show more items in this photo album:
         <ul class="dc-related-searches">
           <li>
             <?php print $album_parts_block; ?>
           </li>
         </ul>
      <?php endif; ?>

</div>
