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

	<div class="squirrels squirrels-auto">

		<div class="row">
			<div class="col-md-12">

				<h3 class="squirrels-title">
					<?php echo ($auto->getYear() > 0) ? $auto->getYear() : ''; ?>
					<?php echo $auto->getMake()->getTitle(); ?>
					<?php echo $auto->getModel()->getTitle(); ?>
					-
					<?php if ($auto->getPrice() === NULL || $auto->getPrice() == 0) { ?>
						Call for Price
					<?php } else { ?>
						$<?php echo number_format($auto->getPrice(), 2); ?>
					<?php } ?>
				</h3>

				<p>
					<a href="<?php echo $this->base_page; ?>">View All</a>
				</p>
				<p><?php echo $auto->getDescription(); ?></p>

			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<table>
					<?php if (strlen($auto->getVin()) > 0) { ?>
						<tr>
							<th>VIN:</th>
							<td><?php echo $auto->getVin(); ?></td>
						</tr>
					<?php } ?>
					<?php if (strlen($auto->getOdometerReading()) > 0) { ?>
						<tr>
							<th>Mileage:</th>
							<td><?php echo number_format($auto->getOdometerReading()); ?></td>
						</tr>
					<?php } ?>
					<?php if (strlen($auto->getExterior()) > 0) { ?>
						<tr>
							<th>Exterior:</th>
							<td><?php echo $auto->getExterior(); ?></td>
						</tr>
					<?php } ?>
					<?php if (strlen($auto->getInterior()) > 0) { ?>
						<tr>
							<th>Interior:</th>
							<td><?php echo $auto->getInterior(); ?></td>
						</tr>
					<?php } ?>
					<?php foreach($auto->getFeatures() as $feature) { ?>
						<tr>
							<th><?php echo $feature->getFeatureTitle(); ?></th>
							<td><?php echo $feature->getValue(); ?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-md-6">
				<div class="row">
					<?php if ($auto->getImageCount() == 0) { ?>
						<?php for ($x=1; $x<=6; $x++) { ?>
							<div class="col-md-6">
								<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
							</div>
						<?php } ?>
					<?php } else { ?>
						<?php foreach ($auto->getImages() as $image) { ?>
							<div class="col-md-6">
								<a href="<?php echo $image->getUrl(); ?>" class="thickbox">
									<img src="<?php echo $image->getThumbnail(); ?>">
								</a>
							</div>
						<?php } ?>
					<?php } ?>
				</div>

			</div>
		</div>

	</div>

<?php } ?>
