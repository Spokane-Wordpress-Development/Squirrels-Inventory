<?php

$auto_table = new \SquirrelsInventory\AutoTable();
$auto_table->prepare_items();

$action = 'list';
if ( isset( $_GET[ 'action' ] ) )
{
	switch( $_GET[ 'action' ] )
	{
		case 'add':
		case 'edit':
		case 'delete':
			$action = $_GET[ 'action' ];
	}
}

?>

<div class="wrap">

	<?php if( $action == 'add' ) { ?>
		<?php
		$models = \SquirrelsInventory\Model::getAllModels();
		$features = \SquirrelsInventory\Feature::getAllFeatures();
		$auto_types = \SquirrelsInventory\AutoType::getAllAutoTypes();
		?>

		<h1>
			<?php echo __( 'Add to Inventory', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>

			<button class="page-title-action" id="squirrels-inventory-add"><?php echo __('Add', 'squirrels_inventory'); ?></button>
		</h1>

		<div class="update-nag">
			<?php _e('If your make and model are not in the drop-down list, you can add them using the "New Make" and "New Model" fields below.', 'squirrels_inventory'); ?>
		</div>

		<form autocomplete="off">
			<table class="form-table">
				<tr>
					<th>
						<label for="squirrels_auto_type">Type:</label>
					</th>
					<td>
						<select id="squirrels_auto_type">
							<?php foreach ($auto_types as $auto_type) { ?>
								<option value="<?php echo $auto_type->getId(); ?>"<?php if ($auto_type->getTitle() == 'Car') { ?> selected<?php } ?>>
									<?php echo $auto_type->getTitle(); ?>
								</option>
							<?php } ?>
						</select>
					</td>
					<th>
						<label for="squirrels_vehicle">Make/Model:</label>
					</th>
					<td>
						<select id="squirrels_vehicle">
							<?php $count = 0; $previous_make = ''; ?>
							<?php foreach( $models as $model ) { ?>

							<?php if( $model->getMake()->getTitle() != $previous_make ) { ?>

							<?php if( $count != 0 ) { ?>
								</optgroup>
							<?php } ?>

							<optgroup label="<?php echo $model->getMake()->getTitle(); ?>">

								<?php $previous_make = $model->getMake()->getTitle(); ?>

								<?php } ?>

								<option value="<?php echo $model->getId(); ?>"><?php echo $model->getTitle(); ?></option>

								<?php $count++; ?>

								<?php } ?>

							</optgroup>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_new_make">Create New Make:</label>
					</th>
					<td>
						<input id="squirrels_new_make" />
					</td>
					<th>
						<label for="squirrels_new_model">Create New Model:</label>
					</th>
					<td>
						<input id="squirrels_new_model" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_is_visible">Visible:</label>
					</th>
					<td>
						<select id="squirrels_is_visible">
							<option value="1" selected>Yes</option>
							<option value="0">No</option>
						</select>
					</td>
					<th>
						<label for="squirrels_is_featured">Featured:</label>
					</th>
					<td>
						<select id="squirrels_is_featured">
							<option value="1">Yes</option>
							<option value="0" selected>No</option>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_inventory_number">Inventory Number:</label>
					</th>
					<td>
						<input id="squirrels_inventory_number" />
					</td>
					<th>
						<label for="squirrels_vin">Vin:</label>
					</th>
					<td>
						<input id="squirrels_vin" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="squirrels_year">Year:</label>
					</th>
					<td>
						<input id="squirrels_year" />
					</td>
					<th>
						<label for="squirrels_odometer_reading">Odometer:</label>
					</th>
					<td>
						<input id="squirrels_odometer_reading" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_exterior">Exterior:</label>
					</th>
					<td>
						<input id="squirrels_exterior" placeholder="ex: Red" />
					</td>
					<th>
						<label for="squirrels_interior">Interior:</label>
					</th>
					<td>
						<input id="squirrels_interior" placeholder="ex: Black or Leather" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_price">Price:</label>
					</th>
					<td>
						<input id="squirrels_price" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_description">Description:</label>
					</th>
					<td colspan="2">
						<textarea id="squirrels_description" style="width:100%; height:200px;"></textarea>
					</td>
				</tr>
				<tr>
					<th><label>Features:</label></th>
					<td colspan="3">

						<table id="squirrels-feature-table">
							<thead>
								<tr>
									<th></th>
									<th>Title</th>
									<th>Value</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Add a <a href="?page=squirrels_features" target="_blank">Pre-Defined Feature</a>:</td>
									<td>
										<select id="pre-defined-feature-title">
											<?php foreach ($features as $feature) { ?>
												<option value="<?php echo $feature->getId(); ?>">
													<?php echo $feature->getTitle(); ?>
												</option>
											<?php } ?>
										</select>
									</td>
									<td>
										<select id="pre-defined-feature-value"></select>
									</td>
									<td>
										<input id="submit-pre-defined-feature" class="button-primary" value="Add" type="button" />
									</td>
								</tr>
								<tr>
									<td>Add a New Feature:</td>
									<td>
										<input id="new-feature-title" placeholder="Ex: Transmission">
									</td>
									<td>
										<input id="new-feature-value" placeholder="Ex: Automatic">
									</td>
									<td>
										<input id="submit-new-feature" class="button-primary" value="Add" type="button" />
									</td>
								</tr>
							</tbody>
						</table>

					</td>
				</tr>
				<tr>
					<th><label>Images:</label></th>
					<td colspan="3">
						<p>
							<input id="squirrels-upload-images" class="button-primary" value="Add Images" type="button" />
						</p>
					</td>
				</tr>
			</table>

			<div id="squirrels-images-admin"></div>

		</form>

		<script>

			var features = [];
			var images = [];

			var feature_options = [];
			<?php foreach ($features as $feature) { ?>
				var options = [];
				<?php if ($feature->isTrueFalse()) { ?>
					options.push('Yes');
					options.push('No');
				<?php } else { ?>
					<?php foreach ($feature->getOptions() as $option) { ?>
						options.push('<?php echo str_replace("'", "\'", $option->getTitle()); ?>');
					<?php } ?>
				<?php } ?>
				feature_options.push({
					id: <?php echo $feature->getId(); ?>,
					title: '<?php echo str_replace("'", "\'", $feature->getTitle()); ?>',
					options: options
				});
			<?php } ?>

		</script>

		<p><em>(Hint: The "add" button is at the top!)</em></p>

	<?php } elseif( $action == 'edit' ) { ?>

		<?php

		$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? abs(round($_GET['id'])) : 0;
		$auto = new \SquirrelsInventory\Auto( $id );

		?>

		<h1>
			<?php echo __( 'Edit Inventory', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>
			<?php if ($auto->getId() !== NULL) { ?>
				<button class="page-title-action" id="squirrels-inventory-edit"><?php echo __('Update', 'squirrels_inventory'); ?></button>
				<button class="page-title-action" id="squirrels-inventory-delete"><?php echo __('Delete', 'squirrels_inventory'); ?></button>
			<?php } ?>
		</h1>

		<?php if ($auto->getId() === NULL) { ?>

			<div class="error">
				<?php _e('The item you are trying to edit is not currently available.', 'squirrels_inventory'); ?>
			</div>

		<?php } else { ?>

			<?php

			$models = \SquirrelsInventory\Model::getAllModels();
			$features = \SquirrelsInventory\Feature::getAllFeatures();
			$auto_types = \SquirrelsInventory\AutoType::getAllAutoTypes();

			?>

			<form autocomplete="off">
			<table class="form-table">
				<tr>
					<th>
						<label for="squirrels_auto_type">Type:</label>
					</th>
					<td>
						<select id="squirrels_auto_type">
							<?php foreach ($auto_types as $auto_type) { ?>
								<option value="<?php echo $auto_type->getId(); ?>"<?php if ($auto_type->getId() == $auto->getTypeId()) { ?> selected<?php } ?>>
									<?php echo $auto_type->getTitle(); ?>
								</option>
							<?php } ?>
						</select>
					</td>
					<th>
						<label for="squirrels_vehicle">Make/Model:</label>
					</th>
					<td>
						<select id="squirrels_vehicle">
							<?php $count = 0; $previous_make = ''; ?>
							<?php foreach( $models as $model ) { ?>

							<?php if( $model->getMake()->getTitle() != $previous_make ) { ?>

							<?php if( $count != 0 ) { ?>
								</optgroup>
							<?php } ?>

							<optgroup label="<?php echo $model->getMake()->getTitle(); ?>">

								<?php $previous_make = $model->getMake()->getTitle(); ?>

								<?php } ?>

								<option value="<?php echo $model->getId(); ?>"<?php if ($model->getId() == $auto->getModelId()) { ?> selected<?php } ?>>
									<?php echo $model->getTitle(); ?>
								</option>

								<?php $count++; ?>

								<?php } ?>

							</optgroup>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_new_make">Create New Make:</label>
					</th>
					<td>
						<input id="squirrels_new_make" />
					</td>
					<th>
						<label for="squirrels_new_model">Create New Model:</label>
					</th>
					<td>
						<input id="squirrels_new_model" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_is_visible">Visible:</label>
					</th>
					<td>
						<select id="squirrels_is_visible">
							<option value="1"<?php if ($auto->isVisible()) { ?> selected<?php } ?>>Yes</option>
							<option value="0"<?php if (!$auto->isVisible()) { ?> selected<?php } ?>>No</option>
						</select>
					</td>
					<th>
						<label for="squirrels_is_featured">Featured:</label>
					</th>
					<td>
						<select id="squirrels_is_featured">
							<option value="1"<?php if ($auto->isFeatured()) { ?> selected<?php } ?>>Yes</option>
							<option value="0"<?php if (!$auto->isFeatured()) { ?> selected<?php } ?>>No</option>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_inventory_number">Inventory Number:</label>
					</th>
					<td>
						<input id="squirrels_inventory_number" value="<?php echo htmlspecialchars($auto->getInventoryNumber()); ?>" />
					</td>
					<th>
						<label for="squirrels_vin">Vin:</label>
					</th>
					<td>
						<input id="squirrels_vin" value="<?php echo htmlspecialchars($auto->getVin()); ?>" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="squirrels_year">Year:</label>
					</th>
					<td>
						<input id="squirrels_year" value="<?php echo htmlspecialchars($auto->getYear()); ?>" />
					</td>
					<th>
						<label for="squirrels_odometer_reading">Odometer:</label>
					</th>
					<td>
						<input id="squirrels_odometer_reading" value="<?php echo htmlspecialchars(number_format($auto->getOdometerReading())); ?>" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_exterior">Exterior:</label>
					</th>
					<td>
						<input id="squirrels_exterior" placeholder="ex: Red" value="<?php echo htmlspecialchars($auto->getExterior()); ?>" />
					</td>
					<th>
						<label for="squirrels_interior">Interior:</label>
					</th>
					<td>
						<input id="squirrels_interior" placeholder="ex: Black or Leather" value="<?php echo htmlspecialchars($auto->getInterior()); ?>" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_price">Price:</label>
					</th>
					<td>
						<input id="squirrels_price" value="$<?php echo htmlspecialchars(number_format($auto->getPrice())); ?>" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="squirrels_description">Description:</label>
					</th>
					<td colspan="2">
						<textarea id="squirrels_description" style="width:100%; height:200px;"><?php echo htmlspecialchars($auto->getDescription()); ?></textarea>
					</td>
				</tr>
				<tr>
					<th><label>Features:</label></th>
					<td>
						<select class="squirrels-feature" id="squirrels_feature">
							<?php foreach($features as $feature){ ?>
								<option value="<?php echo $feature->getId(); ?>"><?php echo $feature->getTitle(); ?></option>
							<?php } ?>
						</select>

						<select class="squirrels-feature-options" id="squirrels_feature_options">
						</select>

						<input id="squirrels-add-feature" class="button-primary" value="More" type="button" />
					</td>
				</tr>
			</table>
			</form>

			<p>
				<input id="squirrels-upload-images" class="button-primary" value="Upload Images" type="button" />
			</p>

			<div id="squirrels-images-admin">
				<?php if ($auto->getImageCount() > 0) { ?>
					<?php foreach ($auto->getImages() as $image) { ?>
						<div class="image-<?php echo $image->getMediaId(); ?><?php if ($image->isDefault()) {?> default<?php } ?>">
							<img src="<?php echo $image->getUrl(); ?>" width="250"><br>
							<span class="remove" data-id="<?php echo $image->getMediaId(); ?>">remove</span>
							|
							<span class="default" data-id="<?php echo $image->getMediaId(); ?>">make default</span></div>
					<?php } ?>
				<?php } ?>
			</div>

			<script>
				var features = <?php echo json_encode($features); ?>;
				var images = [];
				<?php if ($auto->getImageCount() > 0) { ?>
					<?php foreach ($auto->getImages() as $image) { ?>
						images.push({
							id: <?php echo $image->getId(); ?>,
							media_id: <?php echo $image->getMediaId(); ?>,
							url: '<?php echo $image->getUrl(); ?>',
							def: <?php echo ($image->isDefault()) ? 1 : 0; ?>
						});
					<?php } ?>
				<?php } ?>
			</script>

			<p><em>(Hint: The "update" and "delete" buttons are at the top!)</em></p>

		<?php } ?>

	<?php } elseif( $action == 'delete' ) { ?>

	<?php } else { ?>

		<h1>
			<?php echo __( 'Inventory', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>&action=add" class="page-title-action">
				<?php echo __( 'Add New', 'squirrels_inventory' ); ?>
			</a>
		</h1>

		<?php $auto_table->display(); ?>

	<?php } ?>

</div>
