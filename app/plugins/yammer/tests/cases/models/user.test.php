<?php
/**
 * Import Required libraries
 */
App::import('Model', 'Yammer.User');

/**
 * UserTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models
 */
class UserTestCase extends CakeTestCase
{
  /**
   * User Model object
   *
   * @var User
   * @access public
   */
  public $User = null;

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
    $this->User = ClassRegistry::init('User');
    $this->User->setConfig($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->User);
    ClassRegistry::flush();
  }

  public function testFindAll()
  {
    $result = $this->User->find('all');
    $this->assertTrue(count($result));
  }

  public function testFindCurrent()
  {
    $result = $this->User->find('current');
    $this->assertTrue(isset($result->id));
    $user_id = $result->id;

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->User->find('by_id', $params);
    $this->assertTrue(isset($result->id));
    $this->assertEqual($user_id, $result->id);
  }

  public function testFindByEmail()
  {
    $result = $this->User->find('current');
    $email = sprintf("%s@%s", $result->name, $result->network_name);
    $user_id = $result->id;

    $params = array(
      'email' => $email,
    );
    $result = $this->User->find('by_email', $params);

    $found = false;
    foreach ($result as $user) {
      if ($user->id == $user_id) {
        $found = true;
        break;
      }
    }

    $this->assertTrue($found);
  }
}