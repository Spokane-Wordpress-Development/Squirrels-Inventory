<?php

namespace SquirrelsInventory;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class AutoTable extends \WP_List_Table {

	/**
	 * FeatureTable constructor.
	 */
	public function __construct()
	{
		parent::__construct( array(
            'singular' => __( 'Inventory', 'squirrels_inventory' ),
            'plural' => __( 'Inventory', 'squirrels_inventory' ),
            'ajax' => TRUE
        ) );
	}

	/**
	 * @return array
	 */
	public function get_columns()
	{
		return array(
			'inventory_number' => __( 'Inventory #', 'squirrels_inventory'),
			'type' => __( 'Type', 'squirrels_inventory'),
			'make' => __( 'Make', 'squirrels_inventory'),
			'model' => __( 'Model', 'squirrels_inventory'),
			'year' => __( 'Year', 'squirrels_inventory'),
			'vin' => __( 'Vin', 'squirrels_inventory'),
			'odometer_reading' => __( 'Odometer', 'squirrels_inventory' ),
			'price' => __( 'Price', 'squirrels_inventory' ),
			'is_visible' => __( 'Visible', 'squirrels_inventory' ),
			'is_featured' => __( 'Featured', 'squirrels_inventory' ),
			'updated_at' => __( 'Last Updated', 'squirrels_inventory' ),
			'edit' => ''
		);
	}

	/**
	 * @return array
	 */
	public function get_sortable_columns()
	{
		return array(
			'inventory_number' => array( 'inventory_number', TRUE ),
			'updated_at' => array( 'updated_at', TRUE ),
			'type' => array( '3', TRUE ),
			'make' => array( '1', TRUE ),
			'model' => array( '2', TRUE ),
			'price' => array( 'price', TRUE ),
			'vin' => array( 'vin', TRUE ),
			'odometer_reading' => array( 'odometer_reading', TRUE ),
			'is_visible' => array( 'is_visible', TRUE ),
			'is_featured' => array( 'is_featured', TRUE ),
			'year' => array( 'year', TRUE )
		);
	}

	/**
	 * @param object $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'inventory_number':
			case 'vin':
			case 'type':
			case 'make':
			case 'model':
			case 'year':
				return $item->$column_name;
			case 'updated_at':
				return date('n/j/Y', strtotime( $item->$column_name ) );
			case 'is_visible':
			case 'is_featured':
				return ( filter_var( $item->$column_name, FILTER_VALIDATE_BOOLEAN ) ) ? __( 'Yes', 'squirrels_inventory' ) : __( 'No', 'squirrels_inventory' ) ;
			case 'odometer_reading':
				return number_format( $item->$column_name );
			case 'price':
				return '$' . number_format( $item->$column_name, 2 );
			case 'edit':
				return '<a href="?page=' . $_REQUEST['page'] . '&action=edit&id=' . $item->id . '" class="button-primary">' . __('Edit', 'squirrels_inventory') . '</a>';
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 *
	 */
	public function prepare_items()
	{
		global $wpdb;

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		/*
		 * Standard method of grabbing everything from the inventory copied from FeatureTable
		 */
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
					ON p_types.id = si.type_id";
		if ( isset( $_GET[ 'orderby' ] ) )
		{
			foreach ( $sortable as $s )
			{
				if ( $s[ 0 ] == $_GET[ 'orderby' ] )
				{
					$sql .= "
						ORDER BY " . $_GET[ 'orderby' ] . " " . ( ( isset( $_GET['order']) && strtolower( $_GET['order'] == 'desc' ) ) ? "DESC" : "ASC" );
					break;
				}
			}
		}

		$total_items = $wpdb->query($sql);

		$max_per_page = 10;
		$paged = ( isset( $_GET[ 'paged' ] ) && is_numeric( $_GET['paged'] ) ) ? abs( round( $_GET[ 'paged' ])) : 1;
		$total_pages = ceil( $total_items / $max_per_page );

		if ( $paged > $total_pages )
		{
			$paged = $total_pages;
		}

		$offset = ( $paged - 1 ) * $max_per_page;
		$offset = ( $offset < 0 ) ? 0 : $offset; //MySQL freaks out about LIMIT -10, 10 type stuff.

		$sql .= "
			LIMIT " . $offset . ", " . $max_per_page;

		$this->set_pagination_args( array(
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'per_page' => $max_per_page
        ) );

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->items = $wpdb->get_results( $sql );

		/*
		 * Can't join the feature tables to the inventory table as the features are stored in a json array.
		 * Grabbing all the features because if we have the whole inventory then we will be using all the features.
		 */
		$features = Feature::getAllFeatures();

		/*
		 * Looping through the retrieved items
		 */
		foreach( $this->items as $index => $item )
		{
			if( $item->features != NULL )
			{
				$feature_ids = json_decode( $item->features );

				// Loop through each of the assigned features in each item in the inventory
				foreach( $feature_ids as $feature_id => $option_index )
				{
					if( isset( $features[ $feature_id ] ) )
					{
						//No entry in the inventory item object called 'features'. This will be an array of feature title to option selected
						if( $features[ $feature_id ]->isTrueFalse() )
						{
							$this->items[ $index ]->selected_features[ $features[ $feature_id ]->getTitle() ] = null;
						}
						else
						{
							$options = $features[ $feature_id ]->getOptions();

							$this->items[ $index ]->selected_features[ $features[ $feature_id ]->getTitle()] = $options[ $option_index ]->getTitle();
						}
					}
				}
			}
		}
	}

	/**
	 * Overriding this function so that I can add nowrap to the vin and features columns
	 *
	 * @param object $item The current item
	 */
	protected function single_row_columns( $item ) {

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {


			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			// Comments column uses HTML in the display name with screen reader text.
			// Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";

			if ( 'cb' === $column_name ) {
				echo '<th scope="row" class="check-column">';
				echo $this->column_cb( $item );
				echo '</th>';
			} elseif ( method_exists( $this, '_column_' . $column_name ) ) {
				echo call_user_func(
					array( $this, '_column_' . $column_name ),
					$item,
					$classes,
					$data,
					$primary
				);
			} elseif ( method_exists( $this, 'column_' . $column_name ) ) {
				echo "<td $attributes >";
				echo call_user_func( array( $this, 'column_' . $column_name ), $item );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo "</td>";
			} else {
				/* This is the only part that changes from the parent. */
				echo "<td $attributes " . ( ($column_name == 'vin' || $column_name == 'features' ) ? 'nowrap' : '' ) . ">";
				echo $this->column_default( $item, $column_name );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo "</td>";
			}
		}
	}

	/**
	 * Overriding this to remove the default classes.
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		return array( $this->_args['plural'] );
	}

	/**
	 * Overriding this to change the table classes to bootstrap table classes
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

		/* Line below is the only part changing. */ ?>
		<table class="table table-bordered <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>

			<tbody id="the-list"<?php
			if ( $singular ) {
				echo " data-wp-lists='list:$singular'";
			} ?>>
			<?php $this->display_rows_or_placeholder(); ?>
			</tbody>

			<tfoot>
			<tr>
				<?php $this->print_column_headers( false ); ?>
			</tr>
			</tfoot>

		</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}
}