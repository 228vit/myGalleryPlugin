<?php

class Doctrine_Template_Galleryable extends Doctrine_Template
{
  public function setTableDefinition()
  {
    $this->addListener(new Doctrine_Template_Listener_Galleryable($this->_options));
  }
  
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
    $Gallery->set('record_model', $this->_invoker->getTable()->getComponentName());
    $Gallery->set('record_id', $this->_invoker->get('id'));
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
      ->where('c.record_id = ?', $this->_invoker->get('id'))
      ->andWhere('c.record_model = ?', $this->_invoker->getTable()->getComponentName())
      ->orderBy('c.created_at '.strtoupper($order));

    if(sfConfig::get( 'app_myGalleryPlugin_guardbind', false ))
    {
      $query->leftJoin( 'c.User as u');
    }
    return $query;
  }
}