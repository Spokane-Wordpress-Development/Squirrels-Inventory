<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 1/26/16
 * Time: 1:09 PM
 */

namespace SquirrelsInventory;

class Image {

	private $id;
	private $inventory_id;
	private $media_id;
	private $url;
	private $is_default = FALSE;
	private $created_at;
	private $updated_at;

	/**
	 * Image constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL )
	{
		$this
			->setId($id)
			->read();
	}

	public function read()
	{
		global $wpdb;

		if ( $this->id !== NULL )
		{
			$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					" . $wpdb->prefix . "squirrels_images
				WHERE
					`id` = %d",
				$this->id
			);

			if ( $row = $wpdb->get_row( $sql ) )
			{
				$this->loadFromRow( $row );
			}
			else
			{
				$this->id = NULL;
			}
		}
	}

	public function create()
	{
		global $wpdb;

		if ( $this->inventory_id !== NULL )
		{
			$this
				->setCreatedAt( time() )
				->setUpdatedAt( time() );

			$wpdb->insert(
				$wpdb->prefix . 'squirrels_images',
				array(
					'inventory_id' => $this->inventory_id,
					'media_id' => $this->media_id,
					'url' => $this->url,
					'is_default' => ( $this->is_default ) ? 1 : 0,
					'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'%d',
					'%d',
					'%s',
					'%d',
					'%s',
					'%s'
				)
			);

			$this->id = $wpdb->insert_id;
		}
	}

	public function update()
	{
		global $wpdb;

		if ( $this->id !== NULL )
		{
			$this->setUpdatedAt( time() );

			$wpdb->update(
				$wpdb->prefix . 'squirrels_images',
				array(
					'inventory_id' => $this->inventory_id,
					'media_id' => $this->media_id,
					'url' => $this->url,
					'is_default' => ( $this->is_default ) ? 1 : 0,
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'id' => $this->id
				),
				array(
					'%d',
					'%d',
					'%s',
					'%d',
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
				$wpdb->prefix . 'squirrels_images',
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
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Image
	 */
	public function setId( $id ) {
		$this->id = (is_numeric($id)) ? abs(round($id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getInventoryId() {
		return $this->inventory_id;
	}

	/**
	 * @param mixed $inventory_id
	 *
	 * @return Image
	 */
	public function setInventoryId( $inventory_id ) {
		$this->inventory_id = (is_numeric($inventory_id)) ? abs(round($inventory_id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMediaId() {
		return $this->media_id;
	}

	/**
	 * @param mixed $media_id
	 *
	 * @return Image
	 */
	public function setMediaId( $media_id ) {
		$this->media_id = (is_numeric($media_id)) ? abs(round($media_id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param mixed $url
	 *
	 * @return Image
	 */
	public function setUrl( $url ) {
		$this->url = $url;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isDefault() {
		return $this->is_default;
	}

	/**
	 * @param boolean $is_default
	 *
	 * @return Image
	 */
	public function setIsDefault( $is_default ) {
		$this->is_default = ($is_default == 1 || $is_default === TRUE) ? TRUE : FALSE;

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
	 * @return Image
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
	 * @return Image
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = (is_numeric($updated_at) || $updated_at === NULL) ? $updated_at : strtotime( $updated_at );

		return $this;
	}

	public function loadFromRow( \stdClass $row ) {

		$this
			->setId( $row->id )
			->setInventoryId( $row->inventory_id )
			->setMediaId( $row->media_id)
			->setUrl( $row->url )
			->setIsDefault( $row->is_default )
			->setCreatedAt( $row->created_at )
			->setUpdatedAt( $row->updated_at );
	}

	/**
	 * @param $inventory_id
	 *
	 * @return Image[]
	 */
	public static function getInventoryImages( $inventory_id ) {

		global $wpdb;
		$images = array();

		$sql = $wpdb->prepare("
			SELECT
				*
			FROM
				" . $wpdb->prefix . "squirrels_images
			WHERE
				`inventory_id` = %d
			ORDER BY
				is_default DESC,
				id ASC",
			$inventory_id
		);

		$results = $wpdb->get_results( $sql );
		foreach( $results as $result )
		{
			$image = new Image();
			$image->loadFromRow( $result );
			$images[$image->getId()] = $image;
		}

		return $images;
	}
}