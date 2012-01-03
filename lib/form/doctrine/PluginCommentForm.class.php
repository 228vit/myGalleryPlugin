<?php

/**
 * PluginComment form.
 *
 * @package    myGalleryPlugin
 * @subpackage form
 * @author     Jean-Philippe MORVAN <jp.morvan@ville-villejuif.fr>
 * @version    SVN: $Id$
 */

abstract class PluginCommentForm extends PluginCommentCommonForm
{
  public function setup()
  {
    parent::setup();
    
    $user = $this->getOption('user');
    $fields = array(
      'record_model',
      'record_id',
      'author_name',
      'author_email',
      'author_website',
      'body',
      'reply'
    );
    if(myGallery::isGuardBindEnabled())
    {
      $fields[] = 'user_id';
    }
    $this->useFields($fields);
    
    $this->widgetSchema['reply_author'] = new sfWidgetFormInputText(array(), array('readonly' => "readonly"));
    $this->widgetSchema->setLabel('reply_author', __('Reply to', array(), 'myGallery'));
    $this->widgetSchema->setHelp('author_email', __('Your email will never be published', array(), 'myGallery'));
    $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();

    if( myGallery::isUserBoundAndAuthenticated($user) )
    {
        unset( $this['author_email'], $this['author_website'], $this['author_name'] );
    }
    else
    {
        unset( $this['user_id'] );
    }
    if (myGallery::isCaptchaEnabled() && !myGallery::isUserBoundAndAuthenticated($user) )
    {
      $this->addCaptcha();
    }
    $this->widgetSchema->setNameFormat($this->getOption('name').'[%s]');
  }

  protected function addCaptcha()
  {
    $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
      'public_key' => sfConfig::get('app_recaptcha_public_key')
    ));

    $this->validatorSchema['captcha'] = new sfValidatorReCaptcha(array(
      'private_key' => sfConfig::get('app_recaptcha_private_key')
    ));
    $this->validatorSchema['captcha']
        ->setMessage('captcha', __('The captcha is not valid (%error%).', array(), 'myGallery'))
        ->setMessage('server_problem', __('Unable to check the captcha from the server (%error%).', array(), 'myGallery'));
  }
}
