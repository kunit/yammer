<div class="category">Reply</div>

<?php if ($this->Session->read('Message.flash')): ?>
<div class="message-error">
<?php echo $this->Session->flash(); ?>
</div>
<?php endif; ?>

<div class="message-odd">
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
<?php if ($message->bookmarked_by->is_bookmarked): ?>
<?php echo $this->Html->link('Unbookmark', array('controller' => 'messages', 'action' => 'unbookmark', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
<?php else: ?>
<?php echo $this->Html->link('Bookmark', array('controller' => 'messages', 'action' => 'bookmark', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
<?php endif; ?>
<?php if ($message->is_delete): ?>
 | <?php echo $this->Html->link('Del', array('controller' => 'messages', 'action' => 'delete', 'session' => session_id(), $type, $type_id, $message->id, $message_id))?>
<?php endif; ?>
</div>
</div>

<div class="message-form">
<?php echo $this->Form->create('Yammer', array('url' => array('controller' => 'messages', 'action' => 'reply', 'session' => session_id(), $type, $type_id, $message->id), 'encoding' => null)); ?>
<?php echo $this->Form->input('Yammer.body', array('label' => false, 'div' => false)); ?>
 <?php echo $this->Form->submit('reply', array('div' => false)); ?>
<?php echo $this->Form->end(); ?>
</div>

<div class="gnavi">
&#xE6E2;<?php echo $this->Html->link('Back', array('action' => 'index', 'session' => session_id(), $type, $type_id, $message_id), array('accesskey' => 1)); ?><br />
</div>
