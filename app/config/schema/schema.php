<?php
class AppSchema extends CakeSchema
{
  public $name = 'App';

  public function before($event = array())
  {
    return true;
  }
  public function after($event = array())
  {
  }

  public $yammers = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
    'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
    'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
    'request_token' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
    'request_token_secret' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
    'auth_url' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 1000),
    'verifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
    'access_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
    'access_token_secret' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
    'guid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
    'created' => array('type' => 'timestamp', 'null' => false),
    'modified' => array('type' => 'timestamp', 'null' => true),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
  );
}