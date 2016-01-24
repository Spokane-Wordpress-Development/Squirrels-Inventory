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

		<table class="form-table">
			<tr>
				<th>
					<label for="squirrels_auto_type">Type:</label>
				</th>
				<td>
					<select id="squirrels_auto_type">
						<?php foreach ($auto_types as $auto_type) { ?>
							<option value="<?php echo $auto_type->getId(); ?>">
								<?php echo $auto_type->getTitle(); ?>
							</option>
						<?php } ?>
					</select>
				</td>
				<th>
					<label for="squirrels_is_visible">Visible:</label>
				</th>
				<td>
					<select id="squirrels_is_visible">
						<option value="1" selected>Yes</option>
						<option value="0">No</option>
					</select>
				</td>
			</tr>
			<tr>
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
				<th>
					<label for="squirrels_new_make">New Make:</label>
				</th>
				<td>
					<input id="squirrels_new_make" />
				</td>
			</tr>
			<tr>
				<th colspan="2">

				</th>
				<th>
					<label for="squirrels_new_model">New Model:</label>
				</th>
				<td>
					<input id="squirrels_new_model" />
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

		<script>
			var features = <?php echo json_encode($features); ?>;
		</script>

	<?php } elseif( $action == 'edit' ) { ?>

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
