<?php

/**
 * BaseGallery actions.
 *
 * @package    myGalleryPlugin
 * @subpackage Gallery
 * @author     228vit
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseGalleryAdminActions extends sfActions
{
  
  public function executeDeletePic(sfWebRequest $request)
  {
    $id = $request->getParameter('id', 0);
    // TODO: check if pic removed
    $res = Doctrine_Query::create()
            ->delete('Gallery fg')
            ->where('fg.id = ?', $id)
            ->execute()
    ;
    
    $isAjax = $this->getRequest()->isXmlHttpRequest();
    if ($isAjax) {
      $res = array(
          'status'  => 'success',
          'message' => 'Picture was deleted sucessfully',
          'id'      => $id,
      );
      $this->logMessage('id: '.$id.' Picture was deleted sucessfully', 'debug');

//          echo implode(',', $res); 
      echo json_encode($res);
      die();
    } else {
      $this->getUser()->setFlash('notice', 'Picture was deleted sucessfully');
      $this->redirect($request->getReferer());
    }
    
    die($request->getParameter('id', 0).' res: '.$res);
  }
  
  
  public function executeFlashUploadPics(sfWebRequest $request)
  {
    if ($request->isMethod('POST'))
    {
      
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'Asset', 'Thumbnail', 'Partial'));
      
      if (!empty($_FILES)) 
      {
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
        $targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

        $model_name = sfInflector::underscore($request->getParameter('model_name', 'nope'));
        $uploadDirName  = implode('/', array_filter(array(
            sfConfig::get('sf_upload_dir'),
            'galleries',
            $model_name
        )));
        $ext = substr($_FILES['Filedata']['name'], -4);

        $newFileName = md5(rand(1111, 9999).time()).$ext;
        $res = move_uploaded_file($tempFile, $uploadDirName."/".$newFileName);
        if ($res)
        {
          $pic = new Gallery();
          $pic->set('pic', $newFileName);
          $pic->set('model_id', $request->getParameter('model_id', 0));
          $pic->set('model_name', $request->getParameter('model_name', NULL));

          try {
            $pic->save();
          } 
          catch (Doctrine_Exception $e) 
          {
            $res = array(
                'status'  => 'error',
                'message' => $e->getMessage(),
            );
            // something went wrong, erase uploaded pic
            @unlink($uploadDirName."/".$newFileName);
            return $this->renderText(json_encode($res));
          }
        } // if moved file
          
        $this->logMessage('id: '.$request->getParameter('model_id', 0).' uploaded file: '.$newFileName , 'debug');

        $path = implode('/', array_filter(array('uploads', 'galleries', $model_name, $newFileName)));

        $thumb = thumbnail_crop($path, 50, 50, 'fake-pic.jpg', 'watermark-25.png');

        $html = get_partial('galleryAdmin/thumb', array('pic' => $pic, 'path' => $path, 'id' => $pic->id));
        $this->logMessage('html: '.$html, 'debug');


        $res = array(
            'status'  => 'success',
            'message' => $newFileName. ' file uploaded sucessfully',
            'thumb'   => $thumb,
            'id'      => $pic->id,
            'html'    => $html,
        );

//          echo implode(',', $res); 
        echo json_encode($res);
        die();
//          $this->outJson($res);
//          
//          throw new sfStopException();
          
//          die();
        // } else {
        // 	echo 'Invalid file type.';
        // }
      }
      
      die();
      
    } // if post
    else if ($request->isMethod('GET'))
    {
      die('GET request is not allowed');
    }
    
  }
  
}