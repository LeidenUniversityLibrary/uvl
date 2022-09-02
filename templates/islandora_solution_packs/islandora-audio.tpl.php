<?php

/**
 * @file
 * This is the template file for the object page for audio file
 *
 * @TODO: add documentation about file and available variables
 */
?>

<div class="islandora-audio-object islandora" vocab="http://schema.org/" prefix="dcterms: http://purl.org/dc/terms/" typeof="AudioObject">
  <div class="islandora-audio-content-wrapper clearfix islandora-viewer">
    <?php if (isset($islandora_content)): ?>
      <div class="islandora-audio-content">
        <?php print $islandora_content; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php
  //Render the compound navigation block
  $block = module_invoke('islandora_compound_object', 'block_view', 'compound_navigation');
  print render($block['content']);
  ?>
  <div class="islandora-audio-metadata islandora-metadata">
    <?php print $description; ?>

    <?php include('sidebox_right.tpl.php'); ?>

    <div class="dc-box">
      <H3><?php print t('Description'); ?></H3>
      <?php print $metadata; ?>
    </div>
  </div>
</div>
