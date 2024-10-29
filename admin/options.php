<?php

class asaffili_admin_options
{
	public function __construct()
	{
		add_action('admin_init',array($this,'asaffili_options_page_output_register'));
	}	
	
	public function asaffili_options_page_output_register()
	{
		
		
		register_setting('asaffili_options_allgemein_group','asaffili_options_allgemein');	
		add_settings_section('allgemein',__('General','asaffili'),array($this,'asaffili_options_allgemein_render'),'asaffili_options_allgemein_group');
		add_settings_field('asaffili_options_use_bootstrap',__('Load Bootstrap','asaffili'),array($this,'asaffili_options_allgemein_select_feld_render'),'asaffili_options_allgemein_group','allgemein',array('id'=>'asaffili_options_use_bootstrap'));
	
		
		register_setting('asaffili_options_products_group','asaffili_options_products');	
		add_settings_section('products',__('Products','asaffili'),array($this,'asaffili_options_products_render'),'asaffili_options_products_group');
		add_settings_field('asaffili_options_products_pages_listings',__('Page for Products','asaffili'),array($this,'asaffili_select_pages_products_feld_render'),'asaffili_options_products_group','products',array('id'=>'asaffili_options_products_pages_listings'));
		add_settings_field('asaffili_options_products_count_col_listings',__('Count Columns','asaffili'),array($this,'asaffili_select_count_col_products_feld_render'),'asaffili_options_products_group','products',array('id'=>'asaffili_options_products_count_col_listings'));
		add_settings_field('asaffili_options_products_count_entries_listings',__('Count Products per Page','asaffili'),array($this,'asaffili_select_count_entries_products_feld_render'),'asaffili_options_products_group','products',array('id'=>'asfirms_options_products_count_entries_listings'));
		add_settings_field('asaffili_options_products_affili_note',__('Affili-Note','asaffili'),array($this,'asaffili_textarea_products_feld_render'),'asaffili_options_products_group','products',array('id'=>'asaffili_options_products_affili_note'));
		
		register_setting('asaffili_options_shops_group','asaffili_options_shops');	
		add_settings_section('shops',__('Shops','asaffilipro'),array($this,'asaffili_options_shops_render'),'asaffili_options_shops_group');
		add_settings_field('asaffili_options_shops_pages_listings',__('Page for Shops','asaffili'),array($this,'asaffili_select_pages_shops_feld_render'),'asaffili_options_shops_group','shops',array('id'=>'asaffili_options_shops_pages_listings'));
		add_settings_field('asaffili_options_shops_count_col_listings',__('Count Columns','asaffili'),array($this,'asaffili_select_count_col_shops_feld_render'),'asaffili_options_shops_group','shops',array('id'=>'asaffili_options_shops_count_col_listings'));
		add_settings_field('asaffili_options_shops_count_entries_listings',__('Count Shops per Page','asaffili'),array($this,'asaffili_select_count_entries_shops_feld_render'),'asaffili_options_shops_group','shops',array('id'=>'asaffili_options_shops_count_entries_listings'));
		add_settings_field('asaffili_options_shops_show_cats',__('Kategorien anzeigen','asaffili'),array($this,'asaffili_select_yesno_shops_feld_render'),'asaffili_options_shops_group','shops',array('id'=>'asaffili_options_shops_show_cats'));
		add_settings_field('asaffili_options_shops_pos_cats',__('Kategorien Position','asaffili'),array($this,'asaffili_select_pos_shops_feld_render'),'asaffili_options_shops_group','shops',array('id'=>'asaffili_options_shops_pos_cats'));
		
		register_setting('asaffili_options_search_group','asaffili_options_search');	
		add_settings_section('search',__('Suchen','asaffili'),array($this,'asaffili_options_search_render'),'asaffili_options_search_group');
		add_settings_field('asaffili_options_search_pages_listings',__('Page for Search','asaffili'),array($this,'asaffili_select_pages_search_feld_render'),'asaffili_options_search_group','search',array('id'=>'asaffili_options_search_pages_listings'));
		add_settings_field('asaffili_options_search_count_col_listings',__('Count Columns','asaffili'),array($this,'asaffili_select_count_col_search_feld_render'),'asaffili_options_search_group','search',array('id'=>'asaffili_options_search_count_col_listings'));
		add_settings_field('asaffili_options_search_count_entries_listings',__('Count Search per Page','asaffili'),array($this,'asaffili_select_count_entries_search_feld_render'),'asaffili_options_search_group','search',array('id'=>'asaffili_options_search_count_entries_listings'));
		add_settings_field('asaffili_options_search_save_search',__('Produktsuchen speichern','asaffili'),array($this,'asaffili_select_yesno_search_save_search_feld_render'),'asaffili_options_search_group','search',array('id'=>'asaffili_options_search_save_search'));	
		add_settings_field('asaffili_options_search_save_search_email',__('EMail-Benachrichtigung','asaffili'),array($this,'asaffili_options_search_text_feld_render'),'asaffili_options_search_group','search',array('id'=>'asaffili_options_search_save_search_email'));	
	
	
		register_setting('asaffili_options_actions_group','asaffili_options_actions');
		add_settings_section('actions',__('Aktionen','asaffili'),array($this,'asaffili_options_actions_render'),'asaffili_options_actions_group');
		add_settings_field('asaffili_options_actions_pages_listings',__('Page for Actions','asaffili'),array($this,'asaffili_select_pages_actions_feld_render'),'asaffili_options_actions_group','actions',array('id'=>'asaffili_options_actions_pages_listings'));		
		add_settings_field('asaffili_options_actions_count_entries_listings',__('Count Actions per Page','asaffili'),array($this,'asaffili_select_count_entries_actions_feld_render'),'asaffili_options_actions_group','actions',array('id'=>'asaffili_options_actions_count_entries_listings'));
			
		register_setting('asaffili_options_actions_import_group','asaffili_options_actions_import');
		
		add_settings_section('actions_import_awin',__('Import Awin','asaffili'),array($this,'asaffili_options_actions_import_render'),'asaffili_options_actions_import_group');
		add_settings_field('asaffili_options_actions_import_awin',__('Awin verfügbar','asaffili'),array($this,'asaffili_select_yesno_actions_import_awin_feld_render'),'asaffili_options_actions_import_group','actions_import_awin',array('id'=>'asaffili_options_actions_import_awin'));
		add_settings_field('asaffili_options_actions_import_awin_url',__('Import-Url','asaffili'),array($this,'asaffili_options_actions_import_awin_url_text_feld_render'),'asaffili_options_actions_import_group','actions_import_awin',array('id'=>'asaffili_options_actions_import_awin_url'));	
	
		add_settings_section('actions_import_webgains',__('Import Webgains','asaffili'),array($this,'asaffili_options_actions_import_render'),'asaffili_options_actions_import_group');
		add_settings_field('asaffili_options_actions_import_webgains',__('webgains verfügbar','asaffili'),array($this,'asaffili_select_yesno_actions_import_webgains_feld_render'),'asaffili_options_actions_import_group','actions_import_webgains',array('id'=>'asaffili_options_actions_import_webgains'));
		add_settings_field('asaffili_options_actions_import_webgains_api',__('API-Key','asaffili'),array($this,'asaffili_options_actions_import_webgains_api_text_feld_render'),'asaffili_options_actions_import_group','actions_import_webgains',array('id'=>'asaffili_options_actions_import_webgains_api'));	
		add_settings_field('asaffili_options_actions_import_webgains_campaign',__('Kampagnie','asaffili'),array($this,'asaffili_options_actions_import_webgains_campaign_text_feld_render'),'asaffili_options_actions_import_group','actions_import_webgains',array('id'=>'asaffili_options_actions_import_webgains_campaign'));	
	
		
		register_setting('asaffili_options_optionen_group','asaffili_options_optionen');	
		add_settings_section('asaffili_sidebars',__('Sidebars','asaffili'),array($this,'asaffili_options_optionen_render'),'asaffili_options_optionen_group');
		add_settings_field('asaffili_options_optionen_sidebar_before_title',__('HTML before title','asaffili'),array($this,'asaffili_options_optionen_text_feld_render'),'asaffili_options_optionen_group','asaffili_sidebars',array('id'=>'asaffili_options_optionen_sidebar_before_title'));
		add_settings_field('asaffili_options_optionen_sidebar_after_title',__('HTML after title','asaffili'),array($this,'asaffili_options_optionen_text_feld_render'),'asaffili_options_optionen_group','asaffili_sidebars',array('id'=>'asaffili_options_optionen_sidebar_after_title'));
		add_settings_field('asaffili_options_optionen_sidebar_before_widget',__('HTML before widget','asaffili'),array($this,'asaffili_options_optionen_text_feld_render'),'asaffili_options_optionen_group','asaffili_sidebars',array('id'=>'asaffili_options_optionen_sidebar_before_widget'));
		add_settings_field('asaffili_options_optionen_sidebar_after_widget',__('HTML after widget','asaffili'),array($this,'asaffili_options_optionen_text_feld_render'),'asaffili_options_optionen_group','asaffili_sidebars',array('id'=>'asaffili_options_optionen_sidebar_after_widget'));
	
		add_settings_section('asaffili_options_note',__('Affiliate-Hinweis','asaffili'),array($this,'asaffili_options_optionen_render'),'asaffili_options_optionen_group');
		add_settings_field('asaffili_options_affili_note',__('Affiliate-Note','asaffili'),array($this,'asaffili_textarea_options_affili_note_feld_render'),'asaffili_options_optionen_group','asaffili_options_note',array('id'=>'asaffili_options_affili_note'));
		
	}




