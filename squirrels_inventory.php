<?php

/**
 * Plugin Name: Squirrels Auto Inventory
 * Plugin URI: http://www.spokanewp.com/portfolio
 * Description: A simple and lightweight auto inventory system perfect for showcasing your cars online.
 * Author: Spokane WordPress Development
 * Author URI: http://www.spokanewp.com
 * Version: 1.0.0
 * Text Domain: squirrels_inventory
 *
 * Copyright 2016 Spokane WordPress Development
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package SquirrelsInventory
 * @author Spokane WordPress Development
 * @version 1.0.0
 */

require_once ( 'classes/Controller.php' );
require_once ( 'classes/CustomPostType.php' );
require_once ( 'classes/Auto.php' );
require_once ( 'classes/AutoFeature.php' );
require_once ( 'classes/AutoType.php' );
require_once ( 'classes/Feature.php' );
require_once ( 'classes/FeatureOption.php' );
require_once ( 'classes/Make.php' );
require_once ( 'classes/Model.php' );
require_once ( 'classes/FeatureTable.php' );
require_once ( 'classes/AutoTable.php' );
require_once ( 'classes/Image.php' );

/** controller object */
$squirrel = new \SquirrelsInventory\Controller;

/** Activate */
register_activation_hook( __FILE__, array( $squirrel, 'activate') );

/** Initialize any variables that the plugin needs */
add_action( 'init', array( $squirrel, 'init' ) );

/** Create any custom post types */
add_action( 'init', array( $squirrel, 'createPostTypes' ) );

/** Capture form post */
add_action ( 'init', array( $squirrel, 'formCapture' ) );

/** Register Query Vars */
add_filter( 'query_vars', array( $squirrel, 'queryVars') );

/** Register shortcode */
add_shortcode ( 'squirrels_inventory', array( $squirrel, 'shortCode') );

/** Only run these hooks if logged into the admin screen */
if ( is_admin() )
{
	/** Add main menu and sub-menus */
	add_action( 'admin_menu', array( $squirrel, 'addMenus') );

	/** Enqueue admin scripts */
	add_action( 'admin_enqueue_scripts', array( $squirrel, 'enqueueAdminScripts' ) );

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

	add_action( 'wp_ajax_squirrels_feature_save', function() use ($squirrel) {

		if( $_REQUEST['id'] != 0 )
		{
			$response['success'] = $squirrel->editFeature();
		}
		else
		{
			$response['success'] = $squirrel->createFeature();
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit;

	} );

	add_action( 'wp_ajax_squirrels_feature_delete', function() use ($squirrel) {

		header( 'Content-Type: application/json' );
		echo json_encode( array('success' => $squirrel->deleteFeature() ) );
		exit;

	} );

	add_action( 'wp_ajax_squirrels_inventory_add', function() use ( $squirrel ) {

		if( $_REQUEST[ 'id' ] == 0 )
		{
			$response = $squirrel->addToInventory();
		}
		else
		{
			$response = $squirrel->editInventory();
		}

		echo $response;
		exit;

	} );

	add_action( 'wp_ajax_squirrels_inventory_delete', function() use ($squirrel) {

		header( 'Content-Type: application/json' );
		echo json_encode( array('success' => $squirrel->deleteFromInventory() ) );
		exit;

	} );
}