<div class="category">Yammer認証(1/3)</div>
<br />

<?php if ($this->Session->read('Message.flash')): ?>
<div class="auth-error">
<?php echo $this->Session->flash(); ?>
</div>
<br />
<?php endif; ?>

<div class="information">
YammerのPCサイトで認証を行います。入力されたメールアドレスにYammerで認証するためのURLを送信します。
また、ここで入力されたメールアドレスとパスワードは今後この携帯サイトのログインで使用します。
</div>
<br />

<div class="auth-form">
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'top', 'action' => 'oauth', 'session' => session_id()), 'encoding' => null)); ?>
<dl>
<dt>メールアドレス</dt>
<dd><?php echo $this->Form->input('Yammer.email', $this->Mobile->addInputMode('alphabet', array('label' => false))); ?></dd>
<dt>パスワード</dt>
<dd><?php echo $this->Form->input('Yammer.password', $this->Mobile->addInputMode('alphabet', array('type' => 'password', 'label' => false))); ?></dd>
</dl>
<?php echo $this->Form->submit('認証'); ?>
<?php echo $this->Form->end(); ?>
</div>
<br />

<div class="gnavi">
&#xE6E2;<?php echo $this->Html->link('Top', array('action' => 'index', 'session' => session_id()), array('accesskey' => 1)); ?>
</div>