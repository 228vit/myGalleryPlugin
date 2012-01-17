<h1 style="margin: 0;">Галерея фото <?php echo count($gallery) ?></h1>
<?php if ($gallery): ?>
<div>
<ul class='gallery'>
  <?php foreach ($gallery as $g): ?>
    <?php echo $g->id; include_partial('myGalleryAdmin/thumb', array('thumb' => $g, 'id' => $sf_params->get('id', 0))) ?>
  <?php endforeach; // ($gallery as $g): ?>
</ul>
</div>
<br clear="all" />
<?php endif; // ($gallery): ?>
  
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
    $('ul.gallery a').attr('onclick','');
    $('ul.gallery a').click(function (){
      if (confirm('Вы уверены?')) {
        var $parent = $(this).parent();
        $.ajax({
          url: $(this).attr('href'),
          success: function(response) {
  //          alert(response);
            var res = eval('('+response+')'); 
            $('#status-message').text(res.message);
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
    'uploader'      : '/uploadify/uploadify.swf',
    'script'        : "<?php echo url_for('/backend_dev.php/gallery/flashUploadPics?id='.$sf_params->get('id', 0)) ?>",
    'cancelImg'     : '/uploadify/cancel.png',
    'folder'        : '/uploads',
    'auto'          : false,
    'multi'         : true,
    'auto'          : true,
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
//      alert('There are ' + data.fileCount + ' files remaining in the queue.');
      var res = eval('('+response+')'); // eval(response);
//      alert('Server response is: ' + res.message)
//      alert('Server response is: ' + response + ' message: ' + res.message);
//      var html = '<li id="pic_'+res.id+'"><img src="'+res.pic+'" />'
      $('ul.gallery').append(res.html);
      // unbind onClick again!!!
      bindDeleteAction();
    },
    
    'onError'     : function (event,ID,fileObj,errorObj) {
      alert(errorObj.type + ' Error: ' + errorObj.info);
    },    
    'onAllComplete'  : function(event,data) {
        $('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
      }
  
});
</script>
<?php ?>
