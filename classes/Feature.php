<?php

/**
 * ex: Transmission = Manual, Automatic
 */

namespace SquirrelsInventory;

class Feature {

	private $id;
	private $title;
	private $is_system = FALSE;
	private $is_true_false = FALSE;
	private $created_at;
	private $updated_at;

	/** FeatureOption[] $options */
	private $options;

}