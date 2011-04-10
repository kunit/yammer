<?php
App::import('Controller', 'YammerBase');

class MessagesController extends YammerBaseController
{
  const MESSAGE_LIMIT = 10;

  public $components = array(
    'Block',
    'Session',
    'Mobile',
    'MobileSession',
    'Auth',
  );

  public $uses = array('Yammer.Message', 'Yammer.User', 'Yammer.Group', 'Yammer.Topic', 'Yammer.Subscription');

  public $helpers = array('Time', 'Yammer', 'Mobile');

  public function index($type = 'following', $type_id = null, $message_id = null)
  {
    try {
      $type = $this->parseType($type);
      $user = $this->User->find('current');

      if (!empty($this->data)) {
        $this->postMessage($type, $type_id, $user);
      }

      $messages = $this->getMessages($type, $type_id, $message_id);
      $older_available = $this->getOlderAvailable($messages);
      $messages = $this->Message->rebuild($user, $messages);
      $last_message_id = $this->getLastMessageId($messages);

      $groups = $this->User->getGroups($user);
      $this->setViewVarsByType($type, $type_id, $user, $groups);

      if (is_null($type_id)) {
        $type_id = 0;
      }

      $this->set('type', $type);
      $this->set('type_id', $type_id);
      $this->set('message_id', $message_id);
      $this->set('user', $user);
      $this->set('groups', $groups);
      $this->set('messages', $messages);
      $this->set('type_name', $this->getTypeName($type));
      $this->set('last_message_id', $last_message_id);
      $this->set('older_available', $older_available);

      $this->Session->write('Messages', $messages);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }
  }

