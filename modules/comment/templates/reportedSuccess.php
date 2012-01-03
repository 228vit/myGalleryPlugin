<?php use_helper('JavascriptBase', 'I18N') ?>
<?php use_stylesheet('/myGalleryPlugin/css/reported.min.css') ?>
<div id="report-sent">
  <span><?php echo __('Report sent.', array(), 'myGallery') ?><br/><?php echo __('The moderation team has been notified.', array(), 'myGallery') ?></span><br /><br />
  <?php echo link_to_function(__('Close the popup', array(), 'myGallery'), 'window.close()') ?>
</div>