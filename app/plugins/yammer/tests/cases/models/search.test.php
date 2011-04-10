<?php
/**
 * Import Required libraries
 */
App::import('Model', 'Yammer.Search');

/**
 * SearchTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models
 */
class SearchTestCase extends CakeTestCase
{
  /**
   * Search Model object
   *
   * @var Search
   * @access public
   */
  public $Search = null;

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
    $this->Search = ClassRegistry::init('Search');
    $this->Search->setConfig($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->Search);
    ClassRegistry::flush();
  }

  public function testFindAll()
  {
    $params = array(
      'search' => 'Test1',
    );
    $result = $this->Search->find('all', $params);
    $count1 = count($result->messages->messages);

    $body1 = 'Test1! Test1! Test1!';
    $params = array(
      'body' => $body1,
    );
    $result = $this->Search->yammer->post_message($params);
    $message_id = $result->messages[0]->id;

    $params = array(
      'search' => 'Test1',
    );
    $result = $this->Search->find('all', $params);
    $count2 = count($result->messages->messages);
    $this->assertEqual(1, $count2 - $count1);

    $result = $this->Search->yammer->get_messages_all();
    foreach ($result->messages as $message) {
      if (preg_match('|Test\d+!|', $message->body->plain)) {
        $params = array(
          'message_id' => $message->id,
        );
        $result = $this->Search->yammer->delete_message($params);
      }
    }
  }
}