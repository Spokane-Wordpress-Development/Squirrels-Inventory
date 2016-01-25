<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 1/24/16
 * Time: 4:07 PM
 */

/** @var \SquirrelsInventory\Auto[] $autos */
$autos = $this->getCurrentInventory();

?>

<?php if (count($autos) == 0) { ?>

	<p>
		No inventory is currently available. Please check back later.
	</p>

<?php } else { ?>

	<table>
		<?php foreach ($autos as $auto) { ?>
			<tr>
				<td valign="top">
					<img src="<?php echo plugins_url(); ?>/squirrels_inventory/images/photo_coming_soon.jpg">
				</td>
				<td valign="top">
					<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?sq_action=auto&sq_data=<?php echo $auto->getId(); ?>">
						<?php echo $auto->getYear(); ?>
						<?php echo $auto->getMake()->getTitle(); ?>
						<?php echo $auto->getModel()->getTitle(); ?>
					</a>
					<?php if (strlen($auto->getVin()) > 0) { ?>
						<br>VIN: <?php echo $auto->getVin(); ?>
					<?php } ?>
					<?php if (strlen($auto->getOdometerReading()) > 0) { ?>
						<br>ODO: <?php echo number_format($auto->getOdometerReading()); ?>
					<?php } ?>
				</td>
				<td valign="top">
					<?php if ($auto->getPrice() === NULL || $auto->getPrice() == 0) { ?>
						Call For Pricing
					<?php } else { ?>
						$<?php echo number_format($auto->getPrice(), 2); ?>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>

<?php } ?>
