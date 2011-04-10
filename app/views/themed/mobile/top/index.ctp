<div class="category">Top</div>

<?php if ($this->Session->read('Message.flash')): ?>
<div class="auth-error">
<?php echo $this->Session->flash(); ?>
</div>
<?php endif; ?>

<div class="member">
<div>
既にYammer認証を行っている人はログインを行ってください。
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'top', 'action' => 'login'), 'encoding' => null)); ?>
<dl>
<dt>メールアドレス</dt>
<dd><?php echo $this->Form->input('Yammer.email', $this->Mobile->addInputMode('alphabet', array('label' => false))); ?></dd>
<dt>パスワード</dt>
<dd><?php echo $this->Form->input('Yammer.password', $this->Mobile->addInputMode('alphabet', array('type' => 'password', 'label' => false))); ?></dd>
</dl>
<?php echo $this->Form->submit('Login'); ?>
<?php echo $this->Form->end(); ?>
</div>
</div>
<br />

<div class="non-member">
初めての人はこちらからYammer認証を行ってください。
<div class="top-navi">
<?php echo $this->Html->link('Yammer認証', array('controller' => 'top', 'action' => 'oauth', 'session' => session_id())); ?>
</div>
</div>