	//affili_note options

	public function asaffili_textarea_options_affili_note_feld_render($args)
	{
		$option=get_option('asaffili_options_optionen');
		
		if($option[$args['id']]!="")$option_value=$option[$args['id']];else $option_value="";	
		?>
		<textarea name="asaffili_options_optionen[<?php echo $args['id'];?>]" cols="70" rows="5"><?php echo $option_value;?></textarea>
		<?php
	}
	
	
	
	
	
	public function asaffili_options_optionen_text_feld_render($args)
	{
		$option=get_option('asaffili_options_optionen');	
		?>
		<input type="text" size="70" name="asaffili_options_optionen[<?php echo $args['id'];?>]" value='<?php echo $option[$args['id']];?>'></input>
		<?php
	}


	public function asaffili_options_allgemein_select_feld_render($args)
	{
		$options=get_option('asaffili_options_allgemein');
		?>
		<select name="asaffili_options_allgemein[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 1) $option.=' selected';
  			$option .= '>';
			$option .= esc_html_x('Yes','asaffili');
			$option .= '</option>';
			
			$option .= '<option value="0"';
  			if($options[$args['id']] == 0) $option.=' selected';
  			$option .= '>';
			$option .= esc_html_x('No','asaffili');
			$option .= '</option>';			
			echo $option;  			
 		?>
		</select>
		<?php
	}




