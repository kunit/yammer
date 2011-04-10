<?php
/**
 * Import Required libraries
 */
App::import('Model', 'Yammer.Group');

/**
 * GroupTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models
 */
class GroupTestCase extends CakeTestCase
{
  /**
   * Group Model object
   *
   * @var Group
   * @access public
   */
  public $Group = null;

  /**
   * Configuration
   *
   * @var array
   * @access public
   */
  public $config = array(
    'oauth_key' => 'SET YOUR OAUTH_KEY',
    'oauth_secret' => 'SET YOUR OAUTH_SECRET',
    'verifier' => null,
    'request_token' => null,
    'request_token_secret' => null,
    'access_token' => null,
    'access_token_secret' => null,
  );

  /**
   * Start Test
   *
   * @return void
   * @access public
   */
  public function startTest()
  {
    $this->Group = ClassRegistry::init('Group');
    $this->Group->setConfig($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->Group);
    ClassRegistry::flush();
  }

  public function testFindAll()
  {
    $result = $this->Group->find('all');
    $this->assertTrue(count($result));
  }

  public function testFindById()
  {
    $result = $this->Group->find('all');
    $this->assertTrue(count($result));
    $group_id = $result[0]->id;

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Group->find('by_id', $params);
    $this->assertTrue(isset($result->id));
    $this->assertEqual($group_id, $result->id);
  }
}