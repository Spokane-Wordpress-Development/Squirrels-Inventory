<?php

namespace SquirrelsInventory;

class Controller {

	public function init()
	{
		if ( !session_id() )
		{
			session_start();
		}
	}

	public function createPostTypes()
	{
		$this
			->createPostType('Make')
			->createPostType('Model');
	}

	private function createPostType( $title )
	{
		$labels = array (
			'name' => __( $title . 's' ),
			'singular_name' => __( $title ),
			'add_new_item' => __( 'Add New ' . $title ),
			'edit_item' => __( 'Edit ' . $title ),
			'new_item' => __( 'New ' . $title ),
			'view_item' => __( 'View ' . $title ),
			'search_items' => __( 'Search ' . $title . 's' ),
			'not_found' => __( 'No ' . strtolower( $title ) . 's found.' )
		);

		$args = array (
			'labels' => $labels,
			'hierarchical' => FALSE,
			'description' => $title . 's',
			'supports' => array('title', 'editor'),
			'public' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => FALSE,
			'show_in_nav_menus' => TRUE,
			'publicly_queryable' => TRUE,
			'exclude_from_search' => FALSE,
			'has_archive' => TRUE
		);

		register_post_type( 'squirrel_' . strtolower( $title ), $args );
		return $this;
	}

	public function addMenus()
	{
		add_menu_page('Squirrels Inventory', 'Squirrels', 'manage_options', 'squirrels_inventory', array($this, 'plugin_settings_page'), 'dashicons-list-view');
		add_submenu_page('squirrels_inventory', 'Inventory', 'Inventory', 'manage_options', 'squirrels_inventory');
		add_submenu_page('squirrels_inventory', 'Makes', 'Makes', 'manage_options', 'edit.php?post_type=squirrel_make');
		add_submenu_page('squirrels_inventory', 'Models', 'Models', 'manage_options', 'edit.php?post_type=squirrel_model');
	}
}