<?php
/**
 * event_view Element
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Ivan Mattoni <iwmattoni@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     https://github.com/geoneo/EventManager.git
 * @param	   string $slug Event slug
 */
if (isset($slug)) {
	$event = $this->requestAction(
		array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'view', 'slug' => $slug)
	);
}

if (!empty($event)):?>
	<h2><?=$event['Event']['title']?></h2>
	<div class="event-date"><?=$this->EventManager->getDate('<span class="day">%d</span> <span class="month">%F</span> <span class="year">%Y</span>', $event['Event']['date'])?></div>
<?php if ($event['Event']['with_photo']):?>
	<div class="event-thumb"><?=$this->Image->resize('/uploads/events/'.$event['Event']['photo'], 500, 400, true, array(), true)?></div>
<?php endif?>
	<div class="event-body"><?=$event['Event']['body']?></div>
<?php else:?>
<?='[EventManager:view('.$slug.')]'?>
<?php endif?>
