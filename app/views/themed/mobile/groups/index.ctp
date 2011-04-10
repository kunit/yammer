<div class="category">Groups</div>
<br />

<?php foreach ($groups as $group): ?>
<div class="group">
<img src="<?php echo $this->Yammer->img_url('group', $group->id, $group->mugshot_url); ?>" class="icon" align="left" alt="" />
<?php echo $this->Html->link(h($group->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'in_group', $group->id)); ?><br />
<div class="action clear">
<?php if ($group->is_joined) :?>
<?php echo $this->Html->link('Leave', array('controller' => 'groups', 'action' => 'leave', 'session' => session_id(), $group->id)); ?>
<?php else: ?>
<?php echo $this->Html->link('Join', array('controller' => 'groups', 'action' => 'join', 'session' => session_id(), $group->id)); ?>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>

<div class="type-select">
&#xE6E5;<?php echo $this->Html->link('Messages', array('controller' => 'messages', 'action' => 'index', 'session' => session_id()), array('accesskey' => 4))?>
 | &#xE6E7;<?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index', 'session' => session_id()), array('accesskey' => 6))?>
</div>

<div class="gnavi">
&#xE6EA;<?php echo $this->Html->link('Logout', array('controller' => 'messages', 'action' => 'logout', 'session' => session_id()), array('accesskey' => 9)); ?>
</div>