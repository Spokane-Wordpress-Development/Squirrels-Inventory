<?php

/**
 * Ex: Ford
 */

namespace SquirrelsInventory;

class Make {

	/** @var Model[] $models */
	private $models;

	private $id;
	private $title;

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
}