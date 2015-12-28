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

/** controller object */
$squirrel = new \SquirrelsInventory\Controller;

/** Activate */
register_activation_hook( __FILE__, array( $squirrel, 'activate') );

/** Initialize any variables that the plugin needs */
add_action( 'init', array( $squirrel, 'init' ) );

/** Create any custom post types */
add_action( 'init', array( $squirrel, 'createPostTypes' ) );

/** Only run these hooks if logged into the admin screen */
if ( is_admin() )
{
	/** Add main menu and sub-menus */
	add_action( 'admin_menu', array( $squirrel, 'addMenus') );

	/** Change default placeholders */
	add_filter( 'enter_title_here', array( $squirrel, 'changeDefaultPlaceholders' ) );

	/** Create custom attributes for Model post types */
	add_action( 'add_meta_boxes_' . \SquirrelsInventory\Model::CUSTOM_POST_TYPE, array( $squirrel, 'customModelMeta' ) );

	/** Save Model meta */
	add_action( 'save_post', array( $squirrel, 'saveModelMeta' ), 10, 2 );

	/** Makes the Models page sort by make and then model */
	add_action( 'admin_menu', array( $squirrel, 'setMakeColumnSortable') );

	/** Add Make column to Model list */
	add_filter( 'manage_' . \SquirrelsInventory\Model::CUSTOM_POST_TYPE . '_posts_columns', array( $squirrel, 'addMakeColumnToModelList' ) );
	add_action( 'manage_' . \SquirrelsInventory\Model::CUSTOM_POST_TYPE . '_posts_custom_column' , array( $squirrel, 'customModelColumns' ) );
}

