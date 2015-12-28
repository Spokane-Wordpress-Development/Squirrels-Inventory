<?php

/**
 * Ex: Ford
 */

namespace SquirrelsInventory;

class Make extends CustomPostType {

	const CUSTOM_POST_TYPE = 'squirrels_make';

	/** @var Model[] $models */
	private $models;

	/**
	 *
	 */
	public function create()
	{
		parent::_create( self::CUSTOM_POST_TYPE );
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
	 * @return Make[]
	 */
	public static function getAllMakes()
	{
		return parent::getAll( self::CUSTOM_POST_TYPE, __CLASS__ );
	}
}