<a class="uploadPicsPopup fancybox.ajax" 
   href="<?php echo url_for('myGalleryAdmin/edit?model_name=Article&model_id='.$article->id) ?>">
  (<span id="nb_pics_<?php echo $article->id ?>"><?php echo $article->getNbPicsInGallery() ?></span>) pics</a>
