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

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $id
	 *
	 * @return $this
	 */
	public function setId( $id ) {
		$this->id = ( is_numeric( $id ) ) ? abs(round($id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param $title
	 *
	 * @return $this
	 */
	public function setTitle( $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isSystem() {
		return $this->is_system;
	}

	/**
	 * @param $is_system
	 *
	 * @return $this
	 */
	public function setIsSystem( $is_system ) {
		$this->is_system = ($is_system === TRUE || $is_system == 1) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isTrueFalse() {
		return $this->is_true_false;
	}

	/**
	 * @param $is_true_false
	 *
	 * @return $this
	 */
	public function setIsTrueFalse( $is_true_false ) {
		$this->is_true_false = ($is_true_false === TRUE || $is_true_false == 1) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @param null $format
	 *
	 * @return mixed
	 */
	public function getCreatedAt( $format = NULL ) {
		return ($format === NULL) ? $this->created_at : date( $format, $this->created_at );
	}

	/**
	 * @param $created_at
	 *
	 * @return $this
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = ( is_numeric( $created_at) || $created_at === NULL ) ? $created_at : strtotime( $created_at );

		return $this;
	}

	/**
	 * @param null $format
	 *
	 * @return bool|string
	 */
	public function getUpdatedAt( $format = NULL ) {
		return ($format === NULL) ? $this->updated_at : date( $format, $this->updated_at );
	}

	/**
	 * @param $updated_at
	 *
	 * @return $this
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = ( is_numeric( $updated_at) || $updated_at === NULL ) ? $updated_at : strtotime( $updated_at );

		return $this;
	}

	/**
	 * @return FeatureOption[]
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @param $options
	 *
	 * @return $this
	 */
	public function setOptions( $options ) {
		$this->options = $options;

		return $this;
	}

	/**
	 * @param FeatureOption $option
	 */
	public function addOption( FeatureOption $option )
	{
		if ( $this->options === NULL )
		{
			$this->options = array();
		}

		$this->options[] = $option;
	}

	/**
	 * @return Feature[]
	 */
	public static function getAllFeatures()
	{
		global $wpdb;
		$features = array();

		$rows = $wpdb->get_results("
			SELECT
				*
			FROM
				" . $wpdb->prefix . "squirrels_features
			ORDER BY
				title ASC");
		foreach ( $rows as $row )
		{
			$feature = new Feature;
			$feature
				->setId( $row->id )
				->setTitle( $row->title )
				->setIsSystem( $row->is_system )
				->setIsTrueFalse( $row->is_true_false )
				->setCreatedAt( $row->created_at )
				->setUpdatedAt( $row->updated_at );

			if ( strlen( $row->options ) > 0 )
			{
				$options = json_decode( $row->options, TRUE );
				foreach ( $options as $opt)
				{
					$option = new FeatureOption;
					$option
						->setTitle( $opt['title'] )
						->setPosition( $opt['position'] )
						->setIsDefault( $opt['is_default'] );
					$feature->addOption( $option );
				}
			}

			$features[] = $feature;
		}

		return $features;
	}
}