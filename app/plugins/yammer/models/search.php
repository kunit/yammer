<?php
App::import('Model', 'Yammer.YammerBase');

class Search extends YammerBase
{
  protected $rules = array(
    'search' => array(
      'search' => array(),
      'page' => array('is_number'),
      'num_per_page' => array('is_number'),
    ),
  );

  public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
  {
    $params = $this->validateParams($fields, $this->rules['search']);
    if (is_null($params)) {
      return false;
    }
    return $this->yammer->get_search($params);
  }
}