<?php

class Galleryable_Listener extends Doctrine_Record_Listener
{
  protected $_options;
  
  public function __construct($options = array())
  {
    $this->_options = $options;
  }

  public function postDelete(Doctrine_Event $event)
  {
    $event->getInvoker()->getAllGallerys()->delete();
  }
}