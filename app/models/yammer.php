<?php
class Yammer extends AppModel
{
  /**
   * Yammer Datasource object
   *
   * @var YammerSource
   * @access public
   */
  public $yammer = null;

  public $validate = array(
    'email' => array(
      'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => 'メールアドレスを入力してください'),
    ),
    'password' => array(
      'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => 'パスワードを入力してください'),
    ),
  );

  public function __construct($id = false, $table = null, $ds = null)
  {
    $this->yammer = ConnectionManager::getDataSource('yammer');
    parent::__construct($id, $table, $ds);
  }

  public function oauth_authorize($data)
  {
    $email = Set::extract($data, 'Yammer.email');
    $password = Set::extract($data, 'Yammer.password');

    try {
      config('yammer');
      $oauth_key = Configure::read('Yammer.oauth_key');
      $oauth_secret = Configure::read('Yammer.oauth_secret');
      $this->yammer->setOAuthCredentials($oauth_key, $oauth_secret);
      $this->yammer->authorize();

      $token = $this->yammer->getRequestToken();
      $url = $this->yammer->getAuthUrl();
      $request_token = Set::extract($token, 'request_token');
      $request_token_secret = Set::extract($token, 'request_token_secret');
    } catch (Exception $e) {
      $url = null;
      $request_token = null;
      $request_token_secret = null;
    }

    if (!$url || !$request_token || !$request_token_secret) {
      return false;
    }

    $yammer = $this->findByEmail($email);
    $yammer['Yammer']['email'] = $email;
    $yammer['Yammer']['password'] = $password;
    $yammer['Yammer']['request_token'] = $request_token;
    $yammer['Yammer']['request_token_secret'] = $request_token_secret;
    $yammer['Yammer']['auth_url'] = $url;
    $yammer['Yammer']['verifier'] = null;
    $yammer['Yammer']['access_token'] = null;
    $yammer['Yammer']['access_token_secret'] = null;
    $yammer['Yammer']['modified'] = null;

    $this->create();
    return $this->save($yammer);
  }

  public function oauth_get_access_token($yammer, $data)
  {
    $verifier = Set::extract($data, 'Yammer.verifier');
    if (empty($verifier)) {
      return false;
    }

    config('yammer');
    $oauth_key = Configure::read('Yammer.oauth_key');
    $oauth_secret = Configure::read('Yammer.oauth_secret');
    $request_token = Set::extract($yammer, 'Yammer.request_token');
    $request_token_secret = Set::extract($yammer, 'Yammer.request_token_secret');

    if (!$request_token || !$request_token_secret) {
      return false;
    }

    $this->yammer->setOAuthCredentials($oauth_key, $oauth_secret);
    $this->yammer->setVerifier($verifier);
    $this->yammer->setRequestToken($request_token, $request_token_secret);
    try {
      $token = $this->yammer->getAccessToken();
    } catch (Exception $e) {
      $token = array();
    }

    $access_token = Set::extract($token, 'access_token');
    $access_token_secret = Set::extract($token, 'access_token_secret');

    if (!$access_token || !$access_token_secret) {
      return false;
    }

    $email = Set::extract($yammer, 'Yammer.email');
    $yammer_data = $this->findByEmail($email);
    if (empty($yammer_data)) {
      return false;
    }

    $yammer_data['Yammer']['verifier'] = $verifier;
    $yammer_data['Yammer']['access_token'] = $access_token;
    $yammer_data['Yammer']['access_token_secret'] = $access_token_secret;
    $yammer_data['Yammer']['modified'] = null;

    $this->create();
    return $this->save($yammer_data);
  }
}