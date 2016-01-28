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

	/** @var Image[] $images */
	private $images;

	private $id = 0;
	private $inventory_number;
	private $vin;
	private $type_id;
	private $make_id;
	private $model_id;
	private $year;
	private $odometer_reading;
	private $is_visible = FALSE;
	private $is_featured = FALSE;
	private $description;
	private $price;
	private $exterior;
	private $interior;
	private $created_at;
	private $imported_at;
	private $updated_at;

	/**
	 * Auto constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL )
	{
		$this
			->setId($id)
			->read();
	}

	/**
	 *
	 */
	public function loadImages()
	{
		if ($this->id !== NULL) {
			$this->setImages( Image::getInventoryImages( $this->id ) );
		}
	}

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
	 * @return Image[]
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * @param Image[] $images
	 *
	 * @return Auto
	 */
	public function setImages( $images ) {
		$this->images = $images;

		return $this;
	}

	/**
	 * @param Image $image
	 */
	public function addImage( Image $image ) {
		$this->setImage( $image->getId(), $image );
	}

	public function deleteImage( $index ) {
		if ($this->images !== NULL && array_key_exists((int)$index, $this->images)) {
			$image = $this->images[ $index ];
			$image->delete();
			unset( $this->images[ $index ] );
		}
	}

	/**
	 * @return int
	 */
	public function getImageCount() {
		return ($this->images === NULL) ? 0 : count($this->images);
	}

	/**
	 * @param $index
	 *
	 * @return null|Image
	 */
	public function getImage( $index ) {
		if (array_key_exists($index, $this->images)) {
			return $this->images[$index];
		}

		return NULL;
	}

	/**
	 * @param $index
	 * @param Image $image
	 */
	public function setImage( $index, Image $image ) {
		if ($this->images === NULL) {
			$this->images = array();
		}
		$this->images[$index] = $image;
	}

	/**
	 * @return mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param mixed $price
	 *
	 * @return Auto
	 */
	public function setPrice( $price )
	{
		$price = preg_replace( "/[^\d|\.]/", '', $price );

		$this->price = (is_numeric($price)) ? abs(round($price, 2)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getExterior() {
		return $this->exterior;
	}

	/**
	 * @param mixed $exterior
	 *
	 * @return Auto
	 */
	public function setExterior( $exterior ) {
		$this->exterior = $exterior;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getInterior() {
		return $this->interior;
	}

	/**
	 * @param mixed $interior
	 *
	 * @return Auto
	 */
	public function setInterior( $interior ) {
		$this->interior = $interior;

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
	public function getTypeId() {
		return $this->type_id;
	}

	/**
	 * @param mixed $type_id
	 *
	 * @return Auto
	 */
	public function setTypeId( $type_id ) {
		if(is_numeric($type_id) && $type_id > 0)
		{
			$this->type_id = $type_id;
		}
		else
		{
			$this->type_id = NULL;
		}

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
		$this->odometer_reading = preg_replace( "/\D/", "", $odometer_reading ); //strip out non numbers

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isVisible() {
		return $this->is_visible;
	}

	/**
	 * @param boolean $is_visible
	 *
	 * @return Auto
	 */
	public function setIsVisible( $is_visible ) {
		$this->is_visible = ($is_visible == 1 || $is_visible === TRUE) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isFeatured() {
		return $this->is_featured;
	}

	/**
	 * @param boolean $is_featured
	 *
	 * @return Auto
	 */
	public function setIsFeatured( $is_featured ) {
		$this->is_featured = ($is_featured == 1 || $is_featured === TRUE) ? TRUE : FALSE;

		return $this;
	}



	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return Auto
	 */
	public function setDescription( $description )
	{
		$this->description = $description;

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
	 * @return Auto
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
	public function getImportedAt( $format=NULL ) {
		return ($format === NULL) ? $this->imported_at : date( $format, $this->imported_at );
	}

	/**
	 * @param mixed $imported_at
	 *
	 * @return Auto
	 */
	public function setImportedAt( $imported_at ) {
		$this->imported_at = (is_numeric($imported_at) || $imported_at === NULL) ? $imported_at : strtotime( $imported_at );

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
	 * @return Auto
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = (is_numeric($updated_at) || $updated_at === NULL) ? $updated_at : strtotime( $updated_at );

		return $this;
	}

	/**
	 * @return AutoFeature[]
	 */
	public function getFeatures() {
		return ($this->features === NULL) ? array() : $this->features;
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
	 * @param AutoFeature $feature
	 */
	public function addFeature( AutoFeature $feature)
	{
		if ($this->features === NULL)
		{
			$this->features = array();
		}

		$this->features[$feature->getId()] = $feature;
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
		$this->type_id = $type->getId();

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
		$this->make_id = $make->getId();

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
		$this->model_id = $model->getId();

		return $this;
	}

	public function loadFromRow( \stdClass $row )
	{
		$this
			->setId( $row->id )
			->setTypeId( $row->type_id )
			->setVin( $row->vin )
			->setMakeId( $row->make_id )
			->setModelId( $row->model_id )
			->setInventoryNumber( $row->inventory_number )
			->setYear( $row->year )
			->setOdometerReading( $row->odometer_reading )
			->setDescription( $row->description )
			->setPrice( $row->price )
			->setIsVisible( $row->is_visible )
			->setIsFeatured( $row->is_featured )
			->setExterior( $row->exterior )
			->setInterior( $row->interior )
			->setCreatedAt( $row->created_at )
			->setImportedAt( $row->imported_at )
			->setUpdatedAt( $row->updated_at );

		if (isset($row->make))
		{
			$this->make = new Make;
			$this->make
				->setId($row->make_id)
				->setTitle($row->make);
		}

		if (isset($row->model))
		{
			$this->model = new Model;
			$this->model
				->setId($row->model_id)
				->setTitle($row->model);
		}

		if (isset($row->type))
		{
			$this->type = new AutoType;
			$this->type
				->setId($row->type_id)
				->setTitle($row->type);
		}

		$features = json_decode($row->features, TRUE);
		if (!empty($features))
		{
			foreach ($features as $f)
			{
				$feature = new AutoFeature;
				$feature
					->setId($f['id'])
					->setFeatureId($f['feature_id'])
					->setFeatureTitle($f['feature_title'])
					->setValue($f['value'])
					->setCreatedAt($f['created_at'])
					->setUpdatedAt($f['updated_at']);

				$this->addFeature($feature);
			}
		}
	}

	/**
	 * @return mixed|string|void
	 */
	public function featuresToJson()
	{
		if ($this->features === NULL || empty($this->features))
		{
			return '';
		}

		$data = array();
		foreach ($this->features as $feature)
		{
			$data[] = array(
				'id' => $feature->getId(),
				'feature_id' => $feature->getFeatureId(),
				'feature_title' => $feature->getFeatureTitle(),
				'value' => $feature->getValue(),
				'created_at' => $feature->getCreatedAt(),
				'updated_at' => $feature->getUpdatedAt()
			);
		}

		return json_encode($data);
	}

	public function create()
	{
		global $wpdb;

		if ( $this->type_id !== NULL )
		{
			$this
				->setCreatedAt( time() )
				->setUpdatedAt( time() );

			$wpdb->insert(
				$wpdb->prefix . 'squirrels_inventory',
				array(
					'price' => $this->price,
					'type_id' => $this->type_id,
					'inventory_number' => $this->inventory_number,
					'vin' => $this->vin,
					'make_id' => $this->make_id,
					'model_id' => $this->model_id,
					'year' => $this->year,
					'odometer_reading' => $this->odometer_reading,
					'is_visible' => ( $this->is_visible ) ? 1 : 0,
					'is_featured'  => ( $this->is_featured ) ? 1 : 0,
					'description' => $this->description,
					'exterior' => $this->exterior,
					'interior' => $this->interior,
					'features' => $this->featuresToJson(),
					'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'%f',
					'%d',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);

			$this->id = $wpdb->insert_id;
		}
	}

	public function read()
	{
		global $wpdb;

		if ( $this->id !== NULL )
		{
			$sql = $wpdb->prepare("
				SELECT
					p_makes.post_title AS make,
					p_models.post_title AS model,
					p_types.post_title AS `type`,
					si.*
				FROM
					" . $wpdb->prefix . "squirrels_inventory si
					JOIN " . $wpdb->prefix . "posts p_makes
						ON p_makes.id = si.make_id
					JOIN " . $wpdb->prefix . "posts p_models
						ON p_models.id = si.model_id
					JOIN " . $wpdb->prefix . "posts p_types
						ON p_types.id = si.type_id
				WHERE
					si.`id` = %d",
                $this->id
			);

			if ( $row = $wpdb->get_row( $sql ) )
			{
				$this->loadFromRow( $row );
				$this->loadImages();
			}
			else
			{
				$this->id = NULL;
			}
		}
	}

	public function update()
	{
		global $wpdb;

		if ( $this->type_id !== NULL )
		{
			$this->setUpdatedAt( time() );

			$wpdb->update(
				$wpdb->prefix . 'squirrels_inventory',
				array(
					'price' => $this->price,
					'type_id' => $this->type_id,
					'inventory_number' => $this->inventory_number,
					'vin' => $this->vin,
					'make_id' => $this->make_id,
					'model_id' => $this->model_id,
					'year' => $this->year,
					'odometer_reading' => $this->odometer_reading,
					'is_visible' => ( $this->is_visible ) ? 1 : 0,
					'is_featured' => ( $this->is_featured ) ? 1 : 0,
					'description' => $this->description,
					'exterior' => $this->exterior,
					'interior' => $this->interior,
					'features' => $this->featuresToJson(),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'id' => $this->id
				),
				array(
					'%f',
					'%d',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
		}
	}

	public function delete()
	{
		global $wpdb;

		if ($this->id !== NULL) {

			$wpdb->delete(
				$wpdb->prefix . 'squirrels_inventory',
				array(
					'id' => $this->id
				),
				array(
					'%d'
				)
			);

			$wpdb->delete(
				$wpdb->prefix . 'squirrels_images',
				array(
					'inventory_id' => $this->id
				),
				array(
					'%d'
				)
			);

			$this->id = NULL;
		}
	}
}