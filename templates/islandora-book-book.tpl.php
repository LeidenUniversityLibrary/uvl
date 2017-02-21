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

<div class="dc-sidebox dc-sidebox-right">
  <ul class="dc-detail-tools">

    <li>
      <a href="#" title="download">
        <span>download</span>
        <i class="fa fa-download" aria-hidden="true"></i>
      </a>
    </li>
    <li><a href="#" title="print"><span>print</span><i class="fa fa-print" aria-hidden="true"></i></a></li>
    <li><a id="link-button" href="#" title="link"><span>link</span><i class="fa fa-link" aria-hidden="true"></i></a></li>
  </ul>
  <?php if ($parent_collections): ?>
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
<?php if($display_metadata === 1): ?>
  <section class="dc-metadata islandora-book-metadata">
    <?php print $description; ?>
    <?php print $metadata; ?>
  </section>
</div>
<?php endif; ?>
