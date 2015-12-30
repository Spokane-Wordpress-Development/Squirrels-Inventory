<?php

global $post;
$custom = get_post_custom( $post->ID );
$make_id = ( array_key_exists( 'make_id', $custom ) ) ? $custom[ 'make_id' ][0] : '';
$makes = \SquirrelsInventory\Make::getAllMakes();

?>

<table class="form-table">
	<tr>
		<th>
			<label for="squirrels-model-make-id">
				<?php echo __( 'Choose a Make', 'squirrels_inventory' ); ?>:
			</label>
		</th>
		<td>
			<select name="make_id" id="squirrels-model-make-id">
				<option value="">
					N/A
				</option>
				<?php foreach ($makes as $id => $make) { ?>
					<option value="<?php echo $id; ?>"<?php if ($make_id == $id) { ?> selected="selected"<?php } ?>>
						<?php echo $make->getTitle(); ?>
					</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>
			<label for="squirrels-model-new-make">
				<?php echo __( 'Or add a new Make', 'squirrels_inventory' ); ?>:
			</label>
		</th>
		<td>
			<input name="make" id="squirrels-model-new-make" placeholder="ex: Ford">
		</td>
	</tr>
</table>
