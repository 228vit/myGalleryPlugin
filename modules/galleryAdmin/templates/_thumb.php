  <li id="pic_<?php echo $thumb->id ?>">
    <?php $path = 'uploads/'.myConfig::get('gallery_dir', 'gallery').'/'.$thumb->pic ?>
    <img src="<?php echo thumbnail_crop($path, 50, 50, 'fake-flat.jpg', 'watermark-25.png') ?>" width="50" height="50" />
    <br />
    <?php //echo link_to('удалить', 'gallery/deletePic?id='.$thumb->id, array('id' => 'pic_id_'.$thumb->id, 'confirm' => 'Вы уверены?')) ?>
    <?php echo link_to('удалить', 'gallery/deletePic?id='.$thumb->id, array('id' => 'pic_id_'.$thumb->id )) ?>
  </li>

