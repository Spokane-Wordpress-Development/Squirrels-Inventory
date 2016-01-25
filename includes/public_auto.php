<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 1/24/16
 * Time: 4:07 PM
 */

$id = (is_numeric($this->data)) ? abs(round($this->data)) : 0;
$auto = new \SquirrelsInventory\Auto($id);

?>

<?php if ($auto->getId() == 0 || $auto->getId() === NULL) { ?>

	<p>
		The inventory you are looking for is no longer available.
		Please check back later.
	</p>

<?php } else { ?>

	<div class="squirrels-auto">

		<h3>
			<?php echo $auto->getYear(); ?>
			<?php echo $auto->getMake()->getTitle(); ?>
			<?php echo $auto->getModel()->getTitle(); ?>
			<?php if ($auto->getPrice() === NULL || $auto->getPrice() == 0) { ?>
				(Call for Price)
			<?php } else { ?>
				($<?php echo number_format($auto->getPrice(), 2); ?>)
			<?php } ?>
		</h3>
		<p><?php echo $auto->getDescription(); ?></p>

		<div class="row">
			<div class="col-md-6">
				<ul>
					<?php if (strlen($auto->getVin()) > 0) { ?>
						<li>VIN: <?php echo $auto->getVin(); ?></li>
					<?php } ?>
					<?php if (strlen($auto->getOdometerReading()) > 0) { ?>
						<li>ODO: <?php echo number_format($auto->getOdometerReading()); ?></li>
					<?php } ?>
					<?php for ($x=1; $x<=10; $x++) { ?>
						<li>Sample Feature <?php echo $x; ?></li>
					<?php } ?>
				</ul>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
					<div class="col-md-6">
						<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
					</div>
				</div>

			</div>
		</div>

	</div>

<?php } ?>
