<?php

namespace SquirrelsInventory;

class Controller {

	const DOMAIN = 'squirrels_inventory';

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
			'name' => __( $title . 's', self::DOMAIN ),
			'singular_name' => __( $title, self::DOMAIN ),
			'add_new_item' => __( 'Add New ' . $title, self::DOMAIN ),
			'edit_item' => __( 'Edit ' . $title, self::DOMAIN ),
			'new_item' => __( 'New ' . $title, self::DOMAIN ),
			'view_item' => __( 'View ' . $title, self::DOMAIN ),
			'search_items' => __( 'Search ' . $title . 's', self::DOMAIN ),
			'not_found' => __( 'No ' . strtolower( $title ) . 's found.', self::DOMAIN )
		);

		$args = array (
			'labels' => $labels,
			'hierarchical' => FALSE,
			'description' => $title . 's',
			'supports' => array( 'title' ),
			'show_ui' => TRUE,
			'show_in_menu' => FALSE,
			'show_in_nav_menus' => TRUE,
			'publicly_queryable' => TRUE,
			'exclude_from_search' => FALSE,
			'has_archive' => TRUE
		);

		register_post_type( 'squirrels_' . strtolower( $title ), $args );
		return $this;
	}

	public function addMenus()
	{
		add_menu_page('Squirrels Inventory', 'Squirrels', 'manage_options', 'squirrels_inventory', array( $this, 'plugin_settings_page' ), 'dashicons-list-view');
		add_submenu_page('squirrels_inventory', __( 'Inventory', self::DOMAIN ), __( 'Inventory', self::DOMAIN ), 'manage_options', 'squirrels_inventory');
		add_submenu_page('squirrels_inventory', __( 'Makes', self::DOMAIN ), __( 'Makes', self::DOMAIN ), 'manage_options', 'edit.php?post_type=squirrels_make');
		add_submenu_page('squirrels_inventory', __( 'Models', self::DOMAIN ), __( 'Models', self::DOMAIN ), 'manage_options', 'edit.php?post_type=squirrels_model');
	}

	public function customModelMeta()
	{
		add_meta_box( 'squirrels-model-meta', __( 'Additional Info', self::DOMAIN ), array( $this, 'modelMeta' ), 'squirrels_model' );
	}

	public function modelMeta()
	{
		include( dirname( __DIR__ ) ) . '/includes/model_meta.inc';
	}
}