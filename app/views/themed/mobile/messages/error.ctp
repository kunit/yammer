<div class="category">Error</div>

<div class="access-error">
Yammerへのアクセスが正常に終了しませんでした。再度アクセスしてください。
</div>
<br />

<div class="type-select">
<?php echo $this->Html->link('Messages', array('controller' => 'messages', 'action' => 'index', 'session' => session_id()))?>
 | <?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index', 'session' => session_id()))?>
 | <?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'session' => session_id()))?>
</div>
<br />

<div class="gnavi">
<?php echo $this->Html->link('Logout', array('controller' => 'messages', 'action' => 'logout', 'session' => session_id())); ?>
</div>