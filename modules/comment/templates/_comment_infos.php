      <td rowspan="2" class="infos">
        <a name="<?php echo $i ?>" class="ancre">#<?php echo $i ?></a>
        <?php if(!$obj->is_delete): ?>
          <?php echo link_to_function(
                  image_tag('/myGalleryPlugin/images/comments.png', array( 'alt' => 'reply' )) ,
                  "reply('".$obj->getId()."','".$obj->getAuthor()."', '".$form_name."')",
                  array('title' => __('Reply to this comment', array(), 'myGallery'))) ?>
          <?php echo link_to_function(
                image_tag('/myGalleryPlugin/images/error.png', array( 'alt' => 'report' )) ,
                'window.open(
                  \''.url_for('@comment_reporting?id='.$obj->getId().'&num='.$i).'\',
                  \''.__('Add new comment', array(), 'myGallery').'\',
                    "menubar=no, status=no, scrollbars=no, menubar=no, width=565, height=300")',
                array('target' => '_blank', 'title' => __('Report this comment - New window', array(), 'myGallery') )) ?><br />
        <?php endif; ?>
        <?php if(commentTools::isGravatarAvailable() && !$obj->is_delete): ?>
          <?php echo gravatar_image_tag($obj->getEmail()) ?>
        <?php endif ?>
      </td>