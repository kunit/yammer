<?php
App::import('Vendor', 'Net_UserAgent_Mobile', array('file' => 'Net/UserAgent/Mobile.php'));

class MobileSessionComponent extends Object
{
  protected $controller;
  protected $settings = array();

  public function initialize(&$controller, $settings = array())
  {
    $this->controller =& $controller;
    $this->settings = array_merge($this->settings, $settings);

    $ua = Net_UserAgent_Mobile::singleton();
    if (($ua->isDoCoMo() || $ua->isSoftBank() || $ua->isEZweb())) {
      $session = Set::extract($this->controller->params, 'session');
      if (strlen($session) > 0) {
        session_id($session);
      }
    }
  }

  public function startup(&$controller)
  {
  }
}