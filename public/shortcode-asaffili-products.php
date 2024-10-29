<?php

class asaffili_shortcodes_products
{
	public function __construct()
	{
		add_shortcode('asaffili_products_page',array($this,'shortcode_asaffili_products_page'));
		add_shortcode('asaffili_products',array($this,'shortcode_asaffili_products'));
		
		add_filter( 'the_content', array($this,'asaffili_filter_the_content')); 
	}
	
	

	function asaffili_filter_the_content( $content )
	{
 		if( is_singular('asaffili_products') && in_the_loop() && is_main_query() )
 		{
    		global $post;		
			ob_start();	
			//Statistik setzen		
			update_post_meta($post->ID, "_asaffili_products_stat_view", intval($post->_asaffili_products_stat_view)+1);	
			include ASAFFILI_PLUGIN_DIR . 'templates/single-asaffili-products.php';							
			return ob_get_clean();        
    	}        
    	return $content;
	}




	function shortcode_asaffili_products_page($atts)
	{
		if(isset($_REQUEST['asaffili_search_value']))$asaffili_search_value=sanitize_text_field($_REQUEST['asaffili_search_value']);else $asaffili_search_value="";
		
		if(isset($_REQUEST['cat_id']))$cat_id=sanitize_text_field($_REQUEST['cat_id']);else
		if(isset($atts['cat_id']))$cat_id=$atts['cat_id'];else $cat_id=0;
	
			
		$asaffili_options_products=get_option('asaffili_options_products');
		if(isset($asaffili_options_products['asaffili_options_products_count_entries_listings']))$posts_per_page=$asaffili_options_products['asaffili_options_products_count_entries_listings'];else $posts_per_page=15;
	
	
	
		$obj = get_queried_object();
	
	
		$paged = ( get_query_var('pagenum') ) ? get_query_var('pagenum') : 1;
		$page = ( get_query_var('page') ) ? get_query_var('page') : 1;
	
		if(isset($_REQUEST['pagenum']))$paged=sanitize_text_field($_REQUEST['pagenum']);else $paged=1;	
		$offset = ($paged - 1) * $posts_per_page;
	
	
	
	
		if(isset($_GET['pagenum']))$paged=sanitize_text_field($_GET['pagenum']);else $paged=1;
			
	
		$term_slug = get_query_var( 'asfirms_products_category' );
	
		if($cat_id>0)
		{	
			$args = array(
 				'post_type' => 'asaffili_products',		
 				'posts_per_page' => $posts_per_page, 		
 				'paged' => $paged,
 				'offset' => $offset,
 				'tax_query' =>  array(
				array(
				'taxonomy' => 'asaffili_product_category',
				'field'    => 'id',
				'terms'    => $cat_id,
				'include_children' => true
			),
			),
			);
		}else
		{
			$args = array(
 			'post_type' => 'asaffili_products',		
 			'posts_per_page' => $posts_per_page, 		
 			'paged' => $paged,
 			'offset' => $offset, 			
			);
		}	
		
		$asaffili_options_search=get_option('asaffili_options_search');
		if(isset($asaffili_options_search['asaffili_options_search_save_search']))$asaffili_options_search_save_search=$asaffili_options_search['asaffili_options_search_save_search'];else $asaffili_options_search_save_search=0;
		
		if($asaffili_search_value!="")
		{
			$args['s']=$asaffili_search_value;
			if($asaffili_options_search_save_search==1)
			{			
				//Suche speichern
				global $wpdb;
				$sql="select post_id from ".$wpdb->prefix."postmeta where meta_key='_asaffili_search_suchbegriff' and meta_value='".$asaffili_search_value."'";			
				$result=$wpdb->get_row($sql);			
				if(!$result)			
				{				
					//Einfügen
					// Gather post data.
					$my_post = array(
    				'post_title'    => $asaffili_search_value,    				
    				'post_status'   => 'draft',
    				'post_type'		=> 'asaffili_search'
					); 
					// Insert the post into the database.				
					$temp_post_id=wp_insert_post( $my_post );
					update_post_meta($temp_post_id, "_asaffili_search_suchbegriff", $asaffili_search_value);
					//EMail an Admin
					if(isset($asaffili_options_search['asaffili_options_search_save_search_email']))$asaffili_options_search_save_search_email=$asaffili_options_search['asaffili_options_search_save_search_email'];else $asaffili_options_search_save_search_email=0;
					if($asaffili_options_search_save_search_email!="")
					{
						$body="Es wurde eine neue Suche gespeichert: ".$asaffili_search_value;
						wp_mail($asaffili_options_search_save_search_email,"Neue Suche",$body);
					}
				}	
			}
		}	
		
		$custom_query = new WP_Query($args); 				
		if ( $custom_query->have_posts()) : 
		
			ob_start();				
		  	include ASAFFILI_PLUGIN_DIR . 'templates/shortcode-asaffili-products-grid.php';
			return ob_get_clean();
		endif;	
	
	}
	
	
	
	
	function shortcode_asaffili_products($atts)
	{
		
		if(isset($atts['cat_id']))$cat_id=$atts['cat_id'];else $cat_id=0;
		if(isset($atts['count']))$count=$atts['count'];else $count=8;
	
		$asaffili_options_products=get_option('asaffili_options_products');
		if(isset($asaffili_options_products['asaffili_options_products_count_entries_listings']))$posts_per_page=$asaffili_options_products['asaffili_options_products_count_entries_listings'];else $posts_per_page=15;
	
	
		
		$args = array(
 			'post_type' => 'asaffili_products',		
 			'posts_per_page' => $count, 		
 			'paged' => 0,
 			 			
			);
			
	
		$custom_query = new WP_Query($args); 				
		if ( $custom_query->have_posts()) : 
		
			ob_start();				
						
			?>
			
			<div id="post-wrapper" class="asaffili-grid-container asaffili-grid-container-4">

			<?php
		  	while ( $custom_query->have_posts() ) : $custom_query->the_post();?>
			<div class="asaffili-grid-item">
				
			<?php
			$post=get_post();
		
			if($post->_asaffili_image!="")
			{?>
				<div class="asaffili-grid-item-image">
					<a href="<?php echo esc_url( get_permalink())?>">
					<img src="<?php echo esc_url($post->_asaffili_image);?>"/>
					</a>
				</div>
			<?php
			}	
			?>
				
			<div class="asaffili-grid-item-info">
				<?php the_title( sprintf( '<a href="%s" rel="bookmark" class="asaffili-grid-item-title">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php
				if($post->_asaffili_preis_old>0 and $post->_asaffili_preis!=$post->_asaffili_preis_old)
				{
					?><div class="asaffili-grid-preis"><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;");?><span><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_old))." &euro;";?></span></div><?php
				}else
				{
					?><div class="asaffili-grid-preis"><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;");?></div><?php	
				}
						
				if($post->_asaffili_preis_g>0)
				{
					?><div class="asaffili-grid-preis-g"><span>Grundpreis:</span><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_g))." &euro;");?></div>						<?php
				}
				?>
				<div class="asaffili-grid-shipping"><span>Versand:</span><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_shipping))." &euro;");?></div>
			</div>
			</div>
			<?php endwhile; ?>

			</div>
			<?php
	
			return ob_get_clean();
		endif;	
	
	}
	
	
}

$asaffili_shortcodes_products_obj = new asaffili_shortcodes_products();
