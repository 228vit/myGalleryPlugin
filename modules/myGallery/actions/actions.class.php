<?php

require_once dirname(__FILE__).'/../lib/BaseGalleryActions.class.php';

/**
 * gallery actions.
 *
 * @package    myGalleryPlugin
 * @subpackage gallery
 * @author     228vit
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class galleryActions extends BaseGalleryActions
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

        $uploadDirName  = sfConfig::get('sf_upload_dir').'/'.myConfig::get('gallery_dir', 'gallery');
        $ext = substr($_FILES['Filedata']['name'], -4);

        $newFileName = md5(rand(1111, 9999).time()).$ext;
        $res = move_uploaded_file($tempFile, $uploadDirName."/".$newFileName);
        if ($res)
        {
          $flatPic = new FlatGallery();
          $flatPic->set('pic', $newFileName);
          $flatPic->set('flat_id', $request->getParameter('id', 0));

          try {
            $flatPic->save();
          } 
          catch (Doctrine_Exception $e) 
          {
            $res = array(
                'status'  => 'error',
                'message' => $e->getMessage(),
            );
//              echo 'error: '.$e->getMessage()."\r\n";
            // something went wrong, erase uploaded pic
            @unlink($uploadDirName."/".$newFileName);
            return $this->renderText(json_encode($res));
          }
        } // if moved file
          
        //echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
        $this->logMessage('id: '.$request->getParameter('id', 0).' uploaded file: '.$newFileName , 'debug');

        $path = 'uploads/'.myConfig::get('gallery_dir', 'gallery').'/'.$newFileName;

        $url = url_for('@homepage');

        $thumb = thumbnail_crop($path, 50, 50, 'fake-flat.jpg', 'watermark-25.png');

        $html = get_partial('gallery/flatPic', array('flatPic' => $flatPic));

        $res = array(
            'status'  => 'success',
            'message' => $newFileName. ' file uploaded sucessfully',
            'thumb'   => $thumb,
            'id'      => $flatPic->id,
            'html'    => $html,
            'home'    => $url,
        );

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
      $this->gallery = Doctrine::getTable('FlatGallery')->findAllBy('flat_id', $request->getParameter('id', 0));
    }
    
  }
  
}
