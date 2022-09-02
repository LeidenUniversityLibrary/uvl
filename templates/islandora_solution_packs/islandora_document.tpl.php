<?php

/**
 * @file
 * This is the template file for the pdf object
 *
 * @TODO: Add documentation about this file and the available variables
 */
?>

<div class="islandora-pdf-object islandora" vocab="http://schema.org/" prefix="dcterms: http://purl.org/dc/terms/" typeof="Article">
  <div class="islandora-pdf-content-wrapper clearfix islandora-viewer">
    <?php if (isset($islandora_content)): ?>
      <div class="islandora-pdf-content">
        <?php print $islandora_content; ?>
      </div>
      <?php if (isset($islandora_download_link)): ?>
        <?php print $islandora_download_link; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <?php
  //Render the compound navigation block
  $block = module_invoke('islandora_compound_object', 'block_view', 'compound_navigation');
  print render($block['content']);
  ?>
  <div class="islandora-pdf-metadata islandora-metadata">
    <?php print $description; ?>

    <?php include('sidebox_right.tpl.php'); ?>

    <div class="dc-box">
      <H3><?php print t('Description'); ?></H3>
      <?php print $metadata; ?>
    </div>
  </div>
</div>
