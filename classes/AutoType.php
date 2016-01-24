<?php

/*
 * ex: Car, Truck, RV
 */

namespace SquirrelsInventory;

class AutoType extends CustomPostType {

	const CUSTOM_POST_TYPE = 'squirrels_type';

	/**
	 *
	 */
	public function create()
	{
		parent::_create( self::CUSTOM_POST_TYPE );
	}

	/**
	 * @return AutoType[]
	 */
	public static function getAllAutoTypes()
	{
		return parent::getAll( self::CUSTOM_POST_TYPE, __CLASS__ );
	}
}