<?php

/**
 * ex: Transmission = Manual, Automatic
 */

namespace SquirrelsInventory;

class Feature implements \JsonSerializable{

	private $id;
	private $title;
	private $is_system = FALSE;
	private $is_true_false = FALSE;
	private $created_at;
	private $updated_at;

	/** @var FeatureOption[] $options */
	private $options = array();

	/**
	 * Feature constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL )
	{
		$this
			->setId( $id )
			->read();
	}

	public function jsonSerialize() {

		return [
			'id' => $this->id,
			'title' => $this->title,
			'is_system' => $this->is_system,
			'is_true_false' => $this->is_true_false,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'options' => $this->options
		];

	}

	/**
	 *
	 */
	public function read()
	{
		if ( $this->id !== NULL )
		{
			global $wpdb;

			$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					`" . $wpdb->prefix . "squirrels_features`
				WHERE
					`id` = %d",
				$this->id
			);

			if ( $row = $wpdb->get_row( $sql ) )
			{
				$this->loadFromRow( $row );
			}
		}
	}

	/**
	 * @param \stdClass $row
	 */
	public function loadFromRow( \stdClass $row )
	{
		$this
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
				$this->addOption( $option );
			}
		}
	}

	/**
	 *
	 */
	public function create()
	{
		global $wpdb;

		if ( strlen($this->title) > 0 )
		{
			$this
				->setCreatedAt( time() )
				->setUpdatedAt( time() )
				->removeEmptyOptions()
				->sortOptionsByPosition();

			$options = $this->convertOptionsToArray();

			$wpdb->insert(
				$wpdb->prefix . 'squirrels_features',
				array(
					'title' => $this->title,
					'is_system' => $this->isSystem( TRUE ),
					'is_true_false' => $this->isTrueFalse( TRUE ),
					'options' => ( $this->isTrueFalse() || count( $options ) == 0) ? '' : json_encode( $options ),
					'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'%s',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s'
				)
			);

			$this->id = $wpdb->insert_id;
		}
	}

	/**
	 *
	 */
	public function update()
	{
		global $wpdb;

		if ( $this->id !== NULL )
		{
			$this
				->setUpdatedAt( time() )
				->removeEmptyOptions()
				->sortOptionsByPosition();

			$options = $this->convertOptionsToArray();

			$wpdb->update(
				$wpdb->prefix . 'squirrels_features',
				array(
					'title' => $this->title,
					'is_system' => $this->isSystem( TRUE ),
					'is_true_false' => $this->isTrueFalse( TRUE ),
					'options' => ( $this->isTrueFalse() || count( $options ) == 0) ? '' : json_encode( $options ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'id' => $this->id
				),
				array(
					'%s',
					'%d',
					'%d',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
		}
	}

	/**
	 *
	 */
	public function delete()
	{
		global $wpdb;

		if ( $this->id !== NULL )
		{
			$wpdb->delete(
				$wpdb->prefix . 'squirrels_features',
				array(
					'id' => $this->id
				),
				array(
					'%d'
				)
			);

			$this->id = NULL;
		}
	}

	/**
	 * @return array
	 */
	private function convertOptionsToArray()
	{
		$options = array();
		foreach ( $this->options as $option )
		{
			$options[] = array(
				'title' => $option->getTitle(),
				'position' => $option->getPosition(),
				'is_default' => $option->isDefault( TRUE )
			);
		}

		return $options;
	}

	/**
	 * @return $this
	 */
	private function removeEmptyOptions()
	{
		if(count($this->options) > 0)
		{
			foreach ( $this->options as $index => $option )
			{
				if ( strlen( $option->getTitle() ) == 0 )
				{
					unset ( $this->options[ $index ] );
				}
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function sortOptionsByPosition()
	{
		/* TODO: find a better way to do this */

		$positions = array();
		$options = $this->options;
		$this->options = array();

		foreach ( $options as $option )
		{
			$positions[] = $option->getPosition();
		}
		asort ( $positions );

		$count = 0;
		foreach ($positions as $position )
		{
			foreach ( $options as $index => $option )
			{
				if ( $position == $option->getPosition() )
				{
					$option->setPosition( ++$count );
					$this->options[] = $option;
					unset ( $options[ $index ] );
					break;
				}
			}
		}

		/* catch any left-overs */
		foreach ( $options as $option )
		{
			$option->setPosition( ++$count );
			$this->options[] = $option;
		}

		return $this;
	}

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
	 * @param bool $as_tiny_int
	 *
	 * @return bool|int
	 */
	public function isSystem( $as_tiny_int = FALSE )
	{
		if ( $as_tiny_int )
		{
			return ($this->is_system) ? 1 : 0;
		}
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
	 * @param bool $as_tiny_int
	 *
	 * @return bool|int
	 */
	public function isTrueFalse( $as_tiny_int = FALSE )
	{
		if ( $as_tiny_int )
		{
			return ($this->is_true_false) ? 1 : 0;
		}
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
	 *
	 * @return $this
	 */
	public function addOption( FeatureOption $option )
	{
		if ( $this->options === NULL )
		{
			$this->options = array();
		}

		$this->options[] = $option;

		return $this;
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
			$feature->loadFromRow( $row );
			$features[ $feature->getId() ] = $feature;
		}

		return $features;
	}

	/**
	 * @param $title
	 *
	 * @return bool|Feature
	 */
	public static function getFeatureByTitle( $title )
	{
		global $wpdb;

		$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					`" . $wpdb->prefix . "squirrels_features`
				WHERE
					`title` LIKE %s",
			$title
		);

		if ( $row = $wpdb->get_row( $sql ) )
		{
			$feature = new Feature;
			$feature->loadFromRow( $row );
			return $feature;
		}

		return FALSE;
	}
}