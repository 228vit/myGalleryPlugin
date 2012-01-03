<?php use_helper('I18N') ?>
<?php use_stylesheet("/myGalleryPlugin/css/form.min.css") ?>
<?php use_stylesheet("/myGalleryPlugin/css/reportComment.min.css") ?>
<div class="form-comment">
  <form action="" method="post" id="reportComment">
  <fieldset>
    <legend><?php echo __('Report a comment', array(), 'myGallery') ?></legend>
    <?php include_partial("comment/form", array('form' => $form)) ?>
  </fieldset>
  </form>
</div>