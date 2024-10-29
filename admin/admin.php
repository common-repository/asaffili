<?php


	
class asaffili_admin
{
	public function __construct()
	{
		add_action('admin_menu',array($this,'admin_menu'));
		add_action('wp_enqueue_scripts', array($this,'asaffili_include_styles'));
		
		
		
		add_action('plugins_loaded',array($this,'asaffili_language_init'));
				
	}
	
	function asaffili_language_init()
	{
		
		$plugin_dir = '/asaffili/languages/';
		load_plugin_textdomain('asaffili',false,$plugin_dir);
	}




	function asaffili_dashboard_output()
	{
		echo esc_html("<h2>asAffili-Dashboard</h2>\n");
		
		global $wpdb;		
		$sql="select count(*) as anzahl from ".$wpdb->prefix."posts where post_type='asaffili_products'";
		$result=$wpdb->get_row($sql);
		echo esc_html_x('Count products: ','','asaffili').$result->anzahl;
	}
	
	public function admin_menu()
	{
		
		add_menu_page(
		'asAffili',
		'asAffili',
		'manage_options',
		'asaffili',
		array($this,'asaffili_dashboard_output'),
		'',
		2);
	
	
		add_submenu_page(
		'asaffili',
		__('Products','asaffili'),
		__('Products','asaffili'),
		'manage_options',
		'edit.php?post_type=asaffili_products',
		'');
	
	
		add_submenu_page(
		'asaffili',
		__('Product Category','asaffili'),
		__('Product Category','asaffili'),
		'manage_options',
		'edit-tags.php?taxonomy=asaffili_product_category',
		'');
	
	
		add_submenu_page(
		'asaffili',
		__('Imports','asaffili'),
		__('Imports','asaffili'),
		'manage_options',
		'edit.php?post_type=asaffili_imports',
		'');
		
		
	
		add_submenu_page(
		'asaffili',
		__('Options','asaffili'),
		__('Options','asaffili'),
		'manage_options',
		'asaffili_optionen',
		array('asaffili_admin_options','asaffili_options_page_output'));
	}


	function asaffili_include_styles()
	{
		wp_register_style( 'asaffili_css', ASAFFILI_PLUGIN_URL . 'assets/css/asaffili.css' );
		wp_enqueue_style( 'asaffili_css' );
	}


	

}

$asaffili_admin_obj = new asaffili_admin();		