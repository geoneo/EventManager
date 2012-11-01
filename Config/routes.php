<?php
CroogoRouter::connect('/events', array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'index'));
CroogoRouter::connect(
	'/events/:slug',
	array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'view'),
	array('slug' => '(?i)[0-9a-z_-]+', 'pass' => array('slug'))
);
