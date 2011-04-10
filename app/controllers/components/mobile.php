<?php
App::import('Vendor', 'Net_UserAgent_Mobile', array('file' => 'Net/UserAgent/Mobile.php'));
App::import('Vendor', 'HTML_CSS_Mobile', array('file' => 'HTML/CSS/Mobile.php'));

class MobileComponent extends Object
{
  const CARRIER_DOCOMO = 'docomo';
  const CARRIER_AU = 'au';
  const CARRIER_SOFTBANK = 'softbank';
  const CARRIER_WILLCOM = 'willcom';
  const CARRIER_IPHONE = 'iphone';
  const CARRIER_ANDROID = 'android';

  protected $controller;

  protected $settings = array();
  protected $ua;
  protected $carrier;
  protected $layout;
  protected $doc_type;
  protected $content_type;
  protected $charset;

  public function initialize(&$controller, $settings = array())
  {
    $this->controller =& $controller;
    $this->settings = array_merge($this->settings, $settings);

    $this->ua = Net_UserAgent_Mobile::singleton();
    $this->controller->user_agent_mobile = $this->ua;

    $this->detectCarrier();
    $this->controller->carrier = $this->carrier;
    $this->setLayoutAndThema();

    $this->controller->set('doc_type', $this->doc_type);
    $this->controller->set('content_type', $this->content_type);
    $this->controller->set('charset', $this->charset);
  }

  public function startup(&$controller)
  {
    $this->controller->view = 'Theme';
    $this->controller->layout = $this->layout;
    $this->controller->theme = $this->theme;
  }

  public function shutdown(&$controller)
  {
    if ($this->charset == 'Shift_JIS') {
      $output = $this->controller->output;
      $output = mb_convert_kana($output, 'ask', 'UTF-8');
      $output = mb_convert_encoding($output, 'SJIS-win', 'UTF-8');
      if ($this->carrier == self::CARRIER_DOCOMO) {
        $output = @HTML_CSS_Mobile::getInstance()->setMode('transit')->apply($output);
      }
      $config = array(
        'basePath' => CONFIGS . "emoji4unicode" . DIRECTORY_SEPARATOR,
      );
      $emoji = new Emoji4Unicode($config);
      $output = $emoji->convert($output, $this->carrier, 'docomo');
      $this->controller->output = $output;
      header(sprintf('Content-Type: %s; charset=%s', $this->content_type, $this->charset));
    }
  }

  protected function detectCarrier()
  {
    if ($this->ua->isDoCoMo()) {
      $this->carrier = self::CARRIER_DOCOMO;
    } else if ($this->ua->isSoftBank()) {
      $this->carrier = self::CARRIER_SOFTBANK;
    } else if ($this->ua->isEZweb()) {
      $this->carrier = self::CARRIER_AU;
    } else if ($this->ua->isWillcom()) {
      $this->carrier = self::CARRIER_WILLCOM;
    } else if (preg_match('!iPhone|iPod!', $this->ua->getUserAgent())) {
      $this->carrier = self::CARRIER_IPHONE;
    } elseif (preg_match('!Android!i', $this->ua->getUserAgent())) {
      $this->carrier = self::CARRIER_ANDROID;
    } else {
      $this->carrier = null;
    }
  }

  protected function setLayoutAndThema()
  {
    $this->layout = 'default';
    $this->theme = 'default';
    $this->doc_type = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
EOT;
    $this->content_type = 'text/html';
    $this->charset = 'UTF-8';

    switch ($this->carrier) {
    case self::CARRIER_DOCOMO:
      $this->doc_Type = <<<EOT
<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.0) 1.0//EN" "i-xhtml_4ja_10.dtd">
EOT;
      $this->content_type = 'application/xhtml+xml';
      $this->layout = 'mobile';
      $this->theme = 'mobile';
      $this->charset = 'Shift_JIS';
      break;
    case self::CARRIER_SOFTBANK:
      $this->doc_Type = <<<EOT
<!DOCTYPE html PUBLIC "-//JPHONE//DTD XHTML Basic 1.0 Plus//EN" "xhtml-basic10-plus.dtd">
EOT;
      $this->layout = 'mobile';
      $this->theme = 'mobile';
      $this->charset = 'Shift_JIS';
      break;
    case self::CARRIER_AU:
      $this->doc_Type = <<<EOT
<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" "http://www.openwave.com/DTD/xhtml-basic.dtd">
EOT;
      $this->layout = 'mobile';
      $this->theme = 'mobile';
      $this->charset = 'Shift_JIS';
      break;
    case 'iphone':
    case 'android':
      $this->layout = 'sp';
      $ths->theme = 'sp';
      break;
    default:
      break;
    }
  }
}

class Emoji4Unicode
{
  protected $_config = array();

  private $__carrierMap = array(
    'docomo' => 'docomo',
    'au' => 'kddi',
    'softbank' => 'softbank',
    'google' => 'google',
  );

  private $__xmlObject = null;

  public function __construct(array $config = array())
  {
    $defaults = array(
      'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR,
      'dataSource' => 'emoji4unicode.xml',
    );
    $config += $defaults;
    $dataSource = $config['basePath'] . $config['dataSource'];
    if (!file_exists($dataSource)) {
      throw new ErrorException('DataSource is not exists. "' . $dataSource . '"');
    }
    $this->__xmlObject = simplexml_load_file($dataSource);

    $this->_config = $config;
  }

  public function setCarrierMap(array $carrierMap)
  {
    $carrierMap += $this->__carrierMap;
    return $this->__carrierMap = $carrierMap;
  }

  private function __convertCarrierMap($carrier)
  {
    $result = Set::extract($this->__carrierMap, $carrier);
    if ($result === false) {
      throw new ErrorException('Undefined carrier name. "' .  $carrier . '"');
    }
    return $result;
  }

  public function convert($string, $to, $from, array $options = array())
  {
    $to = $this->__convertCarrierMap($to);
    $from = $this->__convertCarrierMap($from);

    $default = array(
      'textFallback' => true,
      'defaultText' => '[emoji]',
      'binary' => false,
      'encoding' => 'UTF-8',
    );
    $options += $default;

    preg_match_all('/&#x([0-9A-F]+);/', $string, $matches);
    $matches[0] = array_unique($matches[0]);
    $matches[1] = array_unique($matches[1]);

    $map = $this->__xmlObject;
    foreach ($matches[1] as $key => $match) {
      $results = $map->xpath('//e[@' . $from . '="' . $match . '"]');
      $result = current($results);

      try {
        $replace = $this->__toEmoji($result, $to, $options);
      } catch (Exception $e) {
        $replace = $this->__toText($result, $options);
      }

      $search = $matches[0][$key];
      $string = str_replace($search, $replace, $string);
    }
    return $string;
  }

  private function __toEmoji($result, $to, $options)
  {
    if (!isset($result[$to])) {
      throw new Exception('Emoji not found');
    }

    $result[$to] = str_replace('>', '', $result[$to]);
    if ($options['binary'] === true) {
      $replace = mb_convert_encoding(pack('H*', $result[$to]), $options['encoding'], 'UCS-2');
    } else {
      $replace = '&#x' . $result[$to] . ';';
    }

    return $replace;
  }

  private function __toText($result, $options)
  {
    if ($options['textFallback'] === false) {
      return '';
    }
    $replace = $options['defaultText'];
    if (isset($result['text_fallback'])) {
      $replace = $result['text_fallback'];
    }
    return $replace;
  }
}
