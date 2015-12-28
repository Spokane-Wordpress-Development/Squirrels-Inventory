<?php

/**
 * ex: Mustang
 */

namespace SquirrelsInventory;

class Model extends CustomPostType {

	const CUSTOM_POST_TYPE = 'squirrels_model';

	/** @var Make $make */
	private $make;

	private $make_id;

	/**
	 *
	 */
	public function create()
	{
		if ( strlen( $this->title ) > 0 && strlen( $this->make_id ) > 0 )
		{
			$this->getIdFromTitleAndMakeId();
			if ( $this->id === NULL )
			{
				$this->id = wp_insert_post( array(
					'post_title' => $this->title,
					'post_type' => self::CUSTOM_POST_TYPE,
					'post_status' => 'publish'
				), TRUE );

				update_post_meta( $this->id, 'make_id', $this->make_id);
			}
		}
	}

	/**
	 *
	 */
	public function getIdFromTitleAndMakeId()
	{
		$query = new \WP_Query( array(
			'post_type' => self::CUSTOM_POST_TYPE,
			'post_status' => 'publish',
			'title' => $this->title,
			'meta_query' => array(
				array(
					'key' => 'make_id',
					'value' => $this->make_id
				)
			)
		) );

		if ( $query->have_posts() )
		{
			$query->the_post();
			$this->id = get_the_ID();
		}
	}

	/**
	 * @return Make
	 */
	public function getMake()
	{
		return $this->make;
	}

	/**
	 * @param $make
	 *
	 * @return $this
	 */
	public function setMake( $make )
	{
		$this->make = $make;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMakeId()
	{
		return $this->make_id;
	}

	/**
	 * @param $make_id
	 *
	 * @return $this
	 */
	public function setMakeId( $make_id )
	{
		if (is_numeric($make_id))
		{
			$this->make_id = abs(round($make_id));
		}

		return $this;
	}

	/**
	 * @return Model[]
	 */
	public static function getAllModels()
	{
		$models = array();
		$makes = Make::getAllMakes();

		return $models;
	}


}