	public function asaffili_select_pages_products_feld_render($args)
	{
		$options=get_option('asaffili_options_products');	
		
		?>
		<select name="asaffili_options_products[<?php echo $args['id'];?>]">	
	
 		<option value="">
		<?php echo esc_attr( esc_html_e( 'Select Page','asaffili' ) ); ?></option> 
 		<?php 
  			$pages = get_pages(); 
  			foreach ( $pages as $page ) {
  				$option = '<option value="' . get_page_link( $page->ID ) . '"';
  				if($options[$args['id']] == get_page_link($page->ID)) $option.=' selected';
  				$option .= '>';
				$option .= esc_html($page->post_title);
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>	
		<?php
		echo "<span>".esc_html("Shortcode: [asaffili_products_page]")."</span>";
	}

	public function asaffili_select_count_col_products_feld_render($args)
	{
		$options=get_option('asaffili_options_products');	
		
		?>
		<select name="asaffili_options_products[<?php echo $args['id'];?>]">	
	
 		 
 		<?php 
  			for($i=1;$i<5;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}


	//affili_note

	public function asaffili_textarea_products_feld_render($args)
	{
		$option=get_option('asaffili_options_products');	
		?>
		<textarea name="asaffili_options_products[<?php echo $args['id'];?>]" cols="70" rows="5"><?php echo $option[$args['id']];?></textarea>
		<?php
	}
	
	
	
	
	
	public function asaffili_select_count_entries_products_feld_render($args)
	{
		$options=get_option('asaffili_options_products');	
		
		?>
		<select name="asaffili_options_products[<?php echo $args['id'];?>]">	
	
 		<?php	 
  			for($i=1;$i<50;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}




	public function asaffili_select_pages_shops_feld_render($args)
	{
		$options=get_option('asaffili_options_shops');	
		
		?>
		<select name="asaffili_options_shops[<?php echo $args['id'];?>]">	
	
 		<option value="">
		<?php echo esc_attr( esc_html_e( 'Select Page','asaffili' ) ); ?></option> 
 		<?php 
  			$pages = get_pages(); 
  			foreach ( $pages as $page ) {
  				$option = '<option value="' . get_page_link( $page->ID ) . '"';
  				if($options[$args['id']] == get_page_link($page->ID)) $option.=' selected';
  				$option .= '>';
				$option .= esc_html($page->post_title);
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>	
		<?php
	}


	public function asaffili_select_yesno_shops_feld_render($args)
	{
		$options=get_option('asaffili_options_shops');
		
		?>
		<select name="asaffili_options_shops[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 1) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('Yes','asaffili'));
			$option .= '</option>';
			
			$option .= '<option value="0"';
  			if($options[$args['id']] == 0) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('No','asaffili'));
			$option .= '</option>';			
			
			echo $option;  			
 		?>
		</select>
		<?php
	}
	
	
	public function asaffili_select_pos_shops_feld_render($args)
	{
		$options=get_option('asaffili_options_shops');
		
		?>
		<select name="asaffili_options_shops[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 2) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('top','asaffili'));
			$option .= '</option>';
			
			$option .= '<option value="2"';
  			if($options[$args['id']] == 2) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('bottom','asaffili'));
			$option .= '</option>';			
			
			echo $option;  			
 		?>
		</select>
		<?php
	}
	
	public function asaffili_select_count_col_shops_feld_render($args)
	{
		$options=get_option('asaffili_options_shops');	
		
		?>
		<select name="asaffili_options_shops[<?php echo $args['id'];?>]">	
	
 		 
 		<?php 
  			for($i=1;$i<5;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}

	public function asaffili_select_count_entries_shops_feld_render($args)
	{
		$options=get_option('asaffili_options_shops');	
		
		?>
		<select name="asaffili_options_shops[<?php echo $args['id'];?>]">	
	
 		<?php	 
  			for($i=1;$i<50;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}






	public function asaffili_select_pages_search_feld_render($args)
	{
		$options=get_option('asaffili_options_search');	
		
		?>
		<select name="asaffili_options_search[<?php echo $args['id'];?>]">	
	
 		<option value="">
		<?php echo esc_attr( esc_html_e( 'Select Page','asaffili' ) ); ?></option> 
 		<?php 
  			$pages = get_pages(); 
  			foreach ( $pages as $page ) {
  				$option = '<option value="' . get_page_link( $page->ID ) . '"';
  				if($options[$args['id']] == get_page_link($page->ID)) $option.=' selected';
  				$option .= '>';
				$option .= esc_html($page->post_title);
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>	
		<?php
	}

	public function asaffili_select_count_col_search_feld_render($args)
	{
		$options=get_option('asaffili_options_search');	
		
		?>
		<select name="asaffili_options_search[<?php echo $args['id'];?>]">	
	
 		 
 		<?php 
  			for($i=1;$i<5;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}

	public function asaffili_select_count_entries_search_feld_render($args)
	{
		$options=get_option('asaffili_options_search');	
		
		?>
		<select name="asaffili_options_search[<?php echo $args['id'];?>]">	
	
 		<?php	 
  			for($i=1;$i<50;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}

	public function asaffili_options_search_text_feld_render($args)
	{
		$option=get_option('asaffili_options_search');
		if(isset($option[$args['id']]))$value=$option[$args['id']];else $value="";	
		?>
		<input type="text" size="70" name="asaffili_options_search[<?php echo $args['id'];?>]" value='<?php echo esc_attr($value);?>'></input>
		<?php
	}
	
	
	
	public function asaffili_select_yesno_search_save_search_feld_render($args)
	{
		$options=get_option('asaffili_options_search');
		
		?>
		<select name="asaffili_options_search[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 1) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('Yes','asaffili'));
			$option .= '</option>';
			
			$option .= '<option value="0"';
  			if($options[$args['id']] == 0) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('No','asaffili'));
			$option .= '</option>';			
			
			echo $option;  			
 		?>
		</select>
		<?php
	}




	public function asaffili_select_pages_actions_feld_render($args)
	{
		$options=get_option('asaffili_options_actions');	
		
		?>
		<select name="asaffili_options_actions[<?php echo $args['id'];?>]">	
	
 		<option value="">
		<?php echo esc_attr( esc_html_e( 'Select Page','asaffili' ) ); ?></option> 
 		<?php 
  			$pages = get_pages(); 
  			foreach ( $pages as $page ) {
  				$option = '<option value="' . get_page_link( $page->ID ) . '"';
  				if($options[$args['id']] == get_page_link($page->ID)) $option.=' selected';
  				$option .= '>';
				$option .= esc_html($page->post_title);
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>	
		<?php
		echo "<span>".esc_html("Shortcode: [asaffili_actions_page]")."</span>";
	}
	
	


	public function asaffili_select_count_entries_actions_feld_render($args)
	{
		$options=get_option('asaffili_options_actions');	
		
		?>
		<select name="asaffili_options_actions[<?php echo $args['id'];?>]">	
	
 		<?php	 
  			for($i=1;$i<50;$i++)
  			{
  				$option = '<option value="' . $i . '"';
  				if($options[$args['id']] == $i) $option.=' selected';
  				$option .= '>';
				$option .= $i;
				$option .= '</option>';
				echo $option;
  			}
 		?>
		</select>
		<?php
	}
	
	
	
	

	public function asaffili_select_yesno_actions_import_awin_feld_render($args)
	{
		$options=get_option('asaffili_options_actions_import');
		
		?>
		<select name="asaffili_options_actions_import[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 1) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('Yes','asaffili'));
			$option .= '</option>';
			
			$option .= '<option value="0"';
  			if($options[$args['id']] == 0) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('No','asaffili'));
			$option .= '</option>';			
			
			echo $option;  			
 		?>
		</select>
		<?php
	}
	
	
	public function asaffili_options_actions_import_awin_url_text_feld_render($args)
	{
		$option=get_option('asaffili_options_actions_import');
		
		if($option[$args['id']]!="")$option_value=$option[$args['id']];else $option_value="";	
		?>
		<textarea name="asaffili_options_actions_import[<?php echo $args['id'];?>]" cols="70" rows="5"><?php echo $option_value;?></textarea>
		<?php
	}
	
	
	

	public function asaffili_select_yesno_actions_import_webgains_feld_render($args)
	{
		$options=get_option('asaffili_options_actions_import');
		
		?>
		<select name="asaffili_options_actions_import[<?php echo $args['id'];?>]">	
	 		 
 		<?php   			
  			$option = '<option value="1"';
  			if($options[$args['id']] == 1) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('Yes','asaffili'));
			$option .= '</option>';
			
			$option .= '<option value="0"';
  			if($options[$args['id']] == 0) $option.=' selected';
  			$option .= '>';
			$option .= esc_html(__('No','asaffili'));
			$option .= '</option>';			
			
			echo $option;  			
 		?>
		</select>
		<?php
	}
	
	
	public function asaffili_options_actions_import_webgains_api_text_feld_render($args)
	{
		$option=get_option('asaffili_options_actions_import');
		
		if($option[$args['id']]!="")$option_value=$option[$args['id']];else $option_value="";	
		?>
		<input type="text" name="asaffili_options_actions_import[<?php echo $args['id'];?>]" size="80" value="<?php echo $option_value;?>"/>
		
		<?php
	}
	
	
	public function asaffili_options_actions_import_webgains_campaign_text_feld_render($args)
	{
		$option=get_option('asaffili_options_actions_import');
		
		if($option[$args['id']]!="")$option_value=$option[$args['id']];else $option_value="";	
		?>
		<input type="text" name="asaffili_options_actions_import[<?php echo $args['id'];?>]" size="80" value="<?php echo $option_value;?>"/>
		
		<?php
	}
	
	
	public function asaffili_options_allgemein_render()
	{
	
	}

	public function asaffili_options_products_render()
	{
	
	}

	public function asaffili_options_shops_render()
	{
	
	}
	
	public function asaffili_options_optionen_render()
	{	
	
	}

	public function asaffili_options_search_render()
	{
	
	}
	
	public function asaffili_options_actions_render()
	{
	
	}
	
	public function asaffili_options_actions_import_render()
	{
	
	}
	

	public static function asaffili_options_page_output()
	{
		
		if(isset($_REQUEST['tab']))$active_tab=sanitize_text_field($_REQUEST['tab']);else $active_tab="allgemein";
	
		?>
	
		<h2><?php echo esc_html_e('Options','asaffili');?></h2>
		
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab<?php if($active_tab=='allgemein')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=allgemein"><?php echo esc_html_e('General','asaffili');?></a>		
		<a class="nav-tab nav-tab<?php if($active_tab=='products')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=products"><?php echo esc_html_e('Products','asaffili');?></a>
		<a class="nav-tab nav-tab<?php if($active_tab=='shops')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=shops"><?php echo esc_html_e('Shops','asaffili');?></a>
		<a class="nav-tab nav-tab<?php if($active_tab=='search')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=search"><?php echo esc_html_e('Suchen','asaffili');?></a>
		<a class="nav-tab nav-tab<?php if($active_tab=='actions')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=actions"><?php echo esc_html_e('Aktionen','asaffili');?></a>
		<a class="nav-tab nav-tab<?php if($active_tab=='actions-import')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=actions-import"><?php echo esc_html_e('Aktionen-Import','asaffili');?></a>
		<a class="nav-tab nav-tab<?php if($active_tab=='optionen')echo "-active";?>" href="admin.php?page=asaffili_optionen&tab=optionen"><?php echo esc_html_e('Options','asaffili');?></a>
		</h2>		
		
		
		<form action='options.php' method='post'>
		<?php
		if($active_tab=='allgemein')
		{
			
			settings_fields('asaffili_options_allgemein_group');
			do_settings_sections('asaffili_options_allgemein_group');
			submit_button();
			?>
			<input type="hidden" name="tab" value="allgemein">
			<?php
		}	
		
			
		
		if($active_tab=='products')
		{
			
			settings_fields('asaffili_options_products_group');
			do_settings_sections('asaffili_options_products_group');
			submit_button();
			?>
			<input type="hidden" name="tab" value="products">
			<?php
		}	
		
		if($active_tab=='shops')
		{
			if(class_exists("asaffilipro_admin_shops"))
			{				
				settings_fields('asaffili_options_shops_group');
				do_settings_sections('asaffili_options_shops_group');
				submit_button();
				?>
				<input type="hidden" name="tab" value="shops">
				<?php
			}else
			{
				echo "<p>".esc_html_x('You must use the premium-version asaffilipro.','','asaffili')."</p>";
			}
		}	
		
		
		
		if($active_tab=='actions')
		{
			if(class_exists("asaffilipro_admin_actions"))
			{				
				settings_fields('asaffili_options_actions_group');
				do_settings_sections('asaffili_options_actions_group');
				submit_button();
				?>
				<input type="hidden" name="tab" value="actions">
				<?php
			}else
			{
				echo "<p>".esc_html_x('You must use the premium-version asaffilipro.','','asaffili')."</p>";
			}
		}	
		
		if($active_tab=='actions-import')
		{
			if(class_exists("asaffilipro_admin_actions_import"))
			{				
				settings_fields('asaffili_options_actions_import_group');
				do_settings_sections('asaffili_options_actions_import_group');
				submit_button();
				?>
				<input type="hidden" name="tab" value="actions-import">
				<?php
			}else
			{
				echo "<p>".esc_html_x('You must use the premium-version asaffilipro.','','asaffili')."</p>";
			}
		}	
		
		
		
		if($active_tab=='search')
		{
			if(class_exists("asaffilipro_admin_search"))
			{				
				settings_fields('asaffili_options_search_group');
				do_settings_sections('asaffili_options_search_group');
				submit_button();			
				?>
				<input type="hidden" name="tab" value="search">
				<?php
			}else
			{
				echo "<p>".esc_html_x('You must use the premium-version asaffilipro.','','asaffili')."</p>";
			}
		}	
		
		
		if($active_tab=='optionen')
		{
			
			settings_fields('asaffili_options_optionen_group');
			do_settings_sections('asaffili_options_optionen_group');
			submit_button();
			?>
			<input type="hidden" name="tab" value="optionen">
			<?php
		}	
		
		?>
		
		
		</form>
		<?php
	}

}

$asaffili_admin_options_obj = new asaffili_admin_options();