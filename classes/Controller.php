<?php

namespace SquirrelsInventory;

class Controller {

	const VERSION = '1.0';

	public function activate()
	{
		add_option( 'squirrels_inventory_version', self::VERSION );

		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		global $wpdb;

		/** Create tables */
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		/** SQUIRRELS_FEATURES table */
		$table = $wpdb->prefix . "squirrels_features";
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
			CREATE TABLE `" . $table . "`
			(
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(50) DEFAULT NULL,
				`is_system` TINYINT(4) DEFAULT NULL,
				`is_true_false` TINYINT(4) DEFAULT NULL,
				`options` TEXT DEFAULT NULL,
				`created_at` DATETIME DEFAULT NULL,
				`updated_at` DATETIME DEFAULT NULL,
				PRIMARY KEY (`id`)
			)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/** Pre-load makes and models */
		$makes = $this->getMakesModels();
		foreach ( $makes as $make_data )
		{
			$make = new Make;
			$make
				->setTitle( $make_data['title'] )
				->create();

			foreach ( $make_data['models'] as $model_data )
			{
				$model = new Model;
				$model
					->setTitle( $model_data['title'] )
					->setMakeId( $make->getId() )
					->create();
			}
		}

		/** Pre-load Auto Types */
		$auto_types = array( 'Car', 'Truck', 'SUV', 'Motorcycle', 'RV', 'Boat' );
		foreach ($auto_types as $title)
		{
			$auto_type = new AutoType;
			$auto_type
				->setTitle( $title )
				->create();
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
			->createPostType('Model')
			->createPostType('Type');
	}

	/**
	 * @param $title
	 *
	 * @return $this
	 */
	private function createPostType( $title )
	{
		$labels = array (
			'name' => __( $title . 's', 'squirrels_inventory' ),
			'singular_name' => __( $title, 'squirrels_inventory' ),
			'add_new_item' => __( 'Add New ' . $title, 'squirrels_inventory' ),
			'edit_item' => __( 'Edit ' . $title, 'squirrels_inventory' ),
			'new_item' => __( 'New ' . $title, 'squirrels_inventory' ),
			'view_item' => __( 'View ' . $title, 'squirrels_inventory' ),
			'search_items' => __( 'Search ' . $title . 's', 'squirrels_inventory' ),
			'not_found' => __( 'No ' . strtolower( $title ) . 's found.', 'squirrels_inventory' )
		);

		$args = array (
			'labels' => $labels,
			'hierarchical' => FALSE,
			'description' => $title . 's',
			'supports' => array( 'title' ),
			'show_ui' => TRUE,
			'show_in_menu' => 'squirrels_inventory',
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
		add_menu_page('Squirrels Inventory', 'Squirrels', 'manage_options', 'squirrels_inventory', array( $this, 'pluginSettingsPage' ), 'dashicons-list-view');
		add_submenu_page('squirrels_inventory', __( 'Settings', 'squirrels_inventory' ), __( 'Settings', 'squirrels_inventory' ), 'manage_options', 'squirrels_inventory');
		add_submenu_page('squirrels_inventory', 'Features', 'Features', 'manage_options', 'squirrels_features', array($this, 'showFeaturesPage'));
		//add_submenu_page('squirrels_inventory', __( 'Makes', 'squirrels_inventory' ), __( 'Makes', 'squirrels_inventory' ), 'manage_options', 'edit.php?post_type=squirrels_make&order=asc');
		//add_submenu_page('squirrels_inventory', __( 'Models', 'squirrels_inventory' ), __( 'Models', 'squirrels_inventory' ), 'manage_options', 'edit.php?post_type=squirrels_model&order=asc');
		//add_submenu_page('squirrels_inventory', __( 'Types', 'squirrels_inventory' ), __( 'Types', 'squirrels_inventory' ), 'manage_options', 'edit.php?post_type=squirrels_type&order=asc');

	}

	public function showFeaturesPage()
	{
		include( dirname( __DIR__ ) . '/includes/features.inc');
	}

	public function pluginSettingsPage()
	{
		include( dirname( __DIR__ ) . '/includes/settings.inc');
	}

	public function customModelMeta()
	{
		add_meta_box( 'squirrels-model-meta', __( 'Additional Info', 'squirrels_inventory' ), array( $this, 'modelMeta' ), 'squirrels_model' );
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
			case AutoType::CUSTOM_POST_TYPE:
				$title = 'Ex: Car';
				break;
		}

		return $title;
	}

	public function addMakeColumnToModelList( $columns )
	{
		$new = array(
			'make_id' => __( 'Make', 'squirrels_inventory')
		);

		//Adding the new column before the current one. IE: Make, Model
		$columns = array_slice( $columns, 0, 1, TRUE ) + $new + array_slice( $columns, 1, NULL, TRUE );
		$columns['title'] = __( 'Model', 'squirrels_inventory');

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

		if ( $column == 'make_id' )
		{
			$make_id = get_post_meta( $post->ID, 'make_id', TRUE );
			if ( array_key_exists( $make_id, $makes ) )
			{
				echo $makes[ $make_id ]->getTitle();
			}
		}
	}

	/**
	 * Applies 4 filters to get the models page to sort by make and then model.
	 *
	 * Filters:
	 *  posts_fields - filtered by post type
	 *  posts_join - filtered by post type
	 *  posts_orderby - filtered by post type
	 *  manage_edit-{post_type}_sortable_columns
	 */
	public function setMakeColumnSortable( )
	{
		add_filter( 'manage_edit-' . Model::CUSTOM_POST_TYPE . '_sortable_columns', function($sortable_columns) {
			$sortable_columns[ 'make_id' ] = 'make';

			return $sortable_columns;
		} );

		add_filter( 'posts_fields', function( $fields, $query ) {
			if( $query->query_vars['post_type'] == Model::CUSTOM_POST_TYPE )
			{
				$fields .= ", x.post_title as make_id";
			}

			return $fields;
		}, 10, 2 );

		add_filter( 'posts_join', function($join, $query ) {

			global $wpdb;

			if( $query->query_vars['post_type'] == Model::CUSTOM_POST_TYPE )
			{
				$join .= "
					JOIN (
						SELECT
							p.id,
							p.post_title,
							pm.post_id,
							pm.meta_value
						FROM
							" . $wpdb->prefix . "posts p
						JOIN
							" . $wpdb->prefix . "postmeta pm
							ON pm.meta_value = p.id
						WHERE
							p.post_type = '" . Make::CUSTOM_POST_TYPE . "'
							AND pm.meta_key = 'make_id'
					) x
					ON x.post_id = wp_posts.id";
			}

			return $join;
		}, 10, 2 );

		add_filter( 'posts_orderby', function( $orderby, $query ) {
			if( $query->query_vars['post_type'] == Model::CUSTOM_POST_TYPE )
			{
				$orderby = 'x.post_title ' . $query->query_vars[ 'order' ] . ', wp_posts.post_title ' . $query->query_vars[ 'order' ];
			}

			return $orderby;
		}, 10, 2 );
	}
}