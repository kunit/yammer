<div class="category">Users</div>
<br />

<?php foreach ($users as $user): ?>
<div class="user">
<img src="<?php echo $this->Yammer->img_url('user', $user->id, $user->mugshot_url); ?>" class="icon" align="left" alt="" />
<?php echo $this->Html->link(h($user->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'from_user', $user->id)); ?><br />
<div class="action clear">
<?php if (!$user->self): ?>
<?php if ($user->is_following) :?>
<?php echo $this->Html->link('Unfollow', array('controller' => 'users', 'action' => 'unfollow', 'session' => session_id(), $user->id)); ?>
<?php else: ?>
<?php echo $this->Html->link('Follow', array('controller' => 'users', 'action' => 'follow', 'session' => session_id(), $user->id)); ?>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>

<div class="type-select">
&#xE6E5;<?php echo $this->Html->link('Messages', array('controller' => 'messages', 'action' => 'index', 'session' => session_id()), array('accesskey' => 4))?>
 | &#xE6E7;<?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'session' => session_id()), array('accesskey' => 6))?>
</div>

<div class="gnavi">
&#xE6EA;<?php echo $this->Html->link('Logout', array('controller' => 'messages', 'action' => 'logout', 'session' => session_id()), array('accesskey' => 9)); ?>
</div>