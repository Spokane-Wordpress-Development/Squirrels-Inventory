<?php

/**
 * Plugin Name: Squirrels Inventory
 * Plugin URI: http://www.ibrake4squirrels.com
 * Description: Auto Inventory Plug-In
 * Version: 1.0.0
 * Author: Tony DeStefano, Ethan Federman
 * Text Domain:
 * Domain Path:
 * Network:
 * License: GPL2
 */

require_once ( 'classes/Controller.php' );
require_once ( 'classes/Auto.php' );
require_once ( 'classes/AutoFeature.php' );
require_once ( 'classes/AutoType.php' );
require_once ( 'classes/Feature.php' );
require_once ( 'classes/FeatureOption.php' );
require_once ( 'classes/Make.php' );
require_once ( 'classes/Model.php' );

$squirrel = new \SquirrelsInventory\Controller;

add_action( 'init', array( $squirrel, 'init' ) );
add_action( 'init', array( $squirrel, 'createPostTypes' ) );

if ( is_admin() )
{
	add_action( 'admin_menu', array( $squirrel, 'addMenus') );
}

