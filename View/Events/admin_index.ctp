<?php $this->extend('/Common/admin_index');?>
<?php $this->start('tabs'); ?>
	<li><?php echo $this->Html->link(__d('event_manager', 'Add event'), array('action' => 'add')); ?></li>
<?php $this->end(); ?>

<table cellpadding="0" cellspacing="0">
<tbody>
<?php
	$tableHeaders = $this->Html->tableHeaders(array(
		$this->Paginator->sort('id'),
		$this->Paginator->sort('title', __('Title')),
		$this->Paginator->sort('body', __('Description')),
		$this->Paginator->sort('date', __('Date')),
		$this->Paginator->sort('status', __('Status')),
		__('Actions'),
	));
	echo $tableHeaders;
	$rows = array();
	foreach ($events as $event) {
		$actions = $this->Html->link(__('Edit'), array('controller' => 'events', 'action' => 'edit', $event['Event']['id'])) . '<br/>';
		$actions .= ' ' . $this->Form->postLink(__('Delete'), array('controller' => 'events', 'action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete this album?'));
		$rows[] = array(
			$event['Event']['id'],
			$event['Event']['title'],
			$this->Text->truncate(strip_tags($event['Event']['body']), 80),
			$event['Event']['date'],
			$this->Layout->status($event['Event']['status']),
			$actions,
		);
	}
	echo $this->Html->tableCells($rows);
	echo $tableHeaders;
?>
</tbody>
</table>
