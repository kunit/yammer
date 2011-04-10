<?php
App::import('Model', 'Yammer.YammerBase');

class GroupMembership extends YammerBase
{
  protected $rules = array(
    'common' => array(
      'group_id' => array('is_number'),
    ),
  );

  public function join($params)
  {
    $params = $this->validateParams($params, $this->rules['common']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->post_group_memberships($params);
  }

  public function leave($params)
  {
    $params = $this->validateParams($params, $this->rules['common']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->delete_group_memberships($params);
  }
}