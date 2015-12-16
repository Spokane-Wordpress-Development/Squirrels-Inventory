<?php

namespace SquirrelsInventory;

class Auto {

	/** @var AutoFeature[] $features */
	private $features;

	/** @var AutoType $type */
	private $type;

	/** @var Make $make */
	private $make;

	/** @var Model $model */
	private $model;

	private $id;
	private $inventory_number;
	private $vin;
	private $make_id;
	private $model_id;
	private $year;
	private $odomter_reading;
}