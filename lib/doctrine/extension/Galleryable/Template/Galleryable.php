<?php

class Doctrine_Template_Galleryable extends Doctrine_Template
{
  public function setTableDefinition()
  {
    $this->addListener(new Galleryable_Listener($this->_options));
  }
  
//  public function setUp()
//  {
//  }
  
  public function hasGallerys()
  {
    return $this->getNbGallerys() > 0;
  }
  
  public function getNbGallerys()
  {
    return $this->getGallerysQuery()->count();
  }
  
  public function addGallery(Gallery $Gallery)
  {
    $Gallery->set('model_name', $this->_invoker->getTable()->getComponentName());
    $Gallery->set('model_id', $this->_invoker->get('id'));
    $Gallery->save();
    
    return $this->_invoker;
  }

  public function getAllGallerys($order = 'ASC')
  {
    return $this->getGallerysQuery($order);
  }

  public function getGallerysQuery($order = 'ASC')
  {
    $query = Doctrine::getTable('Gallery')->createQuery('c')
      ->where('c.model_id = ?', $this->_invoker->get('id'))
      ->andWhere('c.model_name = ?', $this->_invoker->getTable()->getComponentName())
      ->orderBy('c.created_at '.strtoupper($order));

//    if(sfConfig::get( 'app_myGalleryPlugin_guardbind', false ))
//    {
//      $query->leftJoin( 'c.User as u');
//    }
    return $query;
  }
}