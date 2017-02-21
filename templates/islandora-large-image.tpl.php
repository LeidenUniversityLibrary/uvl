<?php

/**
 * @file
 * This is the template file for the object page for large image
 *
 * Available variables:
 * - $islandora_object: The Islandora object rendered in this template file
 * - $islandora_dublin_core: The DC datastream object
 * - $dc_array: The DC datastream object values as a sanitized array. This
 *   includes label, value and class name.
 * - $islandora_object_label: The sanitized object label.
 * - $parent_collections: An array containing parent collection(s) info.
 *   Includes collection object, label, url and rendered link.
 * - $islandora_thumbnail_img: A rendered thumbnail image.
 * - $islandora_content: A rendered image. By default this is the JPG datastream
 *   which is a medium sized image. Alternatively this could be a rendered
 *   viewer which displays the JP2 datastream image.
 *
 * @see template_preprocess_islandora_large_image()
 * @see theme_islandora_large_image()
 */
?>
<?php if ($dc_array['dc:subject']['value']): ?>
  <p class="dc-leader"><?php print $dc_array['dc:subject']['value']; ?></p>
<?php endif; ?>
<?php if ($dc_array['dc:description']['value']): ?>
  <?php print $dc_array['dc:description']['value']; ?>
<?php endif; ?>

<section class="dc-viewer">
  <div class="islandora-large-image-object islandora" vocab="http://schema.org/" prefix="dcterms: http://purl.org/dc/terms/" typeof="ImageObject">
    <div class="islandora-large-image-content-wrapper clearfix">
      <?php if ($islandora_content): ?>
        <?php if (isset($image_clip)): ?>
          <?php //print $image_clip; ?>
        <?php endif; ?>
        <div class="islandora-large-image-content">
          <?php print $islandora_content; ?>
        </div>
      <?php endif; ?>
  </div>
</section>
  <div class="islandora-large-image-metadata">
    <?php print $description; ?>
    <div class="dc-sidebox dc-sidebox-right">
      <ul class="dc-detail-tools">

        <li><a href="<?php print(variable_get(islandora_base_url)); ?>/objects/<?php print $islandora_dublin_core->dc['dc:identifier'][0]; ?>/datastreams/OBJ/content" title="download"><span>download</span><i class="fa fa-download" aria-hidden="true"></i></a></li>
        <li><a href="#?????????????????" title="print"><span>print</span><i class="fa fa-print" aria-hidden="true"></i></a></li>
        <li><a id="link-button" href="#" title="link"><span>link</span><i class="fa fa-link" aria-hidden="true"></i></a></li>
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
    <section class="dc-related-items">
      <h3 class="dc-section-header"><?php print t("Related items") ?></h3>
      <?php
        $block = module_invoke('views', 'block_view', 'c9aff44ea5cef77286d2b1558b49bfd0');
        print render($block);
      ?>
    </section>
  </div>

