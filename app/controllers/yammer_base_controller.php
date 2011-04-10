<?php
class YammerBaseController extends AppController
{
  public function beforeFilter()
  {
    session_start();

    $this->Auth->userModel = 'Yammer';
    $this->Auth->fields = array('username' => 'email', 'password' => 'password');
    $this->Auth->loginAction = array('controller' => 'top', 'action' => 'index');
    $this->Auth->loginRedirect = array('controller' => 'messages', 'action' => 'index', 'session' => session_id());
    $this->Auth->logoutRedirect = array('controller' => 'top', 'action' => 'index', 'session' => session_id());
    $this->Auth->userScope = array('NOT' => array('Yammer.access_token' => null));

    config('yammer');
    $oauth_key = Configure::read('Yammer.oauth_key');
    $oauth_secret = Configure::read('Yammer.oauth_secret');

    $yammer = $this->Auth->user();

    $this->yammer_config = array(
      'oauth_key' => $oauth_key,
      'oauth_secret' => $oauth_secret,
      'verifier' => Set::extract($yammer, 'Yammer.verifier'),
      'request_token' => Set::extract($yammer, 'Yammer.request_token'),
      'request_token_secret' => Set::extract($yammer, 'Yammer.request_token_secret'),
      'access_token' => Set::extract($yammer, 'Yammer.access_token'),
      'access_token_secret' => Set::extract($yammer, 'Yammer.access_token_secret'),
    );
    ClassRegistry::init($this->modelClass)->setConfig($this->yammer_config);

    parent::beforeFilter();
  }
}
