<?php

/**
 * BaseGallery components.
 *
 * @package    myGalleryPlugin
 * @subpackage Gallery
 * @author     228vit
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseGalleryComponents extends sfComponents
{
  public function executeFormGallery(sfWebRequest $request)
  {
    $this->form = new GalleryForm(null, array('user' => $this->getUser(), 'name' => $this->generateCryptModel()));
    $this->form->setDefault('record_model', $this->object->getTable()->getComponentName());
    $this->form->setDefault('record_id', $this->object->get('id'));
    if($request->isMethod('post'))
    {
      //preparing temporary array with sent values
      $formValues = $request->getParameter($this->form->getName());
      if(vjGallery::isPostedForm($formValues, $this->form))
      {
        if( vjGallery::isCaptchaEnabled() && !vjGallery::isUserBoundAndAuthenticated($this->getUser()) )
        {
          $captcha = array(
            'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
            'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
          );
          //Adding captcha
          $formValues = array_merge( $formValues, array('captcha' => $captcha)  );
        }
        if( vjGallery::isUserBoundAndAuthenticated($this->getUser()) )
        {
          //adding user id
          $formValues = array_merge( $formValues, array('user_id' => $this->getUser()->getGuardUser()->getId() )  );
        }

        $this->form->bind( $formValues );
        if ($this->form->isValid())
        {
          $this->form->save();
          $this->initPager($request);
          $url = $this->generateNewUrl($request->getUri());
          $this->getContext()->getController()->redirect($url, 0, 302);
        }
      }
    }
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->getResponse()->addJavascript(sfConfig::get('sf_gallery_js_dir', '/myGalleryPlugin/js'). '/jquery.fancybox.pack.js', 'last');
    $this->getResponse()->addJavascript(sfConfig::get('sf_gallery_js_dir', '/myGalleryPlugin/js'). '/swfobject.js', 'last');
    $this->getResponse()->addJavascript(sfConfig::get('sf_gallery_js_dir', '/myGalleryPlugin/js'). '/jquery.uploadify.v2.1.4.min.js', 'last');
    
    $this->getResponse()->addStylesheet(sfConfig::get('sf_gallery_css_dir', '/myGalleryPlugin/css'). '/jquery.fancybox.css', 'last');
    $this->getResponse()->addStylesheet(sfConfig::get('sf_gallery_css_dir', '/myGalleryPlugin/css'). '/uploadify.css', 'last');
    $this->getResponse()->addStylesheet(sfConfig::get('sf_gallery_css_dir', '/myGalleryPlugin/css'). '/gallery.css', 'last');
            
    if ($this->hasGallery = $this->object->hasGallery())
    {
      $this->gallery = $this->object->getGallery($sort = 'asc');
    }
    // maybe add some new pics??? ;)
    $this->form = new GalleryForm();
  }

  private function initPager(sfWebRequest $request)
  {
    if ($this->has_Gallerys = $this->object->hasGallerys())
    {
      $query = $this->object->getAllGallerys(vjGallery::getListOrder());
      $max_per_page = vjGallery::getMaxPerPage($query);
      $page = $request->getParameter('page-'.$this->generateCryptModel(), 1);

      $this->pager = new sfDoctrinePager('Gallery', $max_per_page);
      $this->pager->setQuery($query);
      $this->pager->setPage($page);
      $this->pager->init();
      $this->cpt = $max_per_page * ($page - 1);
    }
  }


  public function generateCryptModel()
  {
    $model = $this->object->getTable()->getComponentName();
    $id = $this->object->get('id');
    $this->crypt = vjGallery::getFormName($model.$id);
    return $this->crypt;
  }
}
