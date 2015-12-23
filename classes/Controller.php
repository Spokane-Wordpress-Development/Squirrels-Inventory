<?php

namespace SquirrelsInventory;

class Controller {

	const DOMAIN = 'squirrels_inventory';

	public function activate()
	{
		$makes = $this->getMakesModels();
		foreach ( $makes as $make_data )
		{
			$make = new Make;
			$make
				->setTitle( $make_data['title'] )
				->create();

			foreach ( $make_data['models'] as $model_data )
			{
				$title = $model_data['title'];
				$pos = strpos( $title, 'Model' );
				if ( $pos === FALSE )
				{
					$model = new Model;
					$model
						->setTitle( $title )
						->setMakeId( $make->getId() )
						->create();
				}
			}
		}
	}

	private function getMakesModels()
	{
		$json = file_get_contents( dirname( __DIR__ ) . '/includes/makes_models.inc' );
		return json_decode( $json, TRUE );
	}

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

	/**
	 * @param $title
	 *
	 * @return $this
	 */
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

	public function saveModelMeta( $post_id, $post )
	{
		if ( $post->post_type != Model::CUSTOM_POST_TYPE )
		{
			return;
		}

		if ( isset( $_REQUEST[ 'make_id' ] ) )
		{
			$make_id = $_REQUEST[ 'make_id' ];

			if ( strlen( $make_id ) == 0 )
			{
				if ( strlen( $_REQUEST[ 'make' ] ) > 0 )
				{
					$make = new Make;
					$make
						->setTitle( $_REQUEST['make'] )
						->create();

					$make_id = $make->getId();
				}
			}

			if ( strlen( $make_id ) > 0 )
			{
				update_post_meta( $post_id, 'make_id', $make_id);
			}
		}
	}

	public function changeDefaultPlaceholders( $title )
	{
		$screen = get_current_screen();
		switch ( $screen->post_type )
		{
			case Make::CUSTOM_POST_TYPE:
				$title = 'Ex: Ford';
				break;
			case Model::CUSTOM_POST_TYPE:
				$title = 'Ex: Mustang';
				break;
		}

		return $title;
	}

	public function addMakeColumnToModelList( $columns )
	{
		$new = array(
			'make_id' => 'Make'
		);
		$columns = array_slice( $columns, 0, 1, TRUE ) + $new + array_slice( $columns, 1, NULL, TRUE );
		$columns['title'] = 'Model';
		return $columns;
	}

	/**
	 * Make::getAllMakes updates the global $post variable,
	 * which is why I'm assigning it to a temp variable below
	 *
	 * @param $column
	 */
	public function customModelColumns( $column )
	{
		$post = $GLOBALS['post'];
		$makes = Make::getAllMakes();
		$GLOBALS['post'] = $post;

		if ($column == 'make_id')
		{
			$make_id = get_post_meta( $post->ID, 'make_id', TRUE);
			if ( array_key_exists( $make_id, $makes ) )
			{
				echo $makes[ $make_id ]->getTitle();
			}
		}
	}
}