<?php
/**
 * Import Required libraries
 */
App::import('Model', 'Yammer.GroupMembership');

/**
 * GroupMembershipTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models
 */
class GroupMembershipTestCase extends CakeTestCase
{
  /**
   * GroupMembership Model object
   *
   * @var GroupMembership
   * @access public
   */
  public $GroupMembership = null;

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
    $this->GroupMembership = ClassRegistry::init('GroupMembership');
    $this->GroupMembership->setConfig($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->GroupMembership);
    ClassRegistry::flush();
  }

  public function testJoinAndLeave()
  {
    $result = $this->GroupMembership->yammer->get_user();
    $user_id = $result->id;

    $result = $this->GroupMembership->yammer->get_groups();
    $this->assertTrue(count($result) > 0);
    $group_id = $result[0]->id;
    $members = $result[0]->stats->members;

    $params = array(
      'group_id' => $group_id,
      'user_id' => $user_id,
    );
    $result = $this->GroupMembership->leave($params);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->GroupMembership->yammer->get_group($params);
    $this->assertEqual(1, $members - $result->stats->members);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->GroupMembership->join($params);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->GroupMembership->yammer->get_group($params);
    $this->assertEqual(0 , $members - $result->stats->members);
  }
}