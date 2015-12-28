<?php

namespace SquirrelsInventory;

class CustomPostType {

	protected $id;
	protected $title;

	/**
	 * CustomPostType constructor.
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
	 * @param $custom_post_type
	 */
	public function _create( $custom_post_type )
	{
		if ( strlen( $this->title ) > 0 )
		{
			$this->getIdFromTitle( $custom_post_type );
			if ( $this->id === NULL )
			{
				$this->id = wp_insert_post( array(
					'post_title' => $this->title,
					'post_type' => $custom_post_type,
					'post_status' => 'publish'
				), TRUE );
			}
		}
	}

	/**
	 * @param $custom_post_type
	 */
	public function getIdFromTitle( $custom_post_type )
	{
		$query = new \WP_Query( array(
			'post_type' => $custom_post_type,
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
	 * @param $custom_post_type
	 * @param $class
	 *
	 * @return array
	 */
	public static function getAll( $custom_post_type, $class )
	{
		$posts = array();

		$query = new \WP_Query( array(
			'post_type' => $custom_post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => array( 'title' => 'ASC' )
		) );

		if( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$post = new $class( get_the_ID(), get_the_title() );
				$posts[ get_the_ID() ] = $post;
			endwhile;
		}

		return $posts;
	}
}