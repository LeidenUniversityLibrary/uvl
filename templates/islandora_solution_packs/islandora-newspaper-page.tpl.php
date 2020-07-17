<?php

/**
 * @file
 * islandora-newspaper-page.tpl.php
 * This is the template file for the object page for newspaper
 *
 * Available variables:
 * - $object: The Islandora object rendered in this template file
 * - $islandora_content: A rendered image. By default this is the JPG datastream
 *   which is a medium sized image. Alternatively this could be a rendered
 *   viewer which displays the JP2 datastream image.
 * - $description: Rendered metadata descripton for the object.
 * - $metadata: Rendered metadata display for the binary object.
 *
 * @see template_preprocess_islandora_newspaper_page()
 * @see theme_islandora_newspaper_page()
 *
 */
?>
<div class="islandora-newspaper-object">
<?php if (FALSE): ?>
  <div class="islandora-newspaper-controls">
    <?php print theme('islandora_newspaper_page_controls', array('object' => $object)); ?>
  </div>
<?php endif; ?>
  <div class="islandora-newspaper-content-wrapper clearfix">
    <?php if ($content): ?>
      <div class="islandora-newspaper-content">
        <?php print $content; ?>
      </div>
    <?php endif; ?>
  </div>
<div class="dc-sidebox dc-sidebox-right">
      <?php

      // Render the detail tools block
      $block = module_invoke_all('detail_tools_block_view');

      $block['list']['#type'] = 'ul';
      $block['list']['#theme'] = 'item_list';

      if (isset($block['list']['#attributes']['class'])) {
        $block['list']['#attributes']['class'] = array_unique($block['list']['#attributes']['class']);
      }

      print render($block);
      ?>
      <h3 class="dc-sidebox-header"><?php print t('In issue'); ?></h3>
      This page is part of:
      <ul class="dc-related-searches">
        <li>
        <?php
          print $issue_object_id ? l($issue_object_label, "islandora/object/{$issue_object_id}") : t('Orphaned page (no associated issue)');
        ?>
        </li>
      </ul>
  <?php if (isset($parent_collections)): ?>
    <div>
      <h3 class="dc-sidebox-header"><?php print t('In collections'); ?></h3>
      <?php print t('This item can be found in the following collections:'); ?>
      <ul class="dc-related-searches">
        <?php foreach ($parent_collections as $collection): ?>
          <li><?php print l($collection->label, "islandora/object/{$collection->id}"); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>
<div class="dc-box">
  <H3><?php print t('Description'); ?></H3>
  <?php print $metadata; ?>
</div>

<?php if (FALSE): ?>
  <div class="islandora-newspaper-page-metadata">
    <?php print $description; ?>
    <?php print $metadata; ?>
  </div>
<?php endif; ?>
</div>
