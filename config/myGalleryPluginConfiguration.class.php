<?php
/**
 * myGalleryPlugin configuration.
 * 
 * @package    myGalleryPlugin
 * @author     228vit
 */
class myGalleryPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('myGalleryRouting', 'listenToRoutingLoadConfigurationEvent'));
    if (in_array('myGallery', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('myGalleryRouting', 'addRouteForGallery'));
    }
  }


}
