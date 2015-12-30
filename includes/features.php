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

<div class="wrap">

	<?php if ( $action == 'add' ) { ?>

		<h1>
			<?php echo __( 'Add', 'squirrels_inventory' ); ?>
			<?php echo __( 'Feature', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>
		</h1>

		<table class="form-table">
			<tr>
				<th>
					<label for="squirrels-feature-title">
						<?php echo __( 'Feature', 'squirrels_inventory' ); ?>:
					</label>
				</th>
				<td>
					<input name="title" id="squirrels-feature-title" placeholder="ex: Transmission">
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
						<option value="0">
							<?php echo __( 'Yes', 'squirrels_inventory' ); ?>
							/
							<?php echo __( 'No', 'squirrels_inventory' ); ?>
						</option>
						<option value="1">
							<?php echo __( 'Custom Options', 'squirrels_inventory' ); ?>
						</option>
					</select>
				</td>
			</tr>
		</table>

	<?php } elseif ( $action == 'edit' ) { ?>

		<h1>
			<?php echo __( 'Edit', 'squirrels_inventory' ); ?>
			<?php echo __( 'Feature', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>
		</h1>

	<?php } elseif ( $action == 'delete' ) { ?>

		<h1>
			<?php echo __( 'Delete', 'squirrels_inventory' ); ?>
			<?php echo __( 'Feature', 'squirrels_inventory' ); ?>
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				<?php echo __( 'Cancel', 'squirrels_inventory' ); ?>
			</a>
		</h1>

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
