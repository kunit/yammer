<?php
App::import('Controller', 'YammerBase');

class UsersController extends YammerBaseController
{
  public $components = array(
    'Block',
    'Session',
    'Mobile',
    'MobileSession',
    'Auth',
  );

  public $uses = array('Yammer.User', 'Yammer.Subscription');

  public $helpers = array('Time', 'Yammer');

  public function index($page = 1)
  {
    try {
      $user = $this->User->find('current');

      $users = $this->User->find('all');
      foreach ($users as $key => $row) {
        if ($row->id == $user->id) {
          $users[$key]->self = true;
          continue;
        }
        $users[$key]->self = false;
        $params = array(
          'user_id' => $row->id,
        );
        $result = $this->Subscription->find('to_user', $params);
        if ($this->Subscription->yammer->getLastResponseCode() == 404) {
          $users[$key]->is_following = false;
        } else {
          $users[$key]->is_following = true;
        }
      }

      $this->set('network_name', $user->network_name);
      $this->set('users', $users);
      $this->set('page', $page);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }
  }

  public function follow($user_id, $type = null, $type_id = null)
  {
    try {
      $params = array(
        'target_type' => 'user',
        'target_id' => $user_id,
      );
      $this->Subscription->post($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    if ($type && $type_id) {
      $this->redirect(array('controller' =>'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id));
    } else {
      $this->redirect(array('action' => 'index', 'session' => session_id()));
    }
  }

  public function unfollow($user_id, $type = null, $type_id = null)
  {
    try {
      $params = array(
        'target_type' => 'user',
        'target_id' => $user_id,
      );
      $this->Subscription->delete($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    if ($type && $type_id) {
      $this->redirect(array('controller' =>'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id));
    } else {
      $this->redirect(array('action' => 'index', 'session' => session_id()));
    }
  }
}