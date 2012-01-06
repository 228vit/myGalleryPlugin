<h1 style="margin: 0;">Галерея фото <?php // echo count($gallery); echo get_class($object->getRawValue())  ?></h1>
<div>
<ul class='gallery'>
<?php if (isSet($gallery)): ?>
  <?php foreach ($gallery as $pic): ?>
    <?php $path = implode('/', array_filter(array('uploads', 'galleries', $model_name, $pic->pic))); ?>
    <?php include_partial('galleryAdmin/thumb', array('pic' => $pic, 'path' => $path)) ?>
  <?php endforeach; // ($gallery as $g): ?>
<?php endif; // ($gallery): ?>
</ul>
</div>
<br clear="all" />
  
<div id="status-message">Select some files to upload:</div>
<div id="custom-queue"></div>
<div id="gallery"></div>

<form>
  <input id="file_upload" type="file" name="upload[pic]" />
  <br />
  <a href="javascript:$('#file_upload').uploadifyUpload();">Upload Files</a>
</form>
<script type="text/javascript">
  function bindDeleteAction() {
		$( "ul.gallery" ).sortable({
//      stop: function(event, ui) {
//          alert("New position: " + ui.item.index());
//      },      
      update : function () { 
        $.ajax({
          url: "<?php echo url_for(array(
                    'sf_route'    => 'gallery_sort_pics',
                    'model_id'    => $object->get('id'), 
                    'model_name'  => get_class($object->getRawValue()),
                )) ?>",
          data: $(this).sortable('serialize'),
          success: function(response) {
//            alert(response);
            var res = eval('('+response+')'); 
            
            $('#status-message').text(res.message);
          },
          error: function (request, status, error) {
            alert(' error! '+request.responseText);
          }
        })
      }
    });
		$( "ul.gallery" ).disableSelection();
    
    $('ul.gallery a.delLink').attr('onclick','');
    $('ul.gallery a.delLink').click(function (){
      if (confirm('Вы уверены?')) {
        var $parent = $(this).parent();
        $.ajax({
          url: $(this).attr('href'),
          success: function(response) {
  //          alert(response);
            var res = eval('('+response+')'); 
            $('#status-message').text(res.message);
            
            // we need update pics count in list
            $('span#nb_pics_'+res.parent_id).html(res.nb_pics)
            
            $parent.slideUp('slow');
          },
          error: function (request, status, error) {
            alert(request.responseText);
          }
        });
      }
      return false;
    });
  }
  
  bindDeleteAction();
  
  $('#file_upload').uploadify({
    'uploader'      : "<?php echo sfConfig::get('sf_gallery_uploadyfy_dir', '/myGalleryPlugin/uploadify').'/uploadify.swf' ?>",
    'script'        : "<?php echo url_for(array(
        'sf_route'    => 'gallery_upload_pics',
        'model_id'    => $object->get('id'), 
        'model_name' => get_class($object->getRawValue()),
    )) ?>",
    'scriptData'  : {
      'model_name':   "<?php echo get_class($object->getRawValue()) ?>",
      'model_id':     "<?php echo $object->get('id') ?>"
    },         
    'cancelImg'     : "<?php echo sfConfig::get('sf_gallery_uploadyfy_dir', '/myGalleryPlugin/uploadify').'/cancel.png' ?>",
    'folder'        : '/uploads',
    'multi'         : true,
    'auto'          : true,
//    'buttonText'      : 'Выбрать файлы',
    'fileExt'         : '*.jpg;*.gif;*.png',
    'fileDesc'        : 'Image Files (.JPG, .GIF, .PNG)',
    'queueID'         : 'custom-queue',
    'queueSizeLimit'  : 12,
    'simUploadLimit'  : 3,
    'sizeLimit'       : 204800,
    'removeCompleted' : true,
    'onSelectOnce'    : function(event,data) {
        $('#status-message').text(data.filesSelected + ' files have been added to the queue.');
      },  
    
    'onComplete': function(event, ID, fileObj, response, data) {
      var res = eval('('+response+')'); // eval(response);
      if (res.status == 'success') {
        $('ul.gallery').append(res.html);
        // we need update pics count in list
        $('span#nb_pics_'+res.parent_id).html(res.nb_pics)
        // unbind onClick again!!!
        bindDeleteAction();
      } else if (res.status == 'error') {
        alert(res.message);
        $('#status-message').text(res.message);
        data.errors++;
        data.filesUploaded--;
      }
    },
    
    'onError'     : function (event,ID,fileObj,errorObj) {
      alert(errorObj.type + ' Error: ' + errorObj.info);
    },    
    'onAllComplete'  : function(event, data) {
      $('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
    }
  
});
</script>
<?php ?>
