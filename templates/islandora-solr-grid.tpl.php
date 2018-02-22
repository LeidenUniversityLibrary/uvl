<?php
/**
 * @file
 * Islandora Solr grid template
 *
 * Variables available:
 * - $results: Primary profile results array
 *
 * @see template_preprocess_islandora_solr_grid()
 */
?>

<?php if (empty($results)): ?>
  <p class="no-results"><?php print t('Sorry, but your search returned no results.'); ?></p>
<?php else: ?>
  <div class="islandora-solr-search-results">
    <div class="islandora-solr-grid clearfix">
    <?php foreach($results as $result): ?>
      <?php $contentmodelclass = strtolower(implode(' ', preg_replace(array('/info:fedora/','#/islandora:#','#[/:]#'), '', $result['content_models']))); ?>
      <dl class="solr-grid-field <?php print ' ' . $contentmodelclass ?>">
        <dt class="solr-grid-thumb">
          <?php print $result['thumbnail']; ?>
        </dt>
        <dd class="solr-grid-caption">
          <?php print $result['objectLabel']; ?>
        </dd>
      </dl>
    <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
