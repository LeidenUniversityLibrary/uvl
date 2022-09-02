<?php

/**
 * @file
 * This is the template file for the object page for ead
 *
 * Available variables:
 * - $islandora_object: An Islandora object
 * - $parent_collections: An array containing parent collection(s) info.
 *   Includes collection object, label, url and rendered link.
 * - $islandora_thumbnail_img: A rendered thumbnail image.
 * - $islandora_content: A rendered image. By default this is the JPG datastream
 *   which is a medium sized image. Alternatively this could be a rendered
 *   viewer which displays the JP2 datastream image.
 *
 * @see template_preprocess_islandora_ead()
 * @see theme_islandora_ead()
 */
?>

  <div class="islandora-ead-metadata islandora-metadata">
    <?php print $description; ?>
  <div class="islandora-ead-object islandora" vocab="http://schema.org/" prefix="dcterms: http://purl.org/dc/terms/" typeof="EADObject">
    <div class="islandora-ead-content-wrapper islandora-viewer">
      <?php print $content['narrative']; ?>
    </div>
  </div>

    <?php include('sidebox_right.tpl.php'); ?>

    <div class="dc-box">
      <H3><?php print t('Description'); ?></H3>
      <?php print $metadata; ?>
    </div>
  </div>

