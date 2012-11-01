<?php
/**
 * Routes
 *
 * example_routes.php will be loaded in main app/config/routes.php file.
 */
	Croogo::hookRoutes('EventManager');

/**
 * Behavior
 *
 * This plugin's Example behavior will be attached whenever Node model is loaded.
 */
//	Croogo::hookBehavior('Node', 'Example.Example', array());

/**
 * Component
 *
 * This plugin's Example component will be loaded in ALL controllers.
 */
	//Croogo::hookComponent('*', 'Example.Example');

/**
 * Helper
 *
 * This plugin's Example helper will be loaded via NodesController.
 */
	Croogo::hookHelper('Nodes', 'EventManager.EventManager');

/**
 * Admin menu (navigation)
 */
	CroogoNav::add('extensions.children.event_manager', array(
		'title' => __('Event Manager'),
		'url' => '#',
		'children' => array(
			'menu1' => array(
				'title' => __d('event_manager', 'List event'),
				'url' => array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'index'),
			),
			'menu2' => array(
				'title' => __d('event_manager', 'Add event'),
				'url' => array('plugin' => 'event_manager', 'controller' => 'events', 'action' => 'add'),
			),
			'menu3' => array(
				'title' => __d('event_manager', 'Event settings'),
				'url' => array('plugin' => '', 'controller' => 'settings', 'action' => 'prefix', 'EventManager')
			),
		),
	));

/**
 * Admin row action
 *
 * When browsing the content list in admin panel (Content > List),
 * an extra link called 'Example' will be placed under 'Actions' column.
 */
	//Croogo::hookAdminRowAction('Nodes/admin_index', 'Example', 'plugin:example/controller:example/action:index/:id');

/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'Example' will be shown with markup generated from the plugin's admin_tab_node element.
 *
 * Useful for adding form extra form fields if necessary.
 */
	//Croogo::hookAdminTab('Nodes/admin_add', 'Example', 'example.admin_tab_node');
	//Croogo::hookAdminTab('Nodes/admin_edit', 'Example', 'example.admin_tab_node');
