<?php

/**
 * Ex: Ford
 */

namespace SquirrelsInventory;

class Make {

	const CUSTOM_POST_TYPE = 'squirrels_make';

	/** @var Model[] $models */
	private $models;

	private $id;
	private $title;

	/**
	 * Make constructor.
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
	 * @return Model[]
	 */
	public function getModels()
	{
		return $this->models;
	}

	/**
	 * @param Model[] $models
	 *
	 * @return Make
	 */
	public function setModels( $models )
	{
		$this->models = $models;

		return $this;
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
	 * @return Make
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
	 * @return Make
	 */
	public function setTitle( $title )
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return Make[]
	 */
	public static function getAllMakes()
	{
		$makes = array();

		$query = new \WP_Query( array(
			'post_type' => self::CUSTOM_POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order_by' => 'post_title'
		) );

		if( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$make = new Make( get_the_ID(), get_the_title() );
				$makes[ get_the_ID() ] = $make;
			endwhile;
		}

		return $makes;
	}
}