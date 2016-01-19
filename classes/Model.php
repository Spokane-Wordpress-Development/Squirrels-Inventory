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
			$this->make_id = intval(abs(round($make_id)));
		}

		return $this;
	}

	/**
	 * This returns all models sorted by Make Title, then Model Title
	 *
	 * @return \SquirrelsInventory\Model[]
	 */
	public static function getAllModels()
	{
		$models = array();
		$makes = Make::getAllMakes();

		$parents = array(
			0 => array()
		);

		foreach ($makes as $make_id => $make)
		{
			$parents[ $make_id ] = array();
		}

		$query = new \WP_Query( array(
			'post_type' => self::CUSTOM_POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
            'order' => 'ASC'
		) );

		if( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$custom = get_post_custom( get_the_ID() );
				$model = new Model( get_the_ID(), get_the_title() );
				if ( array_key_exists( 'make_id', $custom ))
				{
					$make_id = $custom[ 'make_id' ][0];
					$model->setMakeId( $make_id );
					if ( array_key_exists( $model->getMakeId(), $makes ) )
					{
						$model->setMake( $makes[ $model->getMakeId() ] );
					}
				}
				else
				{
					$make_id = 0;
				}
				$parents[ $make_id ][] = $model;
			endwhile;
		}

		foreach ($parents as $parent_id => $_models)
		{
			/** @var Model[] $_models */
			foreach ($_models as $model)
			{
				$models[ $model->getId() ] = $model;
			}
		}

		return $models;
	}


}