<?php

namespace SquirrelsInventory;

class Controller {

	const VERSION = '1.0';

	public $action = '';
	public $data = '';
	public $return = '';
	public $attributes = array();

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

		/** SQUIRRELS_INVENTORY table */
		$table = $wpdb->prefix . "squirrels_inventory";
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`inventory_number` VARCHAR(50) DEFAULT NULL,
					`vin` VARCHAR(50) DEFAULT NULL,
					`type_id` INT(11) DEFAULT NULL,
					`make_id` INT(11) DEFAULT NULL,
					`model_id` INT(11) DEFAULT NULL,
					`year` INT(2) DEFAULT NULL,
					`odometer_reading` INT(11) DEFAULT NULL,
					`features` TEXT,
					`is_visible` TINYINT(4) DEFAULT 0,
					`is_featured` TINYINT(4) DEFAULT 0,
					`description` TEXT,
					`price` DECIMAL(11,4) DEFAULT NULL,
					`exterior` VARCHAR(50) DEFAULT NULL,
					`interior` VARCHAR(50) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`imported_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		$make_id = 0;
		$model_id = 0;
		$type_id = 0;

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

				if ($make->getTitle() == 'Ford' && $model->getTitle() == 'Mustang')
				{
					$make_id = $make->getId();
					$model_id = $model->getId();
				}
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

			if ($title == 'Car')
			{
				$type_id = $auto_type->getId();
			}
		}

		/** Add a couple sample Features */
		if ( Feature::getFeatureByTitle( 'Transmission' ) === FALSE )
		{
			$feature = new Feature;
			$feature
				->setTitle( 'Transmission' )
				->setIsTrueFalse( FALSE )
				->addOption( new FeatureOption( 'Automatic', 1, TRUE ) )
				->addOption( new FeatureOption( 'Manual', 2, FALSE ) )
				->create();

			$feature = new Feature;
			$feature
				->setTitle( 'AWD' )
				->setIsTrueFalse( TRUE )
				->create();
		}

		/** Add a sample Car */
		if ($make_id > 0 && $model_id > 0 && $type_id > 0)
		{
			$auto = new Auto();
			$auto
				->setTypeId( $type_id )
				->setInventoryNumber( '123456' )
				->setVin( 'QWERTY' )
				->setMakeId( $make_id )
				->setModelId( $model_id )
				->setYear( '1965' )
				->setOdometerReading( 50000 )
				->setPrice( 100000 )
				->setDescription( 'This is a sample car.' )
				->setExterior( 'Red' )
				->setInterior( 'Black' )
				->setIsVisible( TRUE )
				->create();
		}
	}

	private function getMakesModels()
	{
		$json = file_get_contents( dirname( __DIR__ ) . '/includes/makes_models.json' );
		return json_decode( $json, TRUE );
	}

	public function init()
	{
		if ( !session_id() )
		{
			session_start();
		}

		wp_enqueue_style( 'squirrels-bootstrap-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/grid12.css', array(), time() );
		wp_enqueue_style( 'squirrels-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/squirrels_inventory.css', array(), time() );
	}

	public function queryVars( $vars )
	{
		$vars[] = 'sq_action';
		$vars[] = 'sq_data';
		return $vars;
	}

	/**
	 * @param $attributes
	 *
	 * @return string
	 */
	public function shortCode( $attributes )
	{
		$this->action = get_query_var('sq_action');
		$this->data = get_query_var('sq_data');

		$this->attributes = shortcode_atts( array(
			'make' => '',
			'type' => ''
		), $attributes );

		switch ( $this->action )
		{
			case 'auto':

				return $this->showPublicAutoPage();
				break;

			default:

				return $this->showPublicInventoryPage();
				break;
		}
	}

	/**
	 * @return string
	 */
	public function showPublicAutoPage()
	{
		return $this->return . $this->returnOutputFromPage('/includes/public_auto.php');
	}

	/**
	 * @return string
	 */
	public function showPublicInventoryPage()
	{
		return $this->return . $this->returnOutputFromPage('/includes/public_inventory.php');
	}

	/**
	 * @param $page
	 *
	 * @return string
	 */
	private function returnOutputFromPage($page)
	{
		ob_start();
		include(dirname(__DIR__) . $page);
		return ob_get_clean();
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
			'show_in_menu' => 'squirrels',
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
		add_menu_page('Squirrels Inventory', 'Squirrels', 'manage_options', 'squirrels', array( $this, 'pluginSettingsPage' ), 'dashicons-list-view');
		add_submenu_page('squirrels', __( 'Settings', 'squirrels_inventory' ), __( 'Settings', 'squirrels_inventory' ), 'manage_options', 'squirrels');
		add_submenu_page('squirrels', __( 'Features', 'squirrels_inventory' ), __( 'Features', 'squirrels_inventory' ), 'manage_options', 'squirrels_features', array($this, 'showFeaturesPage'));
		add_submenu_page('squirrels', __( 'Inventory', 'squirrels_inventory' ), __( 'Inventory', 'squirrels_inventory' ), 'manage_options', 'squirrels_inventory', array($this, 'showInventoryPage'));
	}

	public function showFeaturesPage()
	{
		include( dirname( __DIR__ ) . '/includes/features.php');
	}

	public function showInventoryPage()
	{
		include( dirname( __DIR__ ) . '/includes/inventory.php');
	}

	public function pluginSettingsPage()
	{
		include( dirname( __DIR__ ) . '/includes/settings.php');
	}

	public function customModelMeta()
	{
		add_meta_box( 'squirrels-model-meta', __( 'Additional Info', 'squirrels_inventory' ), array( $this, 'modelMeta' ), 'squirrels_model' );
	}

	public function modelMeta()
	{
		include( dirname( __DIR__ ) ) . '/includes/model_meta.php';
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

	/**
	 *
	 */
	public function enqueueAdminScripts()
	{
		wp_enqueue_script( 'squirrels-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array( 'jquery' ), time(), TRUE );
		wp_localize_script( 'squirrels-admin', 'url_variables', $_GET );
		wp_enqueue_style( 'squirrels-admin-bootstrap-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-tables.css' );
	}

	/**
	 * AJAX action for adding a new feature.
	 */
	public function createFeature()
	{
		$feature = new Feature();

		$feature
			->setTitle( stripslashes( $_REQUEST['title'] ) );

		if($_REQUEST['option'] == 0)
		{
			$feature->setIsTrueFalse( TRUE );
		}
		else
		{
			$feature->setIsTrueFalse( FALSE );

			foreach( $_REQUEST['custom_options'] as $index => $option )
			{
				$feature->addOption( new FeatureOption( stripslashes( $option['value'] ), $index+1, filter_var( $option['is_default'], FILTER_VALIDATE_BOOLEAN ) ) );
			}
		}

		$feature->create();

		return $feature->getId();
	}

	/**
	 * AJAX action for editing a feature.
	 */
	public function editFeature()
	{
		$feature = new Feature($_REQUEST['id']);

		$feature
			->setTitle( stripslashes( $_REQUEST['title'] ) );

		if($_REQUEST['option'] == 0)
		{
			$feature->setIsTrueFalse( TRUE );
		}
		else
		{
			$feature->setOptions( array() );

			$feature->setIsTrueFalse( FALSE );

			foreach( $_REQUEST['custom_options'] as $index => $option )
			{
				$feature->addOption( new FeatureOption( stripslashes( $option['value'] ), $index+1, filter_var( $option['is_default'], FILTER_VALIDATE_BOOLEAN ) ) );
			}
		}

		$feature->update();

		return $feature->getId();
	}

	/**
	 * AJAX action for deleting feature.
	 */
	public function deleteFeature()
	{
		$feature = new Feature( $_REQUEST['id'] );
		$feature->delete();

		//Since delete doesn't return anything, this will check for success
		return $feature->getId() == NULL;
	}

	public function addToInventory()
	{
		if ( strlen( $_REQUEST['new_make'] ) > 0 && strlen( $_REQUEST['new_model'] ) > 0 )
		{
			$make = new Make();
			$make
				->setTitle( $_REQUEST['new_make'] )
				->create();

			$model = new Model();
			$model
				->setTitle( $_REQUEST['new_model'] )
				->setMake( $make )
				->create();
		}
		else
		{
			$model = new Model( $_REQUEST['model_id'] );
			$model->loadMake();
		}

		$auto = new Auto();
		$auto
			->setPrice( $_REQUEST['price'] )
			->setTypeId( $_REQUEST['type_id'] )
			->setInventoryNumber( $_REQUEST['inventory_number'] )
			->setVin( $_REQUEST['vin'] )
			->setMakeId( $model->getMakeId() )
			->setModelId( $model->getId() )
			->setYear( $_REQUEST['year'] )
			->setOdometerReading( preg_replace('/\D/', '', $_REQUEST['odometer_reading']) )
			->setDescription( $_REQUEST['description'] )
			->setIsVisible( $_REQUEST['is_visible'] )
			->setIsFeatured( $_REQUEST['is_featured'] )
			->setExterior( $_REQUEST['exterior'] )
			->setInterior( $_REQUEST['interior'] )
			->create();

		return $auto->getId();
	}

	public function editInventory()
	{
		if ( strlen( $_REQUEST['new_make'] ) > 0 && strlen( $_REQUEST['new_model'] ) > 0 )
		{
			$make = new Make();
			$make
				->setTitle( $_REQUEST['new_make'] )
				->create();

			$model = new Model();
			$model
				->setTitle( $_REQUEST['new_model'] )
				->setMake( $make )
				->create();
		}
		else
		{
			$model = new Model( $_REQUEST['model_id'] );
			$model->loadMake();
		}

		$auto = new Auto( $_REQUEST['id'] );
		$auto
			->setPrice( $_REQUEST['price'] )
			->setTypeId( $_REQUEST['type_id'] )
			->setInventoryNumber( $_REQUEST['inventory_number'] )
			->setVin( $_REQUEST['vin'] )
			->setMakeId( $model->getMakeId() )
			->setModelId( $model->getId() )
			->setYear( $_REQUEST['year'] )
			->setDescription( $_REQUEST['description'] )
			->setIsVisible( $_REQUEST['is_visible'] )
			->setIsFeatured( $_REQUEST['is_featured'] )
			->setOdometerReading( preg_replace('/\D/', '', $_REQUEST['odometer_reading']) )
			->update();

		return $auto->getId();
	}

	public function deleteFromInventory()
	{
		$auto = new Auto( $_REQUEST['id'] );
		$auto->delete();

		return $auto->getId() == NULL;
	}

	public function getCurrentInventory()
	{
		global $wpdb;
		$autos = array();

		$sql = "
			SELECT
				p_makes.post_title AS make,
				p_models.post_title AS model,
				p_types.post_title AS `type`,
				si.*
			FROM
				" . $wpdb->prefix . "squirrels_inventory si
				JOIN " . $wpdb->prefix . "posts p_makes
					ON p_makes.id = si.make_id
				JOIN " . $wpdb->prefix . "posts p_models
					ON p_models.id = si.model_id
				JOIN " . $wpdb->prefix . "posts p_types
					ON p_types.id = si.type_id
			WHERE
				si.is_visible = 1";

		$results = $wpdb->get_results( $sql );
		foreach( $results as $result )
		{
			$auto = new Auto();
			$auto->loadFromRow( $result );
			$autos[$auto->getId()] = $auto;
		}

		return $autos;
	}
}