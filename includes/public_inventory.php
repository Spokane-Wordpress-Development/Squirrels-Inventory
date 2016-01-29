<?php if ($this->getAttribute('search') != 'off' && !$this->has_displayed_search) { ?>

	<?php

	$this->has_displayed_search = TRUE;
	$makes = \SquirrelsInventory\Make::getAllMakes();
	$models = \SquirrelsInventory\Model::getAllModels();

	?>

	<script>

		var squirrels_makes = [];
		var squirrels_models = [];

		<?php foreach ($makes as $make) { ?>
			squirrels_makes.push({
				id: <?php echo $make->getId(); ?>,
				title: '<?php echo str_replace("'", "\'", $make->getTitle()); ?>'
			});
		<?php } ?>

		<?php foreach ($models as $model) { ?>
			squirrels_models.push({
				id: <?php echo $model->getId(); ?>,
				make_id: <?php echo $model->getMakeId(); ?>,
				title: '<?php echo str_replace("'", "\'", $model->getTitle()); ?>'
			});
		<?php } ?>

	</script>

	<div class="squirrels squirrels-search">

		<form autocomplete="off" method="post">

			<input type="hidden" name="squirrels_action" value="search">
			<input type="hidden" name="page" value="<?php echo (strlen($this->getAttribute('page'))) ? $this->getAttribute('page') : $this->base_page; ?>">

			<?php $selected_make = $this->param('make'); ?>
			<select name="make" id="squirrels-make">
				<option value="">
					Make (All)
				</option>
				<?php foreach ($makes as $make) { ?>
					<option value="<?php echo $make->getId(); ?>"<?php if ($selected_make == $make->getId()) { ?> selected<?php } ?>>
						<?php echo $make->getTitle(); ?>
					</option>
				<?php } ?>
			</select>

			<select name="model" id="squirrels-model">
				<option value="">
					Model (All)
				</option>
				<?php if ($selected_make != '') { ?>
					<?php $selected_model = $this->param('model'); ?>
					<?php foreach ($models as $model) { ?>
						<?php if ($model->getMakeId() == $selected_make) { ?>
							<option value="<?php echo $model->getId(); ?>"<?php if ($selected_model == $model->getId()) { ?> selected<?php } ?>>
								<?php echo $model->getTitle(); ?>
							</option>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</select>

			<br>

			<?php

			$selected_min = $this->param('min');
			$mins = array(0, 1000, 2000, 3000, 4000, 5000, 10000, 15000, 25000, 50000);

			?>

			<select name="min">
				<?php foreach ($mins as $min) { ?>
					<option value="<?php echo $min; ?>"<?php if ($selected_min == $min) { ?> selected<?php } ?>>
						$<?php echo number_format($min); ?> Min Price
					</option>
				<?php } ?>
			</select>

			<?php

			$selected_max = $this->param('max', 100000);
			$maxes = array(1000, 2000, 3000, 4000, 5000, 10000, 15000, 25000, 50000, 100000);

			?>

			<select name="max">
				<?php foreach ($maxes as $max) { ?>
					<option value="<?php echo $max; ?>"<?php if ($selected_max == $max) { ?> selected<?php } ?>>
						$<?php echo number_format($max); ?> Max Price
					</option>
				<?php } ?>
			</select>

			<br>

			<?php

			$selected_order = $this->param('order', 'make');
			$orders = array(
				'price_asc' => 'Price (low to high)',
				'price_desc' => 'Price (high to low)',
				'year_asc' => 'Year (low to high)',
				'year_desc' => 'Year (high to low)',
				'make' => 'Make'
			)

            ?>

			<select name="order">
				<?php foreach ($orders as $key => $val) { ?>
					<option value="<?php echo $key; ?>"<?php if ($key == $selected_order) { ?> selected<?php } ?>>
						Sort by <?php echo $val; ?>
					</option>
				<?php } ?>
			</select>

			<br>

			<button>Search</button>

		</form>

	</div>

<?php } ?>

<?php if ($this->getAttribute('inventory') != 'off' && !$this->has_displayed_inventory) { ?>

	<?php

	$this->has_displayed_inventory = TRUE;

	$make = (isset($_GET['make'])) ? $_GET['make'] : '';
	$model = (isset($_GET['model'])) ? $_GET['model'] : '';
	$min = (isset($_GET['min'])) ? $_GET['min'] : '';
	$max = (isset($_GET['max'])) ? $_GET['max'] : '';
	$order = (isset($_GET['order'])) ? $_GET['order'] : '';
	$page = (isset($_GET['page'])) ? $_GET['page'] : '';
	$featured = $this->getAttribute('featured');

	/** @var \SquirrelsInventory\Auto[] $autos */
	$autos = $this->getCurrentInventory($make, $model, $min, $max, $order, $featured, $page);

	?>

	<?php if (count($autos) == 0) { ?>

		<?php if (strlen($make.$model.$min.$max.$order) > 0) { ?>

			<p>
				Your search returned no vehicles. Please try again.
			</p>

		<?php } else  { ?>

			<p>
				More inventory coming soon! Please check back later.
			</p>

		<?php } ?>

	<?php } else { ?>

		<div class="squirrels squirrels-inventory">

			<?php foreach ($autos as $auto) { ?>
				<div class="row">
					<div class="col-md-4">
						<?php if ($auto->getImageCount() == 0) { ?>
							<img class="sq-thumb" src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
						<?php } else { ?>
							<?php foreach ($auto->getImages() as $image) { ?>
								<img class="sq-thumb" src="<?php echo $image->getUrl(); ?>">
								<?php break; ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<h3 class="squirrels-title">
							<a href="<?php echo (strlen($this->getAttribute('page'))) ? $this->getAttribute('page') : $this->base_page; ?>?sq_action=auto&sq_data=<?php echo $auto->getId(); ?>">
								<?php echo ($auto->getYear() > 0) ? $auto->getYear() : ''; ?>
								<?php echo $auto->getMake()->getTitle(); ?>
								<?php echo $auto->getModel()->getTitle(); ?>
							</a>
						</h3>
						<?php if (strlen($auto->getVin()) > 0) { ?>
							<br>VIN: <?php echo $auto->getVin(); ?>
						<?php } ?>
						<?php if (strlen($auto->getOdometerReading()) > 0) { ?>
							<br>Mileage: <?php echo number_format($auto->getOdometerReading()); ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<?php if ($auto->getPrice() === NULL || $auto->getPrice() == 0) { ?>
							Call For Pricing
						<?php } else { ?>
							$<?php echo number_format($auto->getPrice(), 2); ?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>

		</div>

	<?php } ?>

<?php } ?>
