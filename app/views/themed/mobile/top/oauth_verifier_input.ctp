<div class="category">Yammer認証(2/3)</div>
<br />

<div class="information">
先ほど入力されたメールアドレスにYammerの認証用URLを送信しました。送信されたURLをPCでアクセスしてアクセス許可を行い、その次の画面で表示された4桁のコードを入力してください。
</div>
<br />

<div class="auth-form">
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'top', 'action' => 'oauth_done', 'session' => session_id()), 'encoding' => null)); ?>
<dl>
<dt>コード</dt>
<dd><?php echo $this->Form->input('Yammer.verifier', $this->Mobile->addInputMode('alphabet', array('label' => false))); ?></dd>
</dl>
<?php echo $this->Form->submit('認証'); ?>
<?php echo $this->Form->end(); ?>
</div>
<br />

<div class="gnavi">
&#xE6E2;<?php echo $this->Html->link('Top', array('action' => 'index', 'session' => session_id()), array('accesskey' => 1)); ?>
</div>