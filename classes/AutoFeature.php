<?php

namespace SquirrelsInventory;

class AutoFeature {

	private $id;
	private $feature_id;
	private $feature_title;
	private $value;
	private $created_at;
	private $updated_at;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return AutoFeature
	 */
	public function setId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFeatureId() {
		return $this->feature_id;
	}

	/**
	 * @param mixed $feature_id
	 *
	 * @return AutoFeature
	 */
	public function setFeatureId( $feature_id ) {
		$this->feature_id = (is_numeric($feature_id)) ? abs(round($feature_id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFeatureTitle() {
		return $this->feature_title;
	}

	/**
	 * @param mixed $feature_title
	 *
	 * @return AutoFeature
	 */
	public function setFeatureTitle( $feature_title ) {
		$this->feature_title = $feature_title;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return AutoFeature
	 */
	public function setValue( $value ) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @param string $format
	 *
	 * @return mixed
	 */
	public function getCreatedAt( $format=NULL ) {
		return ($format === NULL) ? $this->created_at : date( $format, $this->created_at );
	}

	/**
	 * @param mixed $created_at
	 *
	 * @return AutoFeature
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = (is_numeric($created_at) || $created_at === NULL) ? $created_at : strtotime( $created_at );

		return $this;
	}

	/**
	 * @param string $format
	 *
	 * @return mixed
	 */
	public function getUpdatedAt( $format=NULL ) {
		return ($format === NULL) ? $this->updated_at : date( $format, $this->updated_at );
	}

	/**
	 * @param mixed $updated_at
	 *
	 * @return AutoFeature
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = (is_numeric($updated_at) || $updated_at === NULL) ? $updated_at : strtotime( $updated_at );

		return $this;
	}
}