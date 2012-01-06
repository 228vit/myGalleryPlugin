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
  
  public function executeSort(sfWebRequest $request)
  {
    
    $modelName  = $request->getParameter('model_name', '');
    $modelId    = $request->getParameter('model_id', 0);
    
//    $firstItem = Doctrine_Query::create()
//            ->from('Gallery g')
//            ->where('g.model_name = ?', $modelName)
//            ->addWhere('g.model_id = ?', $modelId)
//            ->orderBy('g.position asc')
//            ->fetchOne()
//    ;
    $lastItem = Doctrine_Query::create()
            ->from('Gallery g')
            ->where('g.model_name = ?', $modelName)
            ->addWhere('g.model_id = ?', $modelId)
            ->orderBy('g.position DESC')
            ->fetchOne()
    ;
    
    $pics = $request->getParameter('pic');
    if (count($pics) > 0)
    {
      
      $objects = Doctrine_Query::create()
              ->from('Gallery g')
              ->whereIn('g.id', $pics)
              ->addWhere('g.model_name = ?', $modelName)
              ->addWhere('g.model_id = ?', $modelId)
              ->orderBy('g.position ASC')
              ->execute()
      ;
      
      $conn = Doctrine_Manager::connection();
      $conn->beginTransaction();
      
//      foreach ($objects as $object) {
      foreach ($pics as $id) {
        $object = Doctrine_Core::getTable('Gallery')->find($id);
        if ($object)
        {
          $this->logMessage('pic id: '.$object->id.' been sorted, moved down ', 'debug');
          $object->moveToLast();
        } else {
            $this->logMessage('unexisting id detected: '.$id , 'error');
        }
//      $object->moveToPosition((int)$lastItem->position);
      }
      // commit transactions, if any in a stack
      $conn->commit();
    }
    /* 
     * $ids_list = implode(',', array_values($values));
      $values_list = implode(',', array_keys($values));
      $sql = 'UPDATE '.$table.' SET sortOrder = ELT(FIELD(id, '.$ids_list.'), '.$values_list.') WHERE id IN ('.$ids_list.')';
     */    
    
    $isAjax = $this->getRequest()->isXmlHttpRequest();
    if ($isAjax) {
      $res = array(
          'status'  => 'success',
          'message' => 'Pictures was sorted sucessfully',
//          'id'      => $id,
      );
      $data = implode(', ', $pics);
      $this->logMessage('Sort data'.$data, 'debug');
      echo json_encode($res);
      die();
    } 
    else 
    {
      // do something if not ajax
      $this->getUser()->setFlash('notice', 'Pictures was sorted sucessfully');
      $referer = $request->getReferer();
      if (empty($referer))
      {
        $referer = sfInflector::underscore($modelName).'/edit?id='.$modelId;
      }
      $this->redirect($referer);
    }
    
//    $this->forward404Unless($startRec);
//    $this->forward404Unless($endRec);
    
  }

  
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