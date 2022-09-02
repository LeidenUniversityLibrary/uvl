<?php

/**
 * @file
 */
?>
<div class="islandora-newspaper-issue clearfix">
  <span class="islandora-newspaper-issue-navigator">
    <?php print theme('islandora_newspaper_issue_navigator', array('object' => $object)); ?>
  </span>
  <?php if (isset($viewer_id) && $viewer_id != "none"): ?>
    <div id="book-viewer">
      <?php print $viewer; ?>
    </div>
  <?php endif; ?>

  <?php include('sidebox_right.tpl.php'); ?>

<div class="dc-box">
  <H3><?php print t('Description'); ?></H3>
  <?php print $metadata; ?>
</div>

<?php if (FALSE): ?>
  <div class="islandora-newspaper-issue-metadata">
    <?php print $description; ?>
    <?php print $metadata; ?>
  </div>
<?php endif; ?>
</div>
