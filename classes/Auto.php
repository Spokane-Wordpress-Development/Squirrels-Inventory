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

	private $id = 0;
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

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Auto
	 */
	public function setId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getInventoryNumber() {
		return $this->inventory_number;
	}

	/**
	 * @param mixed $inventory_number
	 *
	 * @return Auto
	 */
	public function setInventoryNumber( $inventory_number ) {
		$this->inventory_number = $inventory_number;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getVin() {
		return $this->vin;
	}

	/**
	 * @param mixed $vin
	 *
	 * @return Auto
	 */
	public function setVin( $vin ) {
		$this->vin = $vin;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMakeId() {
		return $this->make_id;
	}

	/**
	 * @param mixed $make_id
	 *
	 * @return Auto
	 */
	public function setMakeId( $make_id ) {
		if(is_numeric($make_id) && $make_id > 0)
		{
			$this->make_id = $make_id;
		}
		else
		{
			$this->make_id = NULL;
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getModelId() {
		return $this->model_id;
	}

	/**
	 * @param mixed $model_id
	 *
	 * @return Auto
	 */
	public function setModelId( $model_id ) {
		if(is_numeric($model_id) && $model_id > 0)
		{
			$this->model_id = $model_id;
		}
		else
		{
			$this->model_id = NULL;
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param mixed $year
	 *
	 * @return Auto
	 */
	public function setYear( $year ) {
		$this->year = preg_replace("/[^\d]/","",$year);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getOdometerReading() {
		return $this->odometer_reading;
	}

	/**
	 * @param mixed $odometer_reading
	 *
	 * @return Auto
	 */
	public function setOdometerReading( $odometer_reading ) {
		$this->odometer_reading = preg_replace("/[^\d]/","",$odometer_reading); //strip out non numbers

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isIsVisible() {
		return $this->is_visible;
	}

	/**
	 * @param boolean $is_visible
	 *
	 * @return Auto
	 */
	public function setIsVisible( $is_visible ) {
		$this->is_visible = $is_visible;

		return $this;
	}

	/**
	 * @param string $feature
	 *
	 * @return mixed
	 */
	public function getCreatedAt( $feature='Y-m-d H:i:s' ) {
		return date( $feature, $this->created_at );
	}

	/**
	 * @param mixed $created_at
	 *
	 * @return Auto
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = date( 'Y-m-d H:i:s', $created_at );

		return $this;
	}

	/**
	 * @param string $feature
	 *
	 * @return mixed
	 */
	public function getImportedAt( $feature='Y-m-d H:i:s' ) {
		return date($feature, $this->imported_at);
	}

	/**
	 * @param mixed $imported_at
	 *
	 * @return Auto
	 */
	public function setImportedAt( $imported_at ) {
		$this->imported_at = date( 'Y-m-d H:i:s', $imported_at );

		return $this;
	}

	/**
	 * @param string $feature
	 *
	 * @return mixed
	 */
	public function getUpdatedAt( $feature='Y-m-d H:i:s' ) {
		return date($feature, $this->updated_at);
	}

	/**
	 * @param mixed $updated_at
	 *
	 * @return Auto
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = date( 'Y-m-d H:i:s', $updated_at );

		return $this;
	}

	/**
	 * @return AutoFeature[]
	 */
	public function getFeatures() {
		return $this->features;
	}

	/**
	 * @param AutoFeature[] $features
	 *
	 * @return Auto
	 */
	public function setFeatures( $features ) {
		$this->features = $features;

		return $this;
	}

	/**
	 * @return AutoType
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param AutoType $type
	 *
	 * @return Auto
	 */
	public function setType( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return Make
	 */
	public function getMake() {
		return $this->make;
	}

	/**
	 * @param Make $make
	 *
	 * @return Auto
	 */
	public function setMake( $make ) {
		$this->make = $make;

		return $this;
	}

	/**
	 * @return Model
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param Model $model
	 *
	 * @return Auto
	 */
	public function setModel( $model ) {
		$this->model = $model;

		return $this;
	}

	public function loadFromRow( \stdClass $row )
	{
		$this
			->setId( $row->id )
			->setVin( $row->vin )
			->setMakeId( $row->make_id )
			->setModelId( $row->model_id )
			->setInventoryNumber( $row->inventory_number )
			->setYear( $row->year )
			->setOdometerReading( $row->odometer_reading )
			->setCreatedAt( $row->created_at )
			->setImportedAt( $row->imported_at )
			->setUpdatedAt( $row->updated_at );

		//TODO: Create AutoFeature and set features here
		$this->features = json_decode($row->features);
	}

	//TODO: Not tested
	public function create()
	{
		global $wpdb;

		if (
			strlen( $this->inventory_number ) > 0 &&
			strlen( $this->vin ) > 0 &&
			strlen( $this->make_id ) > 0 &&
			strlen( $this->model_id ) > 0 &&
			strlen( $this->year ) > 0 &&
			strlen( $this->odometer_reading ) > 0
		) {

			$this
				->setCreatedAt( date( 'Y-m-d H:i:s' ) )
				->setUpdatedAt( date( 'Y-m-d H:i:s' ) );

			$wpdb->insert(
				$wpdb->prefix . 'squirrels_inventory',
				array(
					'inventory_number' => $this->inventory_number,
					'vin' => $this->vin,
					'make_id' => $this->make_id,
					'model_id' => $this->model_id,
					'year' => $this->year,
					'odometer_reading' => $this->odometer_reading,
					'is_visible' => intval( $this->is_visible ),
					'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'%d',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'$d',
					'%s',
					'%s'
				)
			);

			$this->id = $wpdb->insert_id;
		}
	}

	//TODO: Not tested
	public function read()
	{
		if ( $this->id !== NULL )
		{
			global $wpdb;

			$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					`" . $wpdb->prefix . "squirrels_inventory`
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

	//TODO: Not tested
	public function update()
	{
		global $wpdb;

		if (
			strlen( $this->inventory_number ) > 0 &&
			strlen( $this->vin ) > 0 &&
			strlen( $this->make_id ) > 0 &&
			strlen( $this->model_id ) > 0 &&
			strlen( $this->year ) > 0 &&
			strlen( $this->odometer_reading ) > 0
		) {

			$this->setUpdatedAt( date( 'Y-m-d H:i:s' ) );

			$wpdb->update(
				$wpdb->prefix . 'squirrels_inventory',
				array(
					'inventory_number' => $this->inventory_number,
					'vin' => $this->vin,
					'make_id' => $this->make_id,
					'model_id' => $this->model_id,
					'year' => $this->year,
					'odometer_reading' => $this->odometer_reading,
					'is_visible' => intval( $this->is_visible ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'id' => $this->id
				),
				array(
					'%d',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'$d',
					'%s'
				),
				array(
					'%d'
				)
			);

			$this->id = $wpdb->insert_id;
		}
	}

	//TODO: Not tested
	public function delete()
	{
		global $wpdb;

		$wpdb->delete(
			$wpdb->prefix . 'squirrels_inventory',
			array(
				'id' => $this->id
			),
			array(
				'%d'
			)
		);
	}
}