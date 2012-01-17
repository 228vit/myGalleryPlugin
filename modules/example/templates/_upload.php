<?php $modelName = 'News'; $model_name = sfInflector::underscore($modelName); ?>
<a class="uploadPicsPopup fancybox.ajax" 
   href="<?php echo url_for('myGalleryAdmin/edit?model_name='.$modelName.'&model_id='.$$model_name->id) ?>">
  (<span id="nb_pics_<?php echo $$model_name->id ?>"><?php echo $$model_name->getNbPicsInGallery() ?></span>) pics</a>
