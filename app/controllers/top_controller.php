<?php
class TopController extends AppController
{
  public $components = array(
    'Block',
    'Session',
    'Mobile',
    'MobileSession',
    'Qdmail',
    'Auth',
  );
  public $uses = null;

  public $helpers = array('Mobile');

  public function beforeFilter()
  {
    session_start();

    $this->Auth->userModel = 'Yammer';
    $this->Auth->fields = array('username' => 'email', 'password' => 'password');
    $this->Auth->allow('index', 'oauth', 'oauth_done');
    $this->Auth->loginAction = array('controller' => 'top', 'action' => 'login');
    $this->Auth->loginRedirect = array('controller' => 'messages', 'action' => 'index', 'session' => session_id());
    $this->Auth->userScope = array('NOT' => array('Yammer.access_token' => null));

    parent::beforeFilter();
  }

  public function index()
  {
    $this->Session->delete('Yammer');
  }

  public function login()
  {
    $this->redirect(array('action' => 'index'));
  }

  public function oauth()
  {
    if (!empty($this->data)) {
      $this->loadModel('Yammer');
      $yammer = $this->Yammer->oauth_authorize($this->data);
      if ($yammer) {
        $this->send_mail($yammer);
        $this->Session->write('Yammer', $yammer);
        $this->render('oauth_verifier_input');
      } else {
        $this->Session->setFlash('Yammerの認証に失敗しました');
      }
    }
    $this->data['Yammer']['password'] = null;
  }

  public function oauth_done()
  {
    if (empty($this->data)) {
      $this->Session->setFlash('Yammerの認証に失敗しました');
      $this->redirect(array('action' => 'oauth', 'session' => session_id()));
    }

    $this->loadModel('Yammer');

    $yammer = $this->Session->read('Yammer');
    if (!$this->Yammer->oauth_get_access_token($yammer, $this->data)) {
      $this->Session->setFlash('Yammerの認証に失敗しました');
      $this->redirect(array('controller' => 'top', 'action' => 'oauth', 'session' => session_id()));
      return;
    }

    $this->Session->delete('Yammer');
  }

  protected function send_mail($yammer)
  {
    config('yammer');
    $from = Configure::read('Yammer.oauth_mail_from');
    $subject = Configure::read('Yammer.oauth_mail_subject');
    $email = Set::extract($yammer, 'Yammer.email');
    $auth_url = Set::extract($yammer, 'Yammer.auth_url');

    $body = <<<EOT
Mobile Yammerの認証メールです。
以下のURLにPCでアクセスして4桁のコードを入手してください。
入手したら携帯側でそのコードを入力してください。

{$auth_url}
EOT;

    $this->Qdmail->from($from);
    $this->Qdmail->to($email);
    $this->Qdmail->subject($subject);
    $this->Qdmail->text($body);
    $this->Qdmail->send();
  }
}
