<?php 
$this->Helpers->load('Time');
$this->Helpers->load('Paginator');

$this->Html->css('/Guestbook/css/template',null,array('inline' => false));
$this->Html->css('/Guestbook/css/design',null,array('inline' => false));
?>

<?php echo $this->element('adminMenu', array() , array('plugin' => 'Guestbook'));?>
<?php echo $this->Session->flash('Guestbook.Admin');?>

<div id='guestbook_release'>

<?php echo $this->Form->create('maintenance', array('url' => array('plugin' => 'Guestbook', 'controller' => 'Guestbook','action' => 'maintenance')));?>

<table>
	<tr>
	<th></th>
	<th><?php echo __('Author')?></th>
	<th><?php echo __('Title')?></th>
	<th><?php echo __('Text')?></th>
	<th><?php echo __('Date')?></th>
	</tr>
	<?php foreach($unreleasedPosts as $GuestbookPost):?>
	<tr>
	<td class='check'><?php echo $this->Form->input('GuestbookPost.' . $GuestbookPost['GuestbookPost']['id'] . '.ckecked', array('type' => 'checkbox', 'label' => false));?></td>
	<td class='author'><?php echo $GuestbookPost['GuestbookPost']['author'];?></td>
	<td class='title'><?php echo $GuestbookPost['GuestbookPost']['title'];?></td>
	<td class='text'><?php echo $GuestbookPost['GuestbookPost']['text'];?></td>
	<td class='date'><?php echo $this->Time->format('d.m.Y', $GuestbookPost['GuestbookPost']['created']) . ' ' . $this->Time->format('H:i:s',$GuestbookPost['GuestbookPost']['created'])?></td>
	</tr>
	<?php endforeach;?>
</table>	
<?php
echo $this->Form->submit('Release posts', array('name' => 'release'));
echo $this->Form->submit('Delete posts', array('name' => 'delete'));
echo $this->Form->button('Clear selection', array('type' => 'reset'));
echo $this->Form->end();
?>
</div>