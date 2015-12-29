<?php

/** @var \SquirrelsInventory\Feature[] $features */
$features = \SquirrelsInventory\Feature::getAllFeatures();

$table = new \SquirrelsInventory\FeatureTable();
$table->prepare_items();

?>

<div class="wrap">

	<h1>
		Features &amp; Options
		<a href="admin.php?page=squirrels_features&action=add" class="page-title-action">Add New</a>
	</h1>

	<?php $table->display(); ?>

</div>