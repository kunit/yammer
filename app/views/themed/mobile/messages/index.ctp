<div class="category">
<?php echo $type_name; ?>
<?php if ($type == 'about_topic'): ?> - <?php echo h($topic->name); ?><?php endif; ?>
</div>

<?php if ($this->Session->read('Message.flash')): ?>
<div class="message-error">
<?php echo $this->Session->flash(); ?>
</div>
<?php endif; ?>

<?php if ($type == 'in_group'): ?>
<div class="group">
<img src="<?php echo $this->Yammer->img_url('group', $group->id, $group->mugshot_url); ?>" class="icon" align="left" alt="" />
 <?php echo h($group->full_name); ?>
<br class="clear" />
<div class="action">
<?php if ($group->is_joined) :?>
<?php echo $this->Html->link('Leave', array('controller' => 'groups', 'action' => 'leave', 'session' => session_id(), $group->id, $type, $type_id)); ?>
<?php else: ?>
<?php echo $this->Html->link('Join', array('controller' => 'groups', 'action' => 'join', 'session' => session_id(), $group->id, $type, $type_id)); ?>
<?php endif; ?>
</div>
</div>
<?php endif; ?>

<?php if ($type == 'from_user'): ?>
<div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td><img src="<?php echo $this->Yammer->img_url('user', $from_user->id, $from_user->mugshot_url); ?>" class="icon" align="left" alt="" /></td>
<td valign="bottom">
<span class="sender">
<?php echo h($from_user->full_name); ?> (<?php echo h($from_user->name); ?>)
</span>
</td>
</tr>
</table>
<div class="user-stats">
updates: <?php echo $from_user->stats->updates; ?><br />
followers: <?php echo $from_user->stats->followers; ?><br />
following: <?php echo $from_user->stats->following; ?><br />
</div>
</div>

<?php if (!$from_user->self): ?>
<div class="action">
<?php if ($from_user->is_following): ?>
<?php echo $this->Html->link('Unfollow', array('controller' => 'users', 'action' => 'unfollow', 'session' => session_id(), $from_user->id, $type, $type_id)); ?>
<?php else: ?>
<?php echo $this->Html->link('Follow', array('controller' => 'users', 'action' => 'follow', 'session' => session_id(), $from_user->id, $type, $type_id)); ?>
<?php endif; ?>
</div>
<?php endif; ?>

<div class="action">
<?php echo $this->Html->link('Send Private Message', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'private', $from_user->id))?>
</div>
<?php endif; ?>

<?php if ($type == 'private'): ?>
<div class="message-form">
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id), 'encoding' => null)); ?>
<dl>
<dt>To:</dt>
<dd><?php echo $this->Form->input('Yammer.to', $this->Mobile->addInputMode('alphabet', array('label' => false, 'div' => false))); ?></dd>
<dt>Message:</dt>
<dd><?php echo $this->Form->input('Yammer.body', array('label' => false, 'div' => false)); ?></dd>
</dl>
<?php echo $this->Form->submit('send', array('div' => false)); ?>
<?php echo $this->Form->end(); ?>
</div>
<?php elseif (!in_array($type, array('received', 'sent', 'liked_by', 'bookmarked_by'))): ?>
<div class="message-form">
<div class="message-prompt">
What are you working on?
</div>
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id), 'encoding' => null)); ?>
<?php echo $this->Form->input('Yammer.body', array('label' => false, 'div' => false)); ?>
 <?php echo $this->Form->submit('update', array('div' => false)); ?>
<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>

<div class="refresh">
&#xE6E3;<?php echo $this->Html->link('Refresh', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id), array('accesskey' => 2)); ?><br />
</div>

<?php if (count($messages) > 0): ?>
<?php $count = 0; ?>
<?php foreach ($messages as $message): ?>
<?php if (++$count % 2 == 0) { $class = "message-even"; } else { $class = "message-odd"; } ?>
<div class="<?php echo $class; ?>">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td><img src="<?php echo $this->Yammer->img_url('user', $message->sender->id, $message->sender->icon_url); ?>" class="icon" align="left" alt="" /></td>
<td valign="bottom">
<span class="sender">
<?php echo $this->Html->link(h($message->sender->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'from_user', $message->sender->id)); ?>
<?php if ($message->replied_to->id): ?> Re: <?php echo $this->Html->link(h($message->replied_to->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'from_user', $message->replied_to->sender_id)); ?><?php endif; ?>
<?php if (!$message->replied_to->id && $message->direct_to->id): ?> To <?php echo $this->Html->link(h($message->direct_to->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'from_user', $message->direct_to->id)); ?><?php endif; ?>
</span>
</td>
</tr>
</table>
<div class="message-body clear">
<?php if ($message->privacy != 'public'): ?>&#xE6D9; <?php endif; ?>
<?php if (!is_null($message->group->id)): ?>[<?php echo $this->Html->link(h($message->group->full_name), array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'in_group', $message->group->id)); ?>] <?php endif; ?>
<?php echo $this->Yammer->parse_message($messages, $message); ?>
</div>
<?php if ($message->liked_by->is_liked): ?>
<div class="like">
&#xE727; Liked by you.
</div>
<?php endif; ?>
<div class="datetime">
<?php echo $this->Time->format('n/j H:i', $message->created_at); ?>
</div>

<div class="message-navi">
<?php if ($message->liked_by->is_liked): ?>
<?php echo $this->Html->link('Unlike', array('controller' => 'messages', 'action' => 'unlike', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
<?php else: ?>
<?php echo $this->Html->link('Like', array('controller' => 'messages', 'action' => 'like', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
<?php endif; ?>
 | <?php echo $this->Html->link('Thread', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'in_thread', $message->thread->id))?>
 | <?php echo $this->Html->link('Reply', array('controller' => 'messages', 'action' => 'reply', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div>
メッセージはありません。
</div>
<br />
<?php endif; ?>

<?php if ($older_available && !is_null($last_message_id)): ?>
<div class="more">
&#xE6EB;<?php echo $this->Html->link('More', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), $type, $type_id, $last_message_id), array('accesskey' => 0))?>
</div>
<?php endif; ?>

<div class="feed-select">
<?php echo $this->Html->link('My Feed', array('controller' => 'messages', 'action' => 'index', 'session' => session_id()))?>
 | <?php echo $this->Html->link('Company Feed', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'all'))?>
 | <?php echo $this->Html->link('Mentions', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'received'))?>
 | <?php echo $this->Html->link('Sent', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'sent'))?>
 | <?php echo $this->Html->link('Liked', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'liked_by'))?>
 | <?php echo $this->Html->link('Bookmarked', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'bookmarked_by'))?>
 | <?php echo $this->Html->link('Direct Messages', array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'private'))?>
</div>

<div class="type-select">
&#xE6E5;<?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index', 'session' => session_id()), array('accesskey' => 4))?>
 | &#xE6E7;<?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'session' => session_id()), array('accesskey' => 6))?>
</div>

<?php if (count($groups) > 0): ?>
<div class="group-list">
Join Groups<br />
<?php foreach ($groups as $group): ?>
* <?php echo $this->Html->link($group->name, array('controller' => 'messages', 'action' => 'index', 'session' => session_id(), 'in_group', $group->id))?><br />
<?php endforeach;?>
</div>
<?php endif; ?>

<div class="gnavi">
&#xE6EA;<?php echo $this->Html->link('Logout', array('controller' => 'messages', 'action' => 'logout', 'session' => session_id()), array('accesskey' => 9)); ?>
</div>