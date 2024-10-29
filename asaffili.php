<?php
/**
Plugin Name: asAffili
Plugin URI: https://www.asuess.de/asaffili/asaffili-plugin/
Description: asAffili is an affiliate tool. It allows you to import csv data feeds provided by affiliate networks.
Version: 1.1.1
Author: alexandersuess
Author URI:  https://www.asuess.de
License:     GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: asaffili
Domain Path: /languages
*/



if(!defined('ABSPATH'))exit;

// Path to the plugin directory
if( ! defined( 'ASAFFILI_PLUGIN_DIR' ) ) {
    define( 'ASAFFILI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// URL of the plugin
if( ! defined( 'ASAFFILI_PLUGIN_URL' ) ) {
    define( 'ASAFFILI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


include_once("admin/admin.php");

include_once("admin/products.php");
include_once("admin/imports.php");
include_once("admin/options.php");

//include_once("public/functions.php");
//include_once("public/view-asaffili-single.php");

include_once("public/shortcode-asaffili-products.php");


register_activation_hook( __FILE__, 'asaffili_plugin_activate');

function asaffili_plugin_activate()
{
	
		
	global $wpdb;
	$table_name = $wpdb->prefix.'asaffili_catid';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
   		//table not in database. Create new table
   		$charset_collate = $wpdb->get_charset_collate();
 
   		$sql = "CREATE TABLE $table_name (
          		id bigint NOT NULL AUTO_INCREMENT,
	          	cat varchar(1024),
    	      	cat_id bigint default 0,
        	  	shop_id bigint default 0,
          		created datetime,
          		import_id int default 0,
          		PRIMARY KEY id (id)
     		) $charset_collate;";
   		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   		dbDelta( $sql );
   		
	}
	else
	{
		mail("alex@mansu.de","test","vorhanden");
	}
	
	//Upload.Verzeichnis erstellen
	$upload_dir=wp_upload_dir();		
	$pfad=$upload_dir['basedir']."/asaffili";	
	$result=@mkdir($pfad,0700);
		
		
		
}	

