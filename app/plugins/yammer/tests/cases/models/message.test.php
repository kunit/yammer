<?php
/**
 * Import Required libraries
 */
App::import('Model', 'Yammer.Message');

/**
 * MessageTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models
 */
class MessageTestCase extends CakeTestCase
{
  /**
   * Message Model object
   *
   * @var Message
   * @access public
   */
  public $Message = null;

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
    $this->Message = ClassRegistry::init('Message');
    $this->Message->setConfig($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->Message);
    ClassRegistry::flush();
  }

  public function testFindAll()
  {
    $result = $this->Message->find('all');
    $this->assertTrue(count($result->messages) > 0);
    $count = count($result->messages);

    $ids = array();
    foreach ($result->messages as $message) {
      $ids[] = $message->id;
    }

    $params = array(
      'newer_than' => $ids[count($ids) - 1],
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue(1, count($result->messages));

    $params = array(
      'newer_than' => $ids[0],
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue($count, count($result->messages));

    $params = array(
      'newer_than' => 'test'
    );
    $result = $this->Message->find('all', $params);
    $this->assertFalse($result);

    $params = array(
      'older_than' => $ids[count($ids) - 1],
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue($count, count($result->messages));

    $params = array(
      'older_than' => $ids[0],
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue(1, count($result->messages));

    $params = array(
      'older_than' => 'test'
    );
    $result = $this->Message->find('all', $params);
    $this->assertFalse($result);

    $params = array(
      'threaded' => 'true',
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue($count, count($result->messages));

    $params = array(
      'threaded' => 'extended',
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue($count, count($result->messages));

    $params = array(
      'threaded' => 'test',
    );
    $result = $this->Message->find('all', $params);
    $this->assertFalse($result);

    $params = array(
      'limit' => 1,
    );
    $result = $this->Message->find('all', $params);
    $this->assertTrue(1, count($result->messages));

    $params = array(
      'limit' => 'test',
    );
    $result = $this->Message->find('all', $params);
    $this->assertFalse($result);
  }

  public function testFindSent()
  {
    $result = $this->Message->find('all');
    $all_count1 = count($result->messages);

    $result = $this->Message->find('sent');
    $sent_count1 = count($result->messages);

    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);

    $result = $this->Message->find('all');
    $all_count2 = count($result->messages);
    $this->assertEqual(1, $all_count2 - $all_count1);

    $result = $this->Message->find('sent');
    $sent_count2 = count($result->messages);
    $this->assertEqual(1, $sent_count2 - $sent_count1);

    $this->deleteTestMessage();
  }

  public function testFindReceived()
  {
    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $user_name = null;
    foreach ($result->references as $obj) {
      if ($obj->type == 'user') {
        $user_name = $obj->name;
      }
    }

    $result = $this->Message->find('received');
    $count1 = count($result->messages);

    $params = array(
      'body' => "@{$user_name} Test1! Test1! Test1!",
    );
    $result = $this->Message->post($params);

    $result = $this->Message->find('received');
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessage();
  }

  public function testFindPrivate()
  {
    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $user_id = null;
    foreach ($result->references as $obj) {
      if ($obj->type == 'user') {
        $user_id = $obj->id;
      }
    }

    $result = $this->Message->find('private');
    $count1 = count($result->messages);

    $params = array(
      'body' => "Test1! Test1! Test1!",
      'direct_to_id' => $user_id,
    );
    $result = $this->Message->post($params);

    $result = $this->Message->find('private');
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessage();
  }

  public function testFindFollowing()
  {
    $result = $this->Message->find('following');
    $count1 = count($result->messages);

    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);

    $result = $this->Message->find('following');
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessage();
  }

  public function testFindFromUser()
  {
    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $user_id = null;
    foreach ($result->references as $obj) {
      if ($obj->type == 'user') {
        $user_id = $obj->id;
      }
    }

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Message->find('from_user', $params);
    $count1 = count($result->messages);

    $params = array(
      'body' => "Test1! Test1! Test1!",
    );
    $result = $this->Message->post($params);

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Message->find('from_user', $params);
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessage();
  }

  public function testFindAboutTopic()
  {
    $params = array(
      'body' => '#test_topic Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $message_id = $result->messages[0]->id;
    $topic_id = null;
    foreach ($result->references as $obj) {
      if ($obj->type == 'topic') {
        $topic_id = $obj->id;
      }
    }

    $params = array(
      'topic_id' => $topic_id,
    );
    $result = $this->Message->find('about_topic', $params);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessage();
  }

  public function testFindInGroup()
  {
    $result = $this->Message->yammer->get_groups();
    $group_id = $result[0]->id;

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Message->find('in_group', $params);
    $count1 = count($result->messages);

    $params = array(
      'body' => "Test1! Test1! Test1!",
      'group_id' => $group_id,
    );
    $result = $this->Message->post($params);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Message->find('in_group', $params);
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessage();
  }

  public function testFindLikedBy()
  {
    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $message_id = $result->messages[0]->id;
    $user_id = null;
    foreach ($result->references as $obj) {
      if ($obj->type == 'user') {
        $user_id = $obj->id;
      }
    }

    $result = $this->Message->find('liked_by');
    $count1 = count($result->messages);

    $params = array(
      'message_id' => $message_id,
    );
    $result = $this->Message->like($params);

    $result = $this->Message->find('liked_by');
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $params = array(
      'message_id' => $message_id,
    );
    $result = $this->Message->unlike($params);

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Message->find('liked_by', $params);
    $count3 = count($result->messages);
    $this->assertEqual(1, $count2 - $count3);
    $this->assertEqual(0, $count1 - $count3);

    $this->deleteTestMessage();
  }

  public function testFindInThread()
  {
    $params = array(
      'body' => 'Test1! Test1! Test1!',
    );
    $result = $this->Message->post($params);
    $message_id = $result->messages[0]->id;
    $thread_id = $result->messages[0]->thread_id;

    $params = array(
      'body' => 'Test2! Test2! Test2!',
      'replied_to_id' => $message_id,
    );
    $result = $this->Message->post($params);

    $params = array(
      'body' => 'Test3! Test3! Test3!',
      'replied_to_id' => $message_id,
    );
    $result = $this->Message->post($params);

    $params = array(
      'thread_id' => $thread_id,
    );
    $result = $this->Message->find('in_thread', $params);
    $count = count($result->messages);
    $this->assertEqual(3, $count);

    $this->deleteTestMessage();
  }

  protected function deleteTestMessage()
  {
    $result = $this->Message->find('all');
    foreach ($result->messages as $message) {
      if (preg_match('|Test\d+!|', $message->body->plain)) {
        $params = array(
          'message_id' => $message->id,
        );
        $this->Message->delete($params);
      }
    }
  }
}
