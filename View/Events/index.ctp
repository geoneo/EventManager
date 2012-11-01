<h2><?php echo $title_for_layout; ?></h2>
<div id="eventList">
<?php foreach ($events as $event):
		$link = array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'view', 'slug' => $event['Event']['slug']);
?>
	<article>
		<h2 class="event-title"><?=$this->Html->link($event['Event']['title'], $link)?></h2>
		<div class="event-date"><?=$this->EventManager->getDate('<span class="event-day">%d</span><span class="event-month">%M</span>', $event['Event']['date'])?></div>
		<?php if ($event['Event']['with_photo']):?>
		<div class="event-thumb"><?=$this->Html->link($this->Image->resize('/uploads/events/'.$event['Event']['photo'], 120, 80, true, array(), true), $link, array('escape' => false))?></div>
		<?php endif?>
		<div class="event-body"><?=$this->Text->truncate(strip_tags($event['Event']['body']), 150)?></div>
		<div class="event-read-more"><?=$this->Html->link(__('Read more'), $link)?></a>
	</article>
<?php endforeach?>
</div>
