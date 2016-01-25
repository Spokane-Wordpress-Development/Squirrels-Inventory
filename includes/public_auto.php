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

	<table style="border:0;">
		<tr>
			<td valign="top" style="border:0; width:75%">

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

			</td>
			<td valign="top" style="border:0; width:25%">

				<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg"><br>
				<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg"><br>
				<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg"><br>
				<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg"><br>
				<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg"><br>

			</td>
		</tr>
	</table>

<?php } ?>
