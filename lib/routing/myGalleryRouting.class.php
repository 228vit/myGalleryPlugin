<?php
/**
 * myGalleryPlugin routing.
 *
 * @package    myGalleryPlugin
 * @author     228vit@gmail.com
 */
class myGalleryRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    $r->prependRoute(
      'gallery_view',
      new sfRoute(
        '/gallery_view',
        array('module' => 'gallery', 'action' => 'view')
      )
    );
    
    $r->prependRoute(
      'gallery_upload_pics',
      new sfRoute(
        '/gallery_upload_pics',
        array('module' => 'galleryAdmin', 'action' => 'flashUploadPics')
      )
    );
    
    $r->prependRoute(
      'gallery_sort_pics',
      new sfRoute(
        '/gallery_sort_pics',
        array('module' => 'galleryAdmin', 'action' => 'sort')
      )
    );
    
    $r->prependRoute(
            'comment_reporting',
            new sfRoute(
                    '/report-a-comment/:id/:num',
                    array('module' => 'comment', 'action' => 'reporting'),
                    array('id' => '\d+', 'num' => '\d+')
            )
    );
  }

  /**
   * Adds an sfDoctrineRouteCollection collection to manage comments.
   *
   * @param sfEvent $event
   * @static
   */
  static public function addRouteForGallery(sfEvent $event)
  {
    $event->getSubject()->prependRoute('commentAdmin', new sfDoctrineRouteCollection(array(
      'name'                => 'commentAdmin',
      'model'               => 'Comment',
      'module'              => 'commentAdmin',
      'prefix_path'         => 'admin-for-comments',
      'with_wildcard_routes' => true,
      'requirements'        => array(),
    )));
  }

  /**
   * Adds an sfDoctrineRouteCollection collection to manage reported comments.
   *
   * @param sfEvent $event
   * @static
   */
  static public function addRouteForAdminReportedComments(sfEvent $event)
  {
    $event->getSubject()->prependRoute('commentReportAdmin', new sfDoctrineRouteCollection(array(
      'name'                => 'commentReportAdmin',
      'model'               => 'CommentReport',
      'module'              => 'commentReportAdmin',
      'prefix_path'         => 'admin-for-reported-comments',
      'with_wildcard_routes' => true,
      'requirements'        => array(),
    )));
  }
}