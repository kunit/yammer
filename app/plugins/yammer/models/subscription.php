<?php
App::import('Model', 'Yammer.YammerBase');

class Subscription extends YammerBase
{
  protected $rules = array(
    'get' => array(
      'user_id' => array('is_number'),
      'thread_id' => array('is_number'),
      'topic_id' => array('is_number'),
    ),
    'post' => array(
      'target_type' => array('in_array' => array('user', 'thread', 'topic')),
      'target_id' => array('is_number'),
    ),
    'delete' => array(
      'target_type' => array('in_array' => array('user', 'thread', 'topic')),
      'target_id' => array('is_number'),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $methods = array(
      'to_user' => 'get_subscriptions_to_user',
      'to_thread' => 'get_subscriptions_to_thread',
      'to_topic' => 'get_subscriptions_to_topic',
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

  public function post($params = array())
  {
    $params = $this->validateParams($params, $this->rules['post']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->post_subscriptions($params);
  }

  public function delete($params = array())
  {
    $params = $this->validateParams($params, $this->rules['delete']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->delete_subscriptions($params);
  }
}