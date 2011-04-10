<?php
App::import('Model', 'Yammer.YammerBase');

class Topic extends YammerBase
{
  protected $rules = array(
    'get' => array(
      'topic_id' => array('is_number'),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $methods = array(
      'by_id' => 'get_topic',
    );
    $method = Set::extract($methods, $conditions);
    if (!$method) {
      return false;
    }
    $params = $this->validateParams($fields, $this->rules['get']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->$method($params);
  }
}