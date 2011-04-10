<?php
App::import('Controller', 'YammerBase');

class GroupsController extends YammerBaseController
{
  public $components = array(
    'Block',
    'Session',
    'Mobile',
    'MobileSession',
    'Auth',
  );

  public $uses = array('Yammer.Group', 'Yammer.User', 'Yammer.GroupMembership');

  public $helpers = array('Time', 'Yammer');

  public function index($page = 1)
  {
    try {
      $user = $this->User->find('current');
      $groups = $this->User->getGroups($user);
      $ids = array();
      foreach ($groups as $group) {
        $ids[] = $group->id;
      }

      $groups = $this->Group->find('all');
      foreach ($groups as $key => $group) {
        if (in_array($group->id, $ids)) {
          $groups[$key]->is_joined = true;
        } else {
          $groups[$key]->is_joined = false;
        }
      }

      $this->set('network_name', $user->network_name);
      $this->set('groups', $groups);
      $this->set('page', $page);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }
  }

  public function join($group_id, $type = null, $type_id = null)
  {
    try {
      $params = array(
        'group_id' => $group_id,
      );
      $this->GroupMembership->join($params);
    } catch (Exception $e) {
      $this->redirect(array('controller' => 'messages', 'action' => 'error', 'session' => session_id()));
    }

    if ($type && $type_id) {
      $this->redirect(array('controller' =>'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id));
    } else {
      $this->redirect(array('action' => 'index', 'session' => session_id()));
    }
  }

  public function leave($group_id, $type = null, $type_id = null)
  {
    try {
      $params = array(
        'group_id' => $group_id,
      );
      $this->GroupMembership->leave($params);
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