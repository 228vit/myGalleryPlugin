  <li id="pic_<?php echo $pic->id ?>">
    <a href="<?php echo '/'.$path ?>" target="_blank" class="extraPics"><img 
        src="<?php  $helper = sfConfig::get('app_my_gallery_thumb_helper', 'thumbnail_crop_watermarked'); 
                    echo $helper($path, 50, 50, 'fake-pic.jpg', 'watermark-25.png') ?>" width="50" height="50" /></a>
    <br />
    <?php echo link_to('удалить', 'myGalleryAdmin/deletePic?id='.$pic->id, array('class' => 'delLink', 'id' => 'pic_id_'.$pic->id )) ?>
  </li>