  public function reply($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      if (!empty($this->data)) {
        $this->replyMessage($type, $type_id, $id, $message_id);
      }

      $messages = $this->Session->read('Messages');
      $message = $this->getMessage($type, $type_id, $id, $messages);

      $this->set('type', $type);
      $this->set('type_id', $type_id);
      $this->set('message_id', $message_id);
      $this->set('message', $message);
      $this->set('messages', $messages);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }
  }

  public function like($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      $params = array(
        'message_id' => $id,
      );
      $this->Message->like($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
  }

  public function unlike($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      $params = array(
        'message_id' => $id,
      );
      $this->Message->unlike($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
  }

  public function bookmark($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      $params = array(
        'message_id' => $id,
      );
      $this->Message->bookmark($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
  }

  public function unbookmark($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      $params = array(
        'message_id' => $id,
      );
      $this->Message->unbookmark($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
  }

  public function delete($type, $type_id = null, $id = null, $message_id = null)
  {
    try {
      $params = array(
        'message_id' => $id,
      );
      $this->Message->delete($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
  }

  public function error()
  {
  }

  public function logout()
  {
    $this->Session->setFlash('ログアウトしました。');
    $this->redirect($this->Auth->logout());
  }

  protected function parseType($type)
  {
    $types = $this->getTypes();
    if (!in_array($type, array_keys($types))) {
      $type = 'following';
    }

    return $type;
  }

  protected function postMessage($type, $type_id, $user)
  {
    $body = Set::extract($this->data, 'Yammer.body');
    $body = mb_convert_encoding($body, 'UTF-8', 'SJIS-win');

    if (mb_strlen($body, 'UTF-8') > 399) {
      $this->Session->setFlash('399文字以内で入力してください');
      $body = null;
    }

    if ($type == 'private') {
      $to = Set::extract($this->data, 'Yammer.to');
      $direct_to = null;
      if (strlen($to) > 0) {
        $domain = $user->network_name;
        $params = array(
          'email' => sprintf("%s@%s", $to, $domain),
        );
        $ret = $this->User->find('by_email', $params);
        if (count($ret) > 0) {
          $direct_to = $ret[0];
        }
      }
      if (is_null($direct_to)) {
        $body = null;
      }
    }

    if (strlen($body) > 0) {
      $params = array(
        'body' => $body,
      );
      if ($type == 'private') {
        $params['direct_to_id'] = $direct_to->id;
      } else if ($type == 'in_group') {
        $params['group_id'] = $type_id;
      } else if ($type == 'in_thread') {
        $params['replied_to_id'] = $type_id;
      }
      $this->Message->post($params);
    }

    if ($type == 'private') {
      $this->data['Yammer']['to'] = null;
    }
    $this->data['Yammer']['body'] = null;
  }

  protected function getMessages($type, $type_id, $message_id)
  {
    $types = $this->getTypes();

    $params = array(
      'limit' => self::MESSAGE_LIMIT,
    );
    $id_type = $types[$type];
    if (!is_null($id_type)) {
      $params[$id_type] = $type_id;
    }
    if (!is_null($message_id)) {
      $params['older_than'] = $message_id;
    }

    return $this->Message->find($type, $params);
  }

  protected function getOlderAvailable($messages)
  {
    $older_available = false;
    if (isset($messages->meta->older_available)) {
      $older_available = $messages->meta->older_available;
    }

    return $older_available;
  }

  protected function getLastMessageId($messages)
  {
    $last_message_id = null;
    $count = count($messages);
    if ($count > 0) {
      $last_message_id = $messages[$count - 1]->id;
    }

    return $last_message_id;
  }

  protected function setViewVarsByType($type, $type_id, $user, $groups)
  {
    if ($type == 'from_user') {
      $params = array(
        'user_id' => $type_id,
      );
      $from_user = $this->User->find('by_id', $params);
      if ($type_id == $user->id) {
        $from_user->self = true;
      } else {
        $from_user->self = false;
        $result = $this->Subscription->find('to_user', $params);
        if ($this->Subscription->yammer->getLastResponseCode() == 404) {
          $from_user->is_following = false;
        } else {
          $from_user->is_following = true;
        }
      }
      $this->set('from_user', $from_user);
    } else if (($type == 'private') && $type_id) {
      $params = array(
        'user_id' => $type_id,
      );
      $to_user = $this->User->find('by_id', $params);
      $this->data['Yammer']['to'] = $to_user->name;
    } else if ($type == 'in_group') {
      $ids = array();
      foreach ($groups as $group) {
        $ids[] = $group->id;
      }
      $params = array(
        'group_id' => $type_id,
      );
      $group = $this->Group->find('by_id', $params);
      if (in_array($group->id, $ids)) {
        $group->is_joined = true;
      } else {
        $group->is_joined = false;
      }
      $this->set('group', $group);
    } else if ($type == 'about_topic') {
      $params = array(
        'topic_id' => $type_id,
      );
      $topic = $this->Topic->find('by_id', $params);
      $this->set('topic', $topic);
      $this->data['Yammer']['body'] = sprintf("#%s ", str_replace(" ", "_", $topic->normalized_name));
    }
  }

  protected function replyMessage($type, $type_id, $id, $message_id)
  {
    $body = Set::extract($this->data, 'Yammer.body');
    $body = mb_convert_encoding($body, 'UTF-8', 'SJIS-win');

    if (mb_strlen($body, 'UTF-8') > 399) {
      $this->Session->setFlash('399文字以内で入力してください');
      $body = null;
    }

    if (strlen($body) > 0) {
      $params = array(
        'body' => $body,
        'replied_to_id' => $id,
      );
      $this->Message->post($params);
      $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id));
    }
  }

  protected function getMessage($type, $type_id, $message_id, $messages)
  {
    $message = null;
    foreach ($messages as $row) {
      if ($row->id == $message_id) {
        $message = $row;
        break;
      }
    }
    if (is_null($message)) {
      $this->redirect(array('action' => 'index', 'session' => session_id(), $type, $type_id));
    }

    return $message;
  }

  protected function getTypes()
  {
    return array(
      'all' => null,
      'sent' => null,
      'received' => null,
      'private' => null,
      'following' => null,
      'from_user' => 'user_id',
      'about_topic' => 'topic_id',
      'in_group' => 'group_id',
      'liked_by' => null,
      'bookmarked_by' => null,
      'in_thread' => 'thread_id',
    );
  }

  protected function getTypeName($type)
  {
    $types = array(
      'all' => 'Company Feed',
      'sent' => 'Sent',
      'received' => 'Mentions',
      'private' => 'Direct Messages',
      'following' => 'My Feed',
      'from_user' => 'From User',
      'about_topic' => 'Topics',
      'in_group' => 'Groups',
      'liked_by' => 'Liked',
      'bookmarked_by' => 'Bookmarked',
      'in_thread' => 'Thread',
    );

    return Set::extract($types, $type);
  }
}