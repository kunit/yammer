<?php
App::import('Vendor', 'Net_UserAgent_Mobile', array('file' => 'Net/UserAgent/Mobile.php'));

class MobileHelper extends AppHelper
{
  public function addInputMode($mode, $options = array())
  {
    $ua = Net_UserAgent_Mobile::singleton();
    if ($ua->isDoCoMo()) {
      $carrier = 'docomo';
    } else if ($ua->isSoftBank()) {
      $carrier = 'softbank';
    } else if ($ua->isEZweb()) {
      $carrier = 'au';
    }
    $ret = $this->getInputMode($mode, $carrier);

    return Set::merge($options, $ret);
  }

  protected function getInputMode($mode, $carrier)
  {
    $list = array(
      'hiragana' => array(
        'docomo' => array('istyle' => 1, 'style' => '-wap-input-format:"*<ja:h>"'),
        'au' => array('istyle' => 1),
        'softbank' => array('istyle' => 1, 'mode' => 'hiragana'),
      ),
      'hankakukana' => array(
        'docomo' => array('istyle' => 2, 'style' => '-wap-input-format:"*<ja:hk>"'),
        'au' => array('istyle' => 2),
        'softbank' => array('istyle' => 2, 'mode' => 'hankakukana'),
      ),
      'alphabet' => array(
        'docomo' => array('istyle' => 3, 'style' => '-wap-input-format:"*<ja:en>"'),
        'au' => array('istyle' => 3),
        'softbank' => array('istyle' => 3, 'mode' => 'alphabet'),
      ),
      'numeric' => array(
        'docomo' => array('istyle' => 4, 'style' => '-wap-input-format:"*<ja:n>"'),
        'au' => array('istyle' => 4),
        'softbank' => array('istyle' => 4, 'mode' => 'numeric'),
      ),
    );

    return Set::extract($list, "{$mode}.{$carrier}");
  }
}
