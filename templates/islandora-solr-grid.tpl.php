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
          <?php
            $image = '<img src="/sites/all/themes/uvl/img/loading.gif" data-src="' . url($result['thumbnail_url'], array('query' => $result['thumbnail_url_params'])) . '" />';
            print l($image, $result['object_url'], array(
              'html' => TRUE,
              'query' => $result['object_url_params'],
              'fragment' => isset($result['object_url_fragment']) ? $result['object_url_fragment'] : '',
            ));
          ?>
        </dt>
        <dd class="solr-grid-caption">
          <?php
            $object_label = isset($result['object_label']) ? $result['object_label'] : '';
            print l($object_label, $result['object_url'], array(
              'query' => $result['object_url_params'],
              'fragment' => isset($result['object_url_fragment']) ? $result['object_url_fragment'] : '',
            ));
          ?>
        </dd>
      </dl>
    <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
