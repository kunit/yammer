<?php
App::import('Model', 'Yammer.YammerBase');

class Message extends YammerBase
{
  protected $rules = array(
    'get' => array(
      'older_than' => array('is_number'),
      'newer_than' => array('is_number'),
      'threaded' => array('in_array' => array('true', 'extended')),
      'limit' => array('is_number'),
      'user_id' => array('is_number'),
      'topic_id' => array('is_number'),
      'group_id' => array('is_number'),
      'thread_id' => array('is_number'),
    ),
    'post' => array(
      'body' => array(),
      'group_id' => array('is_number'),
      'replied_to_id' => array('is_number'),
      'direct_to_id' => array('is_number'),
      'broadcast' => array('in_array' => array('true')),
    ),
    'delete' => array(
      'message_id' => array('is_number'),
    ),
    'like' => array(
      'message_id' => array('is_number'),
    ),
    'bookmark' => array(
      'message_id' => array('is_number'),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $methods = array(
      'all' => 'get_messages_all',
      'sent' => 'get_messages_sent',
      'received' => 'get_messages_received',
      'private' => 'get_messages_private',
      'following' => 'get_messages_following',
      'from_user' => 'get_messages_from_user',
      'about_topic' => 'get_messages_about_topic',
      'in_group' => 'get_messages_in_group',
      'liked_by' => 'get_messages_liked_by',
      'bookmarked_by' => 'get_messages_bookmarked_by',
      'in_thread' => 'get_messages_in_thread',
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
    return $this->yammer->post_message($params);
  }

  public function delete($params = array())
  {
    $params = $this->validateParams($params, $this->rules['delete']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->delete_message($params);
  }

  public function like($params = array())
  {
    $params = $this->validateParams($params, $this->rules['like']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->post_messages_liked_by($params);
  }

  public function unlike($params = array())
  {
    $params = $this->validateParams($params, $this->rules['like']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->delete_messages_liked_by($params);
  }

  public function bookmark($params = array())
  {
    $params = $this->validateParams($params, $this->rules['bookmark']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->post_messages_bookmarked_by($params);
  }

  public function unbookmark($params = array())
  {
    $params = $this->validateParams($params, $this->rules['bookmark']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->delete_messages_bookmarked_by($params);
  }

  public function rebuild($user, $messages)
  {
    $result = array();
    if (!isset($messages->messages) || (count($messages->messages) < 1)) {
      return $result;
    }

    foreach ($messages->messages as $message) {
      $row = new stdClass();
      $row->id = $message->id;
      $row->privacy = $message->privacy;
      $row->group = $this->get_group($messages, $message);
      $row->message_type = $message->message_type;
      $row->thread = $this->get_thread($messages, $message->thread_id);
      $row->client_type = $message->client_type;
      $row->client_url= $message->client_url;
      $row->body = $this->get_body($messages, $message->body);
      $row->sender = $this->get_sender($messages, $message->sender_id);
      $row->liked_by = $this->get_liked_by($user, $messages, $message->liked_by);
      $row->bookmarked_by = $this->get_bookmarked_by($messages, $message);
      $row->replied_to = $this->get_replied_to($messages, $message->replied_to_id);
      if (!isset($message->direct_to_id)) {
        $message->direct_to_id = null;
      }
      $row->direct_to = $this->get_direct_to($messages, $message->direct_to_id);
      $row->created_at = $message->created_at;
      if ($user->id == $message->sender_id) {
        $row->is_delete = true;
      } else {
        $row->is_delete = false;
      }
      $result[] = $row;
    }

    return $result;
  }

  protected function get_group($messages, $message)
  {
    $result = new stdClass();
    $result->id = null;
    if (!isset($message->group_id)) {
      return $result;
    }
    $group_id = $message->group_id;
    foreach ($messages->references as $obj) {
      if (($obj->type == 'group') && ($obj->id == $group_id)) {
        $result->id = $obj->id;
        $result->name = $obj->name;
        $result->full_name = $obj->full_name;
        break;
      }
    }

    return $result;
  }

  protected function get_thread($messages, $thread_id)
  {
    $result = new stdClass();
    $result->id = null;
    $result->thread_starter_id = null;
    foreach ($messages->references as $obj) {
      if (($obj->type == 'thread') && ($obj->id == $thread_id)) {
        $result->id = $obj->id;
        $result->thread_starter_id = $obj->thread_starter_id;
        break;
      }
    }

    return $result;
  }

  protected function get_body($messages, $body)
  {
    $result = new stdClass();
    $result->parsed = $body->parsed;
    $result->plain = $body->plain;
    $result->urls = null;
    if (isset($body->urls)) {
      $result->urls = $body->urls;
    }
    $result->references = array();

    preg_match_all('|\[\[(\w+):(\d+)\]\]|', $body->parsed, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
      $type = $match[1];
      $id = $match[2];
      foreach ($messages->references as $obj) {
        if (($obj->type == $type) && ($obj->id == $id)) {
          $row = new stdClass();
          $row->id = $obj->id;
          $row->type = $obj->type;
          $row->name = $obj->name;
          if ($type == 'tag') {
            preg_match("|/(\d+)$|", $obj->web_url, $matches);
            if (count($matches) > 0) {
              $row->topic_id = $matches[1];
            }
          }
          $result->references[] = $row;
        }
      }
    }

    return $result;
  }

  protected function get_sender($messages, $sender_id)
  {
    $result = new stdClass();
    $result->id = null;
    $result->name = null;
    $result->full_name = null;
    $result->icon_url = null;
    foreach ($messages->references as $obj) {
      if (($obj->type == 'user') && ($obj->id == $sender_id)) {
        $result->id = $obj->id;
        $result->name = $obj->name;
        $result->full_name = $obj->full_name;
        $result->icon_url = $obj->mugshot_url;
        break;
      }
    }

    return $result;
  }

  protected function get_liked_by($user, $message, $liked_by)
  {
    $result = new stdClass();
    $result->count = $liked_by->count;
    $result->is_liked = false;
    foreach ($liked_by->names as $name) {
      if ($name->permalink == $user->name) {
        $result->is_liked = true;
      }
    }

    return $result;
  }

  protected function get_bookmarked_by($messages, $message)
  {
    $result = new stdClass();
    $result->is_bookmarked = false;
    if (isset($messages->meta->bookmarked_message_ids)) {
      foreach ($messages->meta->bookmarked_message_ids as $id) {
        if ($message->id == $id) {
          $result->is_bookmarked = true;
          break;
        }
      }
    }

    return $result;
  }

  protected function get_replied_to($messages, $replied_to_id)
  {
    $result = new stdClass();
    $result->id = null;
    $result->sender_id = null;
    $result->name = null;
    $result->full_name = null;
    foreach ($messages->references as $obj) {
      if (($obj->type == 'message') && ($obj->id == $replied_to_id)) {
        $result->id = $obj->id;
        $sender = $this->get_sender($messages, $obj->sender_id);
        $result->sender_id = $obj->sender_id;
        $result->name = $sender->name;
        $result->full_name = $sender->full_name;
        break;
      }
    }

    return $result;
  }

  protected function get_direct_to($messages, $direct_to_id)
  {
    $result = new stdClass();
    $result->id = null;
    $result->name = null;
    $result->full_name = null;
    foreach ($messages->references as $obj) {
      if (($obj->type == 'user') && ($obj->id == $direct_to_id)) {
        $result->id = $obj->id;
        $result->name = $obj->name;
        $result->full_name = $obj->full_name;
        break;
      }
    }

    return $result;
  }
}