  <div class="backtop<?php if($text) echo " backtopmax"; ?>">
    <a href="<?php echo url_for($route."#comments-".$crypt) ?>">
<?php if($text): ?>
<?php echo __('Back to top', array(), 'myGallery') ?>
<?php endif; ?>
<?php echo image_tag('/myGalleryPlugin/images/arrow-up.png', array('alt' => __('Back to top', array(), 'myGallery'), 'title' => __('Back to top', array(), 'myGallery'))) ?>
    </a>
  </div>