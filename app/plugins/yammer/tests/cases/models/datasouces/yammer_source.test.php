<?php
/**
 * Import Required libraries
 */
App::import('Datasource', 'Yammer.YammerSource');

/**
 * YammerTestCase
 *
 * @package       yammer
 * @subpackage    yammer.tests.cases.models.datasources
 */
class YammerTestCase extends CakeTestCase
{
  /**
   * Yammer Datasource object
   *
   * @var YammerSource
   * @access public
   */
  public $Yammer = null;

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
    $this->Yammer = new YammerSource($this->config);
  }

  /**
   * End Test
   *
   * @return void
   * @access public
   */
  public function endTest()
  {
    unset($this->Yammer);
    sleep(10);
  }

  public function testPostAndDeleteMessage()
  {
    $result = $this->Yammer->get_messages_all();
    $count1 = count($result->messages);

    $body = 'Test1! Test1! Test1!';
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $result = $this->Yammer->get_messages_all();
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $this->assertTrue($body, $message->body->plain);
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $params = array(
      'message_id' => $message_id,
    );
    $result = $this->Yammer->delete_message($params);

    $result = $this->Yammer->get_messages_all();
    $count3 = count($result->messages);
    $this->assertEqual(1, $count2 - $count3);
    $this->assertEqual(0, $count1 - $count3);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertFalse($found);

    $this->deleteTestMessages();
  }

  public function testGetSentMessage()
  {
    $result = $this->Yammer->get_messages_sent();
    $count1 = count($result->messages);

    $body = 'Test1! Test1! Test1!';
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $result = $this->Yammer->get_messages_sent();
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessages();
  }

  public function testGetReceivedMessage()
  {
    $result = $this->Yammer->get_user();
    $user_name = $result->name;

    $result = $this->Yammer->get_messages_received();
    $count1 = count($result->messages);

    $body = "@{$user_name} Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $result = $this->Yammer->get_messages_received();
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessages();
  }

  public function testGetPrivateMessage()
  {
    $result = $this->Yammer->get_user();
    $user_id = $result->id;

    $body = "Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
      'direct_to_id' => $user_id,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $result = $this->Yammer->get_messages_private();

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);
  }

  public function testGetFollowingMessage()
  {
    $result = $this->Yammer->get_messages_following();
    $count1 = count($result->messages);

    $body = "Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $result = $this->Yammer->get_messages_following();
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessages();
  }

  public function testGetMessagesFromUser()
  {
    $result = $this->Yammer->get_user();
    $user_id = $result->id;

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Yammer->get_messages_from_user($params);
    $count1 = count($result->messages);

    $body = "Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Yammer->get_messages_from_user($params);
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessages();
  }

  public function testGetMessagesAboutTopic()
  {
    $body = "Test1! Test1! Test1! #test_topic";
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;
    $topic_id = null;
    foreach ($result->references as $obj) {
     if ($obj->type == 'topic') {
       $topic_id = $obj->id;
       break;
     }
    }
    $this->assertFalse(is_null($topic_id));

    $params = array(
      'topic_id' => $topic_id,
    );
    $result = $this->Yammer->get_messages_about_topic($params);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $params = array(
      'topic_id' => $topic_id,
    );
    $result = $this->Yammer->get_topic($params);
    $this->assertEqual($topic_id, $result->id);

    $this->deleteTestMessages();
  }

  public function testGetMessagesInGroup()
  {
    $result = $this->Yammer->get_groups();
    $group_id = $result[0]->id;

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->get_messages_in_group($params);
    $count1 = count($result->messages);

    $body = "Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
      'group_id' => $group_id,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->get_messages_in_group($params);
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $this->deleteTestMessages();
  }

  public function testGetMessagesLikedBy()
  {
    $result = $this->Yammer->get_user();
    $user_id = $result->id;

    $result = $this->Yammer->get_messages_liked_by();
    $count1 = count($result->messages);

    $body = "Test1! Test1! Test1!";
    $params = array(
      'body' => $body,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $message_id = $result->messages[0]->id;

    $params = array(
      'message_id' => $message_id,
    );
    $result = $this->Yammer->post_messages_liked_by($params);

    $result = $this->Yammer->get_messages_liked_by();
    $count2 = count($result->messages);
    $this->assertEqual(1, $count2 - $count1);

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Yammer->get_messages_liked_by($params);
    $count3 = count($result->messages);
    $this->assertEqual(0, $count3 - $count2);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertTrue($found);

    $params = array(
      'message_id' => $message_id,
    );
    $result = $this->Yammer->delete_messages_liked_by($params);

    $result = $this->Yammer->get_messages_liked_by();
    $count4 = count($result->messages);
    $this->assertEqual(1, $count2 - $count4);
    $this->assertEqual(0, $count1 - $count4);

    $found = false;
    foreach ($result->messages as $message) {
      if ($message->id == $message_id) {
        $found = true;
        break;
      }
    }
    $this->assertFalse($found);

    $this->deleteTestMessages();
  }

  public function testThreadMessage()
  {
    $body1 = 'Test1! Test1! Test1!';
    $params = array(
      'body' => $body1,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $id1 = $result->messages[0]->id;
    $thread_id = $result->messages[0]->thread_id;

    $body2 = 'Test2! Test2! Test2!';
    $params = array(
      'body' => $body2,
      'replied_to_id' => $id1,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $id2 = $result->messages[0]->id;

    $body3 = 'Test3! Test3! Test3!';
    $params = array(
      'body' => $body3,
      'replied_to_id' => $id2,
    );
    $result = $this->Yammer->post_message($params);
    $this->assertTrue(isset($result->messages));
    $id3 = $result->messages[0]->id;

    $params = array(
      'thread_id' => $thread_id,
    );
    $result = $this->Yammer->get_messages_in_thread($params);
    $this->assertEqual(3, count($result->messages));

    $params = array(
      'thread_id' => $thread_id,
    );
    $result = $this->Yammer->get_thread($params);
    $this->assertEqual(3, $result->stats->updates);

    $this->deleteTestMessages();
  }

  public function testGetUsers()
  {
    $result = $this->Yammer->get_user();
    $user_id = $result->id;

    $result = $this->Yammer->get_users();
    $this->assertTrue(count($result) > 0);

    $found = false;
    foreach ($result as $user) {
      if ($user->id == $user_id) {
        $found = true;
        break;
      }
    }

    $this->assertTrue($found);
  }

  public function testGetUser()
  {
    $result = $this->Yammer->get_user();
    $this->assertTrue(isset($result->id));
    $user_id = $result->id;

    $params = array(
      'user_id' => $user_id,
    );
    $result = $this->Yammer->get_user($params);
    $this->assertTrue(isset($result->id));
    $this->assertEqual($user_id, $result->id);
  }

  public function testGetUsersByEmail()
  {
    $result = $this->Yammer->get_user();
    $email = sprintf("%s@%s", $result->name, $result->network_name);
    $user_id = $result->id;

    $params = array(
      'email' => $email,
    );
    $result = $this->Yammer->get_users_by_email($params);

    $found = false;
    foreach ($result as $user) {
      if ($user->id == $user_id) {
        $found = true;
        break;
      }
    }

    $this->assertTrue($found);
  }

  public function testGetGroups()
  {
    $result = $this->Yammer->get_groups();
    $this->assertTrue(count($result) > 0);
  }

  public function testGetGroup()
  {
    $result = $this->Yammer->get_groups();
    $this->assertTrue(count($result) > 0);
    $group_id = $result[0]->id;

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->get_group($params);
    $this->assertTrue($group_id, $result->id);
  }

  public function testGroupMemberships()
  {
    $result = $this->Yammer->get_user();
    $user_id = $result->id;

    $result = $this->Yammer->get_groups();
    $this->assertTrue(count($result) > 0);
    $group_id = $result[0]->id;
    $members = $result[0]->stats->members;

    $params = array(
      'group_id' => $group_id,
      'user_id' => $user_id,
    );
    $result = $this->Yammer->delete_group_memberships($params);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->get_group($params);
    $this->assertEqual(1 , $members - $result->stats->members);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->post_group_memberships($params);

    $params = array(
      'group_id' => $group_id,
    );
    $result = $this->Yammer->get_group($params);
    $this->assertEqual(0 , $members - $result->stats->members);
  }

  public function testSearch()
  {
    $params = array(
      'search' => 'Test1',
    );
    $result = $this->Yammer->get_search($params);
    $count1 = count($result->messages->messages);

    $body1 = 'Test1! Test1! Test1!';
    $params = array(
      'body' => $body1,
    );
    $result = $this->Yammer->post_message($params);

    $params = array(
      'search' => 'Test1',
    );
    $result = $this->Yammer->get_search($params);
    $count2 = count($result->messages->messages);
    $this->assertEqual(1, $count2 - $count1);

    $this->deleteTestMessages();
  }

  protected function deleteTestMessages()
  {
    sleep(10);
    $result = $this->Yammer->get_messages_all();
    foreach ($result->messages as $message) {
      if (preg_match('|Test\d+!|', $message->body->plain)) {
        sleep(5);
        $params = array(
          'message_id' => $message->id,
        );
        $this->Yammer->delete_message($params);
      }
    }
  }
}
