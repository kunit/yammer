<?php
App::import('Datasource', 'Yammer.YammerSource');

class YammerBase extends AppModel
{
  public $useDbConfig = 'yammer';
  public $useTable = false;

  /**
   * Yammer Datasource object
   *
   * @var YammerSource
   * @access public
   */
  public $yammer = null;

  public function __construct($id = false, $table = null, $ds = null)
  {
    $this->yammer = ConnectionManager::getDataSource('yammer');
    parent::__construct($id, $table, $ds);
  }

  public function setConfig($config)
  {
    $this->yammer->setConfig($config);
  }

  public function getLastResponse()
  {
    return $this->yammer->getLastResponse();
  }

  protected function validateParams($params, $rules)
  {
    $result = array();
    if (!is_array($params) || (count($params) < 1)) {
      return $result;
    }
    foreach ($params as $key => $value) {
      if (isset($rules[$key])) {
        foreach ($rules[$key] as $method => $options) {
          if (preg_match('|^\d+$|', $method)) {
            $method = $options;
            $options = null;
          }
          if (!$this->$method($value, $options)) {
            $result = null;
            break 2;
          }
        }
        $result[$key] = $value;
      }
    }

    return $result;
  }

  public function is_number($value, $options = null)
  {
    return preg_match('|^\d+$|', $value);
  }

  public function in_array($value, $options = array())
  {
    return in_array($value, $options);
  }

  public function is_number_or_current($value, $options = null)
  {
    return (preg_match('|^\d+$|', $value) || ($value == 'current'));
  }

  public function is_boolean($value, $options = null)
  {
    return is_bool($value);
  }
}