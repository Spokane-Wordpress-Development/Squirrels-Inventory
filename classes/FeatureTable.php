<?php

namespace SquirrelsInventory;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class FeatureTable extends \WP_List_Table {

	/**
	 * FeatureTable constructor.
	 */
	public function __construct()
	{
		parent::__construct( array(
			'singular' => __( 'Feature', 'squirrels_inventory' ),
			'plural' => __( 'Features', 'squirrels_inventory' ),
			'ajax' => TRUE
		) );
	}

	/**
	 * @return array
	 */
	public function get_columns()
	{
		return array(
			'col_title' => __( 'Feature', 'squirrels_inventory' ),
			'col_options' => __( 'Options', 'squirrels_inventory' )
		);
	}

	/**
	 * @return array
	 */
	public function get_sortable_columns()
	{
		return array(
			'col_title' => array( 'title', TRUE )
		);
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

		$sql = "
			SELECT
				*
			FROM
				" . $wpdb->prefix . "squirrels_features";
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
	}

	/**
	 * @param $item
	 *
	 * @return string
	 */
	public function column_col_options( $item )
	{
		if ( $item->is_true_false == 1 )
		{
			$return = __( 'Yes', 'squirrels_inventory' ) . ' / ' . __( 'No', 'squirrels_inventory' );
		}
		else
		{
			$options = json_decode( $item->options, TRUE );
			$return = '';
			foreach ($options as $option)
			{
				$return .= $option['title'] . '<br>';
			}
		}

		return $return;
	}

	/**
	 * @param $item
	 *
	 * @return string
	 */
	public function column_col_title( $item )
	{
		$actions = array(
			'edit' => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'edit', $item->id, __( 'Edit' ) ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'delete', $item->id, __( 'Delete' ) )
		);

		return sprintf( '%1$s %2$s', $item->title, $this->row_actions( $actions ) );
	}
}