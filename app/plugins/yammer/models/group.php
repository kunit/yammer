<?php
App::import('Model', 'Yammer.YammerBase');

class Group extends YammerBase
{
  protected $rules = array(
    'get' => array(
      'page' => array('is_number'),
      'letter' => array(),
      'sort_by' => array('in_array' => array('messages', 'members', 'privacy', 'created_at', 'creator')),
      'reverse' => array('in_array' => array('true')),
      'name' => array(),
      'private' => array('in_array' => array('true')),
      'group_id' => array('is_number'),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $methods = array(
      'all' => 'get_groups',
      'by_id' => 'get_group',
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