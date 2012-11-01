<?php
/**
 * event_index Element
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Ivan Mattoni <iwmattoni@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     https://github.com/geoneo/EventManager.git
 */
$events = $this->requestAction(
	array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'index')
);
?>
<ul id="eventList">
<?php foreach ($events as $event):
		$link = array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'view', 'slug' => $event['Event']['slug']);
?>
	<li>
		<h2 class="event-title"><?=$this->Html->link($event['Event']['title'], $link)?></h2>
		<div class="event-date"><?=$this->EventManager->getDate('<span class="event-day">%d</span><span class="event-month">%M</span>', $event['Event']['date'])?></div>
		<div class="event-body"><?=$this->Text->truncate(strip_tags($event['Event']['body']), 90)?></div>
	</li>
<?php endforeach?>
</ul>
