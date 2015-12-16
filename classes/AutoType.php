<?php

/*
 * ex: Car, Truck, RV
 */

namespace SquirrelsInventory;

class AutoType {

	private $id;
	private $title;
	private $is_active = FALSE;

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
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->is_active;
	}

	/**
	 * @param boolean $is_active
	 *
	 * @return AutoType
	 */
	public function setIsActive( $is_active )
	{
		$this->is_active = ($is_active === TRUE || $is_active == 1) ? TRUE : FALSE;

		return $this;
	}
}