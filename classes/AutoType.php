<?php

/*
 * ex: Car, Truck, RV
 */

namespace SquirrelsInventory;

class AutoType {

	const CUSTOM_POST_TYPE = 'squirrels_type';

	private $id;
	private $title;

	/**
	 * AutoType constructor.
	 *
	 * @param null $id
	 * @param null $title
	 */
	public function __construct( $id=NULL, $title=NULL )
	{
		$this
			->setId( $id )
			->setTitle( $title );
	}

	/**
	 *
	 */
	public function create()
	{
		if ( strlen( $this->title ) > 0 )
		{
			$this->getIdFromTitle();
			if ( $this->id === NULL )
			{
				$this->id = wp_insert_post( array(
					'post_title' => $this->title,
					'post_type' => self::CUSTOM_POST_TYPE,
					'post_status' => 'publish'
				), TRUE );
			}
		}
	}

	/**
	 *
	 */
	public function getIdFromTitle()
	{
		$query = new \WP_Query( array(
			'post_type' => self::CUSTOM_POST_TYPE,
			'post_status' => 'publish',
			'title' => $this->title
		) );

		if ( $query->have_posts() )
		{
			$query->the_post();
			$this->id = get_the_ID();
		}
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return AutoType
	 */
	public function setId( $id )
	{
		if (is_numeric($id))
		{
			$this->id = abs(round($id));
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 *
	 * @return AutoType
	 */
	public function setTitle( $title )
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return array
	 */
	public static function getAllAutoTypes()
	{
		$auto_types = array();

		$query = new \WP_Query( array(
			'post_type' => self::CUSTOM_POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order_by' => 'post_title'
		) );

		if( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$auto_type = new AutoType( get_the_ID(), get_the_title() );
				$auto_types[ get_the_ID() ] = $auto_type;
			endwhile;
		}

		return $auto_types;
	}
}