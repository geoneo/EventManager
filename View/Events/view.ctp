<h2><?=$event['Event']['title']?></h2>
<div class="event-date"><?=$this->EventManager->getDate('<span class="day">%d</span> <span class="month">%F</span> <span class="year">%Y</span>', $event['Event']['date'])?></div>
<?php if ($event['Event']['with_photo']):?>
	<div class="event-thumb"><?=$this->Image->resize('/uploads/events/'.$event['Event']['photo'], 500, 400, true, array(), true)?></div>
<?php endif?>
<div class="event-body"><?=$event['Event']['body']?></div>
