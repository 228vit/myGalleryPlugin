<?php 
if ($form->getObject()->isNew())
{
  echo __('Gallery is not available until record is not created');
} else {
  include_component('myGalleryAdmin', 'edit', array('object' => $form->getObject())); 
}
?>
