<?php
/**
 * @file
 * Template file to style output.
 */

?>

<?php if(isset($viewer)): ?>
  <div id="book-viewer">
    <?php print $viewer; ?>
  </div>
<?php endif; ?>
<?php
//Render the compound navigation block
$block = module_invoke('islandora_compound_object', 'block_view', 'compound_navigation');
print render($block['content']);
?>

<?php include('sidebox_right.tpl.php'); ?>

<div class="dc-box">
<?php if($display_metadata === 1): ?>
  <H3><?php print t('Description'); ?></H3>
  <?php print $description; ?>
  <?php print $metadata; ?>
</div>
<?php endif; ?>
