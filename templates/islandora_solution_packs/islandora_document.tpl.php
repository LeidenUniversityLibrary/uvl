<?php

/**
 * @file
 * This is the template file for the pdf object
 *
 * @TODO: Add documentation about this file and the available variables
 */
?>

<div class="islandora-pdf-object islandora" vocab="http://schema.org/" prefix="dcterms: http://purl.org/dc/terms/" typeof="Article">
  <div class="islandora-pdf-content-wrapper clearfix">
    <?php if (isset($islandora_content)): ?>
      <div class="islandora-pdf-content">
        <?php print $islandora_content; ?>
      </div>
      <?php if (isset($islandora_download_link)): ?>
        <?php print $islandora_download_link; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <div class="islandora-pdf-metadata">
    <?php
    //Render the compound navigation block
    $block = module_invoke('islandora_compound_object', 'block_view', 'compound_navigation');
    print render($block['content']);
    ?>
    <?php print $description; ?>
    <div class="dc-sidebox dc-sidebox-right">
      <ul class="dc-detail-tools">
        <!--
        <li><a href="<?php print(variable_get(islandora_base_url)); ?>/objects/<?php print $islandora_dublin_core->dc['dc:identifier'][0]; ?>/datastreams/OBJ/content" title="download"><span>download</span><i class="fa fa-download" aria-hidden="true"></i></a></li>
        <li><a href="#?????????????????" title="print"><span>print</span><i class="fa fa-print" aria-hidden="true"></i></a></li>-->

        <li><a id="link-button" href="<?php print $persistent_url; ?>" title="link"><span>link</span><i class="fa fa-link" aria-hidden="true"></i></a></li>
      </ul>
      <?php if ($parent_collections): ?>
        <div>
          <h3 class="dc-sidebox-header"><?php print t('In collections'); ?></h3>
          This item can be found in the following collections:
          <ul class="dc-related-searches">
            <?php foreach ($parent_collections as $collection): ?>
              <li><?php print l($collection->label, "islandora/object/{$collection->id}"); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
    <div class="dc-box">
      <?php print $metadata; ?>
    </div>
  </div>
</div>
