<?php

/**
 * commentAdmin module configuration.
 *
 * @package    myGalleryPlugin
 * @subpackage commentAdmin
 * @author     228vit@gmail.com
 * @version    SVN: $Id: configuration.php 12474 2008-10-31 10:41:27Z fabien $
 */
class commentAdminGeneratorConfiguration extends BaseCommentAdminGeneratorConfiguration
{
    
    public function getFilterDefaults()
    {
        return array('is_delete' => 0);
    }

}
