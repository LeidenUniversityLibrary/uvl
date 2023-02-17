<?php

/**
 * @file
 * This is the template file for the object page for a page of a book
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
 * @see template_preprocess_islandora_book_page()
 * @see theme_islandora_book_page()
 */
?>
<section class="dc-viewer">
  <div class="islandora-book-page-object islandora">
    <div class="islandora-book-page-content-wrapper clearfix islandora-viewer">
      <?php if ($islandora_content): ?>
        <?php if (isset($image_clip)): ?>
          <?php //print $image_clip; ?>
        <?php endif; ?>
        <div class="islandora-book-page-content">
          <?php print $islandora_content; ?>
        </div>
      <?php endif; ?>
  </div>
</section>
  <div class="islandora-book-page-metadata islandora-metadata">
    <?php print $description; ?>

    <?php include('sidebox_right.tpl.php'); ?>

    <div class="dc-box">
      <H3><?php print t('Description'); ?></H3>
      <?php print $metadata; ?>
    </div>
  </div>



<?php
 if (FALSE):
/**
 * @file
 * Template file to style output.
 */
?>
<?php
  print $book_object_id ? l(t('Return to Book View'), "islandora/object/{$book_object_id}") : t('Orphaned page (no associated book)');
?>
<?php if (isset($viewer)): ?>
  <div id="book-page-viewer">
    <?php print $viewer; ?>
  </div>
<?php elseif (isset($object['JPG']) && islandora_datastream_access(ISLANDORA_VIEW_OBJECTS, $object['JPG'])): ?>
  <div id="book-page-image">
    <?php
      $params = array(
        'path' => url("islandora/object/{$object->id}/datastream/JPG/view"),
        'attributes' => array(),
      );
      print theme('image', $params);
    ?>
  </div>
<?php endif; ?>
<?php endif; ?>
<!-- @todo Add table of metadata values -->
