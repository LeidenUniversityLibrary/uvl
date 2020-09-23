<?php

/**
 * @file
 * This is the template file for the object page for newspaper
 *
 * Available variables:
 * - $islandora_content: A rendered vertical tabbed newspapper issue browser.
 * - $parent_collections: An array containing parent collection IslandoraFedoraObject(s).
 * - $description: Rendered metadata descripton for the object.
 * - $metadata: Rendered metadata display for the binary object.
 *
 * @see template_preprocess_islandora_newspaper()
 */
?>
<div class="islandora-newspaper-object islandora">
  <div class="islandora-newspaper-content-wrapper clearfix">
    <?php if ($islandora_content): ?>
      <div class="islandora-newspaper-content">
        <?php print $islandora_content; ?>
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
  <div class="islandora-newspaper-metadata">
    <?php print $description; ?>
    <?php if ($parent_collections): ?>
      <div>
        <h2><?php print t('In collections'); ?></h2>
        <ul>
          <?php foreach ($parent_collections as $collection): ?>
        <li><?php print l($collection->label, "islandora/object/{$collection->id}"); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <?php print $metadata; ?>
  </div>
<?php endif; ?>
</div>
