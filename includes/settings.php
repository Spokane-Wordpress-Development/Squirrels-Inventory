<?php

/** @var \SquirrelsInventory\Controller $this */

?>

<div class="wrap">

	<h1>Squirrels Inventory <?php echo __( 'Settings', 'squirrels_inventory'); ?></h1>

	<form method="post" action="options.php">

		<?php

		settings_fields( 'squirrels_inventory_settings' );
		do_settings_sections( 'squirrels_inventory_settings' );

		?>

		<table class="form-table">
			<thead>
				<tr>
					<th></th>
					<th><?php echo __( 'Current Value', 'squirrels_inventory'); ?></th>
					<th><?php echo __( 'Change To', 'squirrels_inventory'); ?></th>
				</tr>
			</thead>
			<tr valign="top">
				<th scope="row">
					<label for="squirrels_inventory_date_format">
						<?php echo __( 'Date Format', 'squirrels_inventory'); ?>
					</label>
				</th>
				<td>"<?php echo \SquirrelsInventory\Controller::getDateFormat(); ?>"</td>
				<td>
					<select name="squirrels_inventory_date_format" id="squirrels_inventory_date_format">
						<?php foreach ( $this->getDateFormats() as $format ) { ?>
							<option value="<?php echo $format; ?>"<?php if ( $format == \SquirrelsInventory\Controller::getDateFormat() ) { ?> selected<?php } ?>>
								<?php echo $format; ?>
							</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="squirrels_inventory_mileage_label">
						<?php echo __( 'Mileage Label', 'squirrels_inventory'); ?>
					</label>
				</th>
				<td>"<?php echo \SquirrelsInventory\Controller::getMileageLabel(); ?>"</td>
				<td>
					<input type="text" name="squirrels_inventory_mileage_label" id="squirrels_inventory_mileage_label" value="<?php echo \SquirrelsInventory\Controller::getMileageLabel(); ?>">
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
	
	<h1><?php echo __( 'Shortcode', 'squirrels_inventory'); ?></h1>

	<h3><?php echo __( 'Add this shortcode to a page to insert the inventory search and display', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory]

	<h3><?php echo __( 'Specify how many vehicles to show per page', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory search="Off" per_page="25"]

	<h3><?php echo __( 'Turn off the search', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory search="Off"]

	<h3><?php echo __( 'Turn off the inventory (only show search fields - good for sidebar placement)', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory inventory="Off"]

	<h3><?php echo __( 'Specify what page your inventory is on (useful if you want to direct to another page)', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory page="http://mydomain.com/my-page"]

	<h3><?php echo __( 'You can also add a filter to the shortcode to show only featured items', 'squirrels_inventory'); ?>:</h3>

	[squirrels_inventory featured="True"]

</div>