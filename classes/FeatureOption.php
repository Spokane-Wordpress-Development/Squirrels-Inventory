<?php

/**
 * ex: Manual, Automatic
 *
 * Extracted from a json string in the \SquirrelsInventory\Feature table
 * [{"title":"Automatic","position":"1","is_default":"1"},{"title":"Manual","position":"2","is_default":"0"}]
 */

namespace SquirrelsInventory;

class FeatureOption {

	/** @var Feature $feature */
	private $feature;

	private $title;
	private $position;
	private $is_default = FALSE;

	/**
	 * @return Feature
	 */
	public function getFeature()
	{
		return $this->feature;
	}

	/**
	 * @param Feature $feature
	 *
	 * @return FeatureOption
	 */
	public function setFeature( $feature )
	{
		$this->feature = $feature;

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
	 * @return FeatureOption
	 */
	public function setTitle( $title )
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isDefault()
	{
		return $this->is_default;
	}

	/**
	 * @param boolean $is_default
	 *
	 * @return FeatureOption
	 */
	public function setIsDefault( $is_default )
	{
		$this->is_default = ($is_default === TRUE || $is_default == 1) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param mixed $position
	 *
	 * @return FeatureOption
	 */
	public function setPosition( $position )
	{
		$this->position = (is_numeric($position)) ? abs(round($position)) : 0;

		return $this;
	}
}