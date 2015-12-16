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
	private $odometer_reading;
	private $is_visible = FALSE;
	private $created_at;
	private $imported_at;
	private $updated_at;
}