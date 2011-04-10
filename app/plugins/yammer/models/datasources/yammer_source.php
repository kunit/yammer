<?php
App::import('Vendor', 'pear' . DS . 'pear_init');
App::import('Vendor', 'HTTP_OAuth_Consumer', array('file' => 'HTTP/OAuth/Consumer.php'));

class YammerSource extends DataSource
{
  public $description = 'Yammer API';

  protected $oauth = null;
  protected $last_response = null;

  public function __construct($config = array())
  {
    parent::__construct($config);
    $this->config = $config;
    $keys = array(
      'oauth_key',
      'oauth_secret',
      'auth_url',
      'callback',
    	'verifier',
      'request_token',
      'request_token_secret',
      'access_token',
      'access_token_secret',
    );
    foreach ($keys as $key) {
      if (!isset($this->config[$key])) {
        $this->config[$key] = null;
      }
    }
  }

  public function get_messages_all($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_sent($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/sent.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_received($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/received.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_private($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/private.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_following($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/following.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_from_user($params = array())
  {
    if (!isset($params['user_id'])) {
      return null;
    }
    $user_id = $params['user_id'];
    unset($params['user_id']);
    $url = "https://www.yammer.com/api/v1/messages/from_user/{$user_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_about_topic($params = array())
  {
    if (!isset($params['topic_id'])) {
      return null;
    }
    $topic_id = $params['topic_id'];
    unset($params['topic_id']);
    $url = "https://www.yammer.com/api/v1/messages/about_topic/{$topic_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_in_group($params = array())
  {
    if (!isset($params['group_id'])) {
      return null;
    }
    $group_id = $params['group_id'];
    unset($params['group_id']);
    $url = "https://www.yammer.com/api/v1/messages/in_group/{$group_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_liked_by($params = array())
  {
    if (isset($params['user_id'])) {
      $user_id = $params['user_id'];
    } else {
      $user_id = 'current';
    }
    unset($params['user_id']);
    $url = "https://www.yammer.com/api/v1/messages/liked_by/{$user_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_bookmarked_by($params = array())
  {
    if (isset($params['user_id'])) {
      $user_id = $params['user_id'];
    } else {
      $user_id = 'current';
    }
    unset($params['user_id']);
    $url = "https://www.yammer.com/api/v1/messages/bookmarked_by/{$user_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_messages_in_thread($params = array())
  {
    if (!isset($params['thread_id'])) {
      return null;
    }
    $thread_id = $params['thread_id'];
    unset($params['thread_id']);
    $url = "https://www.yammer.com/api/v1/messages/in_thread/{$thread_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function post_message($params)
  {
    $url = 'https://www.yammer.com/api/v1/messages.json';
    return $this->sendRequest($url, $params, 'POST');
  }

  public function delete_message($params)
  {
    if (!isset($params['message_id'])) {
      return null;
    }
    $message_id = $params['message_id'];
    unset($params['message_id']);
    $url = "https://www.yammer.com/api/v1/messages/{$message_id}";
    return $this->sendRequest($url, $params, 'DELETE');
  }

  public function post_messages_liked_by($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/liked_by/current.json';
    return $this->sendRequest($url, $params, 'POST');
  }

  public function delete_messages_liked_by($params = array())
  {
    $params['_method'] = 'DELETE';
    $url = "https://www.yammer.com/api/v1/messages/liked_by/current.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function post_messages_bookmarked_by($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/messages/bookmarked_by/current.json';
    return $this->sendRequest($url, $params, 'POST');
  }

  public function delete_messages_bookmarked_by($params = array())
  {
    $params['_method'] = 'DELETE';
    $url = "https://www.yammer.com/api/v1/messages/bookmarked_by/current.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function get_users($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/users.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_user($params = array())
  {
    if (isset($params['user_id'])) {
      $user_id = $params['user_id'];
    } else {
      $user_id = 'current';
    }
    unset($params['user_id']);
    $url = "https://www.yammer.com/api/v1/users/{$user_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_users_by_email($params = array())
  {
    $url = "https://www.yammer.com/api/v1/users/by_email.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_groups($params = array())
  {
    $url = 'https://www.yammer.com/api/v1/groups.json';
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_group($params = array())
  {
    if (!isset($params['group_id'])) {
      return null;
    }
    $group_id = $params['group_id'];
    unset($params['group_id']);
    $url = "https://www.yammer.com/api/v1/groups/{$group_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function post_group_memberships($params = array())
  {
    $url = "https://www.yammer.com/api/v1/group_memberships.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function delete_group_memberships($params = array())
  {
    $user_id = null;
    if (isset($params['user_id'])) {
      $user_id = $params['user_id'];
    }
    unset($params['user_id']);
    if (is_null($user_id)) {
      $result = $this->get_user();
      $user_id = $result->id;
    }
    $params['_method'] = "DELETE";
    $url = "https://www.yammer.com/api/v1/group_memberships/{$user_id}.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function get_topic($params = array())
  {
    if (isset($params['topic_id'])) {
      $topic_id = $params['topic_id'];
    }
    unset($params['topic_id']);
    $url = "https://www.yammer.com/api/v1/topics/{$topic_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_thread($params = array())
  {
    if (isset($params['thread_id'])) {
      $thread_id = $params['thread_id'];
    }
    unset($params['thread_id']);
    $url = "https://www.yammer.com/api/v1/threads/{$thread_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_subscriptions_to_user($params = array())
  {
    if (isset($params['user_id'])) {
      $user_id = $params['user_id'];
    }
    unset($params['user_id']);
    $url = "https://www.yammer.com/api/v1/subscriptions/to_user/{$user_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_subscriptions_to_thread($params = array())
  {
    if (isset($params['thread_id'])) {
      $thread_id = $params['thread_id'];
    }
    unset($params['thread_id']);
    $url = "https://www.yammer.com/api/v1/subscriptions/to_thread/{$thread_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function get_subscriptions_to_topic($params = array())
  {
    if (isset($params['topic_id'])) {
      $thread_id = $params['topic_id'];
    }
    unset($params['topic_id']);
    $url = "https://www.yammer.com/api/v1/subscriptions/to_topic/{$topic_id}.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function post_subscriptions($params = array())
  {
    $url = "https://www.yammer.com/api/v1/subscriptions.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function delete_subscriptions($params = array())
  {
    $params['_method'] = 'DELETE';
    $url = "https://www.yammer.com/api/v1/subscriptions.json";
    return $this->sendRequest($url, $params, 'POST');
  }

  public function get_search($params = array())
  {
    $url = "https://www.yammer.com/api/v1/search.json";
    return $this->sendRequest($url, $params, 'GET');
  }

  public function sendRequest($uri, array $params = array(), $method = 'POST')
  {
    $this->init();
    $result = $this->oauth->sendRequest($uri, $params, $method);
    $this->last_response = $result;
    return json_decode($result->getBody());
  }

  public function getLastResponse()
  {
    return $this->last_response->getResponse();
  }

  public function getLastResponseCode()
  {
    return $this->last_response->getResponse()->getStatus();
  }

  public function setOAuthCredentials($key, $secret)
  {
    $this->config['oauth_key'] = $key;
    $this->config['oauth_secret'] = $secret;
  }

  public function getRequestToken()
  {
    return array(
      'request_token' => $this->config['request_token'],
      'request_token_secret' => $this->config['request_token_secret'],
    );
  }

  public function setRequestToken($request_token ,$request_token_secret)
  {
    $this->config['request_token'] = $request_token;
    $this->config['request_token_secret'] = $request_token_secret;
  }

  public function getAuthUrl()
  {
    return $this->config['auth_url'];
  }

  public function getVerifier()
  {
    return $this->config['verifier'];
  }

  public function setVerifier($verifier)
  {
    $this->config['verifier'] = $verifier;
  }

  public function getAccessToken()
  {
    if (is_null($this->config['access_token'])) {
      if (is_null($this->oauth)) {
        $this->createComsumer();
      }

      $this->oauth->setToken($this->config['request_token']);
      $this->oauth->setTokenSecret($this->config['request_token_secret']);
      $this->oauth->getAccessToken('https://www.yammer.com/oauth/access_token', $this->config['verifier']);
      $this->config['access_token'] = $this->oauth->getToken();
      $this->config['access_token_secret'] = $this->oauth->getTokenSecret();
    }

    return array(
      'access_token' => $this->config['access_token'],
      'access_token_secret' => $this->config['access_token_secret'],
    );
  }

  public function setAccessToken($access_token, $access_token_secret)
  {
    $this->config['access_token'] = $access_token;
    $this->config['access_token_secret'] = $access_token_secret;

    if (is_null($this->oauth)) {
      $this->createComsumer();
    }

    $this->oauth->setToken($this->config['access_token']);
    $this->oauth->setTokenSecret($this->config['access_token_secret']);
  }

  protected function init()
  {
    if (is_null($this->config['request_token'])) {
      $this->authorize();
      throw new NoRequestTokenException();
    }

    $token = $this->getAccessToken();
    $this->setAccessToken($token['access_token'], $token['access_token_secret']);
  }

  protected function createComsumer()
  {
    $this->oauth = new HTTP_OAuth_Consumer($this->config['oauth_key'], $this->config['oauth_secret']);
    $this->oauth->setSignatureMethod('PLAINTEXT');

    $req = new HTTP_Request2();
    $req->setConfig('ssl_verify_peer', false);
    $creq = new HTTP_OAuth_Consumer_Request;
    $creq->accept($req);
    $this->oauth->accept($creq);
  }

  public function authorize()
  {
    if (is_null($this->oauth)) {
      $this->createComsumer();
    }
    $this->oauth->getRequestToken('https://www.yammer.com/oauth/request_token', $this->config['callback']);
    $this->config['request_token'] = $this->oauth->getToken();
    $this->config['request_token_secret'] = $this->oauth->getTokenSecret();
    $this->config['auth_url'] = $this->oauth->getAuthorizeUrl('https://www.yammer.com/oauth/authorize');
  }
}

class NoRequestTokenException extends Exception {}