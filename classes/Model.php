<?php

/**
 * ex: Mustang
 */

namespace SquirrelsInventory;

class Model {

	/** @var Make $make */
	private $make;

	private $id;
	private $make_id;
	private $title;

	/**
	 * Model constructor.
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
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param $id
	 *
	 * @return $this
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
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param $title
	 *
	 * @return $this
	 */
	public function setTitle( $title )
	{
		$this->title = $title;

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