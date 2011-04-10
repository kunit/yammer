<?php
App::import('Model', 'Yammer.YammerBase');

class User extends YammerBase
{
  protected $rules = array(
    'get' => array(
      'page' => array('is_number'),
      'letter' => array(),
      'sort_by' => array('in_array' => array('messages', 'followers')),
      'reverse' => array('in_array' => array('true')),
      'delete' => array('in_array' => array('true')),
      'user_id' => array('is_number'),
      'email' => array(),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $methods = array(
      'all' => 'get_users',
      'current' => 'get_user',
      'by_id' => 'get_user',
      'by_email' => 'get_users_by_email'
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

  public function getGroups($user)
  {
    $result = array();
    if (!isset($user->web_preferences)) {
      return $result;
    }

    foreach ($user->web_preferences->home_tabs as $obj) {
      if ($obj->type == 'group') {
        $row = new stdClass();
        $row->id = $obj->group_id;
        $row->name = $obj->name;
        $row->select_name = $obj->select_name;
        $result[] = $row;
      }
    }
    return $result;
  }
}