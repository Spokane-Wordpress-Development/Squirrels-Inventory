<?php

/** @var \SquirrelsInventory\Feature[] $features */
$features = \SquirrelsInventory\Feature::getAllFeatures();

$table = new \SquirrelsInventory\FeatureTable();
$table->prepare_items();

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

<style>
	.squirrels-feature-custom-option-remove{
		cursor: pointer;
		color: red;
	}

	.squirrels-feature-custom-option-add{
		cursor: pointer;
		color: green;
	}

	.squirrels-feature-custom-option-remove,
	.squirrels-feature-custom-option-add{
		margin-top: 5px;
		margin-left: 5px;
	}
</style>

<div class="wrap">

	<?php if ( $action == 'add' ) { ?>

		<h1>
			<?php echo __( 'Add Feature', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>

			<button class="page-title-action" id="squirrels-feature-add"><?php echo __('Add'); ?></button>
		</h1>

		<table class="form-table">
			<tr>
				<th>
					<label for="squirrels-feature-title">
						<?php echo __( 'Feature', 'squirrels_inventory' ); ?>:
					</label>
				</th>
				<td>
					<input name="title" id="squirrels-feature-title" placeholder="<?php echo __('ex: Transmission'); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="squirrels-feature-type">
						<?php echo __( 'Options', 'squirrels_inventory' ); ?>:
					</label>
				</th>
				<td colspan="3">
					<select id="squirrels-feature-type">
						<option value="0">
							<?php echo __( 'Yes', 'squirrels_inventory' ); ?>
							/
							<?php echo __( 'No', 'squirrels_inventory' ); ?>
						</option>
						<option value="1" selected>
							<?php echo __( 'Custom Options', 'squirrels_inventory' ); ?>
						</option>
					</select>
				</td>
			</tr>
			<tr id="squirrels-feature-custom-options-table">
				<th></th>
				<td colspan="3">
					<table>
						<thead>
							<tr>
								<th>Default</th>
								<th>Feature Name</th>
								<th></th>
							</tr>
						</thead>
						<tr>
							<td></td>
							<td><input type="text" class="squirrels-feature-custom-option-input" /></td>
							<td><input class="button-primary squirrels-feature-custom-option-add" value="Add" type="button" /></td>
						</tr>
						<tbody id="squirrels-feature-custom-options-wrapper">
						</tbody>
					</table>
				</td>
			</tr>
		</table>

	<?php } elseif ( $action == 'edit' ) { ?>

		<?php $feature = new \SquirrelsInventory\Feature( $_REQUEST[ 'id' ] ); ?>

		<h1>
			<?php echo __( 'Edit Feature', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>

			<button id="squirrels-feature-edit" class="page-title-action"><?php echo __( 'Update' ); ?></button>
		</h1>

		<table class="form-table">
			<tr>
				<th>
					<label for="squirrels-feature-title">
						<?php echo __( 'Feature', 'squirrels_inventory' ); ?>:
					</label>
				</th>
				<td>
					<input name="title" id="squirrels-feature-title" placeholder="<?php echo __('ex: Transmission'); ?>" value="<?php echo $feature->getTitle(); ?>">
				</td>
			</tr>
			<tr>
				<th>
					<label for="squirrels-feature-type">
						<?php echo __( 'Options', 'squirrels_inventory' ); ?>:
					</label>
				</th>
				<td>
					<select id="squirrels-feature-type">
						<option value="0" <?php echo ( $feature->isTrueFalse() ) ? 'selected' : ''; ?>>
							<?php echo __( 'Yes', 'squirrels_inventory' ); ?>
							/
							<?php echo __( 'No', 'squirrels_inventory' ); ?>
						</option>
						<option value="1" <?php echo ( !$feature->isTrueFalse() ) ? 'selected' : ''; ?>>
							<?php echo __( 'Custom Options', 'squirrels_inventory' ); ?>
						</option>
					</select>
				</td>
			</tr>
			<tr id="squirrels-feature-custom-options-table">
				<th></th>
				<td colspan="3">
					<table>
						<thead>
						<tr>
							<th>Default</th>
							<th>Feature Name</th>
							<th></th>
						</tr>
						</thead>
						<tr>
							<td></td>
							<td><input type="text" class="squirrels-feature-custom-option-input" /></td>
							<td><input class="button-primary squirrels-feature-custom-option-add" value="Add" type="button" /></td>
						</tr>
						<tbody id="squirrels-feature-custom-options-wrapper">
						<?php if( !$feature->isTrueFalse() ) { ?>

							<?php foreach( $feature->getOptions() as $index => $option ) { ?>

								<tr class="squirrels-feature-custom-option">
									<td><input type="radio" name="squirrels-feature-default" <?php echo ( $option->isDefault() ) ? 'checked' : ''; ?> /></td>
									<td><p><?php echo $option->getTitle(); ?></p></td>
									<td><input type="button" class="button-secondary squirrels-feature-custom-option-remove" value="Remove" /></td>
								</tr>

							<?php } ?>

						<?php } ?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>

	<?php } elseif ( $action == 'delete' ) { ?>

		<?php $feature = new \SquirrelsInventory\Feature( $_REQUEST[ 'id' ] ); ?>

		<h1>
			<?php echo __( 'Delete Feature', 'squirrels_inventory' ); ?>

			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>

			<button id="squirrels-feature-delete" class="page-title-action"><?php echo __( 'Delete' ); ?></button>
		</h1>

		<table class="form-table">
			<tr>
				<th><?php echo __( 'Feature' ); ?></th>
				<td><?php echo $feature->getTitle(); ?></td>
			</tr>

			<?php if ( $feature->isTrueFalse() ) { ?>

				<tr>
					<th><?php echo __( 'Option' ); ?></th>
					<td><?php echo __( 'Yes / No' ); ?></td>
				</tr>

			<?php } else { ?>

				<tr>
					<th><?php echo __( 'Option' ); ?></th>
				</tr>

				<?php foreach( $feature->getOptions() as $option ) { ?>
					<tr>
						<th></th>
						<td><?php echo $option->getTitle(); echo ($option->isDefault()) ? ' - ' . __('Default') : ''; ?></td>
					</tr>

				<?php } ?>

			<?php } ?>

		</table>

	<?php } else { ?>

		<h1>
			<?php echo __( 'Features', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>&action=add" class="page-title-action">
				<?php echo __( 'Add', 'squirrels_inventory' ); ?>
				<?php echo __( 'New', 'squirrels_inventory' ); ?>
			</a>
		</h1>

		<?php $table->display(); ?>

	<?php } ?>

</div>
