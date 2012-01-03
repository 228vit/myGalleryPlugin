<?php use_helper('I18N', 'JavascriptBase') ?>
<?php use_stylesheet("/myGalleryPlugin/css/form.min.css") ?>
<?php use_stylesheet("/myGalleryPlugin/css/formComment.min.css") ?>
<a name="comments-<?php echo $crypt ?>"></a>
<div class="form-comment">
<?php if( myGallery::checkAccessToForm($sf_user) ): ?>
  <form action="<?php echo url_for($sf_request->getUri()) ?>" method="post" name="<?php echo $form->getName() ?>">
  <fieldset>
    <legend><?php echo __('Add new comment', array(), 'myGallery') ?></legend>
    <?php include_partial("comment/form", array('form' => $form)) ?>
  </fieldset>
  </form>
<?php else: ?>
  <div id="notlogged"><?php echo __('Please log in to comment', array(), 'myGallery') ?></div>
<?php endif ?>
</div>