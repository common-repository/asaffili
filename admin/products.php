<?php

class asaffili_admin_products
{
	public function __construct()
	{
		add_action('init', array($this,'post_type_asaffili_products'));	
		add_action('admin_init', array($this,'asaffili_products_metainit'));
		add_action('save_post', array($this,'asaffili_products_save_details'));	
				
	}	
		
	function post_type_asaffili_products()
	{
    	register_post_type('asaffili_products',
                array('label' => __('asAffili-Products','asaffili'),
                    'public' => true,
                    'show_in_menu'=>false,
                    'show_ui' => true,
                    'supports' => array('title','editor'),
                    'has_archive' => true,
                    'rewrite' => array('slug'=>'produkt','with_front' => false)
                    )
        );
        
        $labels = array(
    		'name' => esc_html_x( 'Product-Category','','asaffili' ),
    		'singular_name' => esc_html_x( 'Productcategory','','asaffili' ),
    		'search_items' =>  esc_html_x( 'Search in category','','asaffili' ),
    		'all_items' => esc_html_x( 'All categories','','asaffili' ),
    		'parent_item' => esc_html_x( 'Parent-Category','','asaffili' ),
    		'parent_item_colon' => esc_html_x( 'Parent-Category:','','asaffili' ),
    		'edit_item' => esc_html_x( 'Edit Category','','asaffili' ), 
    		'update_item' => esc_html_x( 'Update Category','','asaffili' ),
    		'add_new_item' => esc_html_x( 'New Category','','asaffili' ),
    		'new_item_name' => esc_html_x( 'New Category','','asaffili' ),
    		'menu_name' => esc_html_x( 'Product-Categories','','asaffili' ),
  		); 	
 
		// Name der Taxonomie - array('product') verbindet diese Taxonomie mit dem Post-Type product
  		register_taxonomy('asaffili_product_category',array('asaffili_products'), array(
    		'hierarchical' => true,
    		'labels' => $labels,
    		'show_ui' => true,
    		'query_var' => true,
    		'rewrite' => array( 'slug' => 'produkte' ),
  		));
 
	}

 
	function asaffili_products_metainit()
	{
  		add_meta_box("asaffili_products_meta", esc_html_x('Details','','asaffili'), array($this,'asaffili_products_meta'), "asaffili_products", "normal", "high");
  		add_meta_box("asaffili_products_meta_product", esc_html_x('Produkt-Info','','asaffili'), array($this,'asaffili_products_meta_product'), "asaffili_products", "normal", "high");
  		add_meta_box("asaffili_products_meta_image", esc_html_x('Image','','asaffili'), array($this,'asaffili_products_meta_image'), "asaffili_products", "normal", "high");
  		add_meta_box("asaffili_products_meta_shop", esc_html_x('Shop','','asaffili'),array($this,'asaffili_products_meta_shop'),"asaffili_products","normal","high");
	}

	function asaffili_products_meta()
	{
  		global $post;
  		if($post->ID>0)
  		{  	
  			$custom = get_post_custom($post->ID);
  			$_asaffili_url = $custom["_asaffili_url"][0];
  			$_asaffili_image = $custom["_asaffili_image"][0];
  			$_asaffili_preis = $custom["_asaffili_preis"][0];
  			$_asaffili_preis_g = $custom["_asaffili_preis_g"][0];
  			$_asaffili_preis_old = $custom["_asaffili_preis_old"][0];
  			$_asaffili_shipping = $custom["_asaffili_shipping"][0];
		}  	
    
  		?>
  		<p><label><?php echo esc_html_e('Deeplink:','asaffili');?></label><br />
  		<input type="text" size="70" name="_asaffili_url" value="<?php echo esc_attr($_asaffili_url);?>"/>
  		<?php
  		if($_asaffili_url!="")echo "<a href=\"".esc_url($_asaffili_url)."\" target=\"_blank\">Link</a>\n";
  		?>
  		</p>
  
  		<p><label><?php echo esc_html_e('Image-URL:','asaffili');?></label><br />
  		<input type="text" size="70" name="_asaffili_image" value="<?php echo esc_attr($_asaffili_image);?>"/></p>
  
		<p><label><?php echo esc_html_e('Price','asaffili');?></label><br />
  		<input type="text" size="10" name="_asaffili_preis" value="<?php echo esc_attr($_asaffili_preis);?>"/></p>
  
  		<p><label><?php echo esc_html_e('Price/Unit:','asaffili');?></label><br />
  		<input type="text" size="10" name="_asaffili_preis_g" value="<?php echo esc_attr($_asaffili_preis_g);?>"/></p>
  
  		<p><label><?php echo esc_html_e('Price old:','asaffili');?></label><br />
  		<input type="text" size="10" name="_asaffili_preis_old" value="<?php echo esc_attr($_asaffili_preis_old);?>"/></p>

		<p><label><?php echo esc_html_e('Shipping Cost:','asaffili');?></label><br />
  		<input type="text" size="10" name="_asaffili_shipping" value="<?php echo esc_attr($_asaffili_shipping);?>"/></p>  
   
  		<?php
	}
 

	function asaffili_products_meta_image()
	{
  		global $post;
  		if($post->ID>0)
  		{  	
  			$custom = get_post_custom($post->ID);
  			$_asaffili_image = $custom["_asaffili_image"][0];  		
  			echo "<img src=\"".esc_url($_asaffili_image)."\" style=\"max-height:300px\"/>\n";  		
		}  	
	
	
	}


	function asaffili_products_meta_shop()
	{
  		//update_post_meta($post_id, "_asaffili_shop_title", $shop_title);
		//update_post_meta($post_id, "_asaffili_shop_url", $shop_url);
		//update_post_meta($post_id, "_asaffili_shop_id",	$shop_id);
  		
  		global $post;
  		$custom = get_post_custom($post->ID);
  		
  		if(isset($custom["_asaffili_shop_id"]))		$_asaffili_shop_id=$custom["_asaffili_shop_id"][0];else $_asaffili_shop_id=0;
  		if(isset($custom["_asaffili_shop_title"]))	$_asaffili_shop_title = $custom["_asaffili_shop_title"][0];else $_asaffili_shop_title="";
  		if(isset($custom["_asaffili_shop_url"]))	$_asaffili_shop_url = $custom["_asaffili_shop_url"][0];else $_asaffili_shop_url="";
  		?>
 
   		<div class="">
			<p><label>Shop:</label><br>
				<select name="_asaffili_shop_id">
					<option value="0">Keine Auswahl</option>
					
					<?php 
					$args = array( 'post_type' => 'asaffili_shops', 'posts_per_page'   => 1000, 'order' => 'ASC','orderby' => 'post_title' );

					$myposts = get_posts($args);

					foreach($myposts as $item)
					{
						
				    	echo '<option value="'.$item->ID.'"';
				    	if($item->ID==$_asaffili_shop_id)echo ' selected';
				    	echo '>';
						echo esc_html($item->post_title);
						echo '</option>\n';
					}
					?>



				</select>
			</p>	
		</div>
		
   		<div class="">  
			<p><label><?php echo esc_html_e('Shop-Title:','asaffili');?></label><br>
				<input type="text" id="shop_title" name="_asaffili_shop_title" value="<?php echo esc_attr($_asaffili_shop_title);?>" size="60"/>
			</p>	
		</div>
		<div class="">
			<p><label><?php echo esc_html_e('Shop-URL:','asaffili');?></label><br>
				<input type="text" id="shop_url" name="_asaffili_shop_url" value="<?php echo esc_attr($_asaffili_shop_url);?>" size="60"/>
			</p>	
		</div>
  		
  		<?php
	}
	
	
	
	function asaffili_products_meta_product()
	{
  		
  		
  		global $post;
  		$custom = get_post_custom($post->ID);
  		
  		if(isset($custom["_asaffili_product_info"]))		$_asaffili_product_info=$custom["_asaffili_product_info"][0];else $_asaffili_product_info="";
  		?>
 
   		<div class="">
			<p><label>Product-Info:</label><br>
				<textarea name="_asaffili_product_info" cols="70" rows="8"><?php echo $_asaffili_product_info;?></textarea>
			</p>	
		</div>
		
   		
  		
  		<?php
	}
	
	
	
	function asaffili_products_save_details($post_id)
	{
		$post_type = get_post_type($post_id);

		if($post_type=="asaffili_products")
		{
			if(isset($_POST["_asaffili_url"]))		$_asaffili_url=sanitize_text_field($_POST["_asaffili_url"]);else $_asaffili_url="";
			if(isset($_POST["_asaffili_image"]))	$_asaffili_image=sanitize_text_field($_POST["_asaffili_image"]);else $_asaffili_image="";
			if(isset($_POST["_asaffili_preis"]))	$_asaffili_preis=sanitize_text_field($_POST["_asaffili_preis"]);else $_asaffili_preis="";
			if(isset($_POST["_asaffili_preis_g"]))	$_asaffili_preis_g=sanitize_text_field($_POST["_asaffili_preis_g"]);else $_asaffili_preis_g="";
			if(isset($_POST["_asaffili_preis_old"]))$_asaffili_preis_old=sanitize_text_field($_POST["_asaffili_preis_old"]);else $_asaffili_preis_old="";
			if(isset($_POST["_asaffili_shipping"]))	$_asaffili_shipping=sanitize_text_field($_POST["_asaffili_shipping"]);else $_asaffili_shipping="";
			
			if(isset($_POST["_asaffili_shop_id"]))		$_asaffili_shop_id=sanitize_text_field($_POST["_asaffili_shop_id"]);else $_asaffili_shop_id="";			
			if(isset($_POST["_asaffili_shop_titel"]))	$_asaffili_shop_titel=sanitize_text_field($_POST["_asaffili_shop_titel"]);else $_asaffili_shop_titel="";			
			if(isset($_POST["_asaffili_shop_url"]))		$_asaffili_shop_url=sanitize_text_field($_POST["_asaffili_shop_url"]);else $_asaffili_shop_url="";
			
			if(isset($_POST["_asaffili_product_info"]))		$_asaffili_product_info=$_POST["_asaffili_product_info"];else $_asaffili_product_info="";
			
			
			$_asaffili_preis=strtr($_asaffili_preis,",",".");
			$_asaffili_preis_g=strtr($_asaffili_preis_g,",",".");
			
			update_post_meta($post_id, "_asaffili_url", $_asaffili_url);
			update_post_meta($post_id, "_asaffili_image", $_asaffili_image);
			update_post_meta($post_id, "_asaffili_preis", $_asaffili_preis);
			update_post_meta($post_id, "_asaffili_preis_g", $_asaffili_preis_g);
			update_post_meta($post_id, "_asaffili_preis_old", $_asaffili_preis_old);
			update_post_meta($post_id, "_asaffili_shipping", $_asaffili_shipping);
			update_post_meta($post_id, "_asaffili_product_info", $_asaffili_product_info);
			
			
			update_post_meta($post_id, "_asaffili_shop_id",$_asaffili_shop_id);
  			if($_asaffili_shop_id>0)
  			{
				$shop=get_post($_asaffili_shop_id);
				update_post_meta($post_id, "_asaffili_shop_title",$shop->post_title);
					
				update_post_meta($post_id, "_asaffili_shop_url",$shop->_asaffili_shops_url);
					
			}else
			{
				if(isset($_POST["_asaffili_shop_title"]))	update_post_meta($post_id, "_asaffili_shop_title",sanitize_text_field($_POST["_asaffili_shop_title"]));
  				if(isset($_POST["_asaffili_shop_url"]))		update_post_meta($post_id, "_asaffili_shop_url",sanitize_text_field($_POST["_asaffili_shop_url"]));
			}
			
		}  
	}	




}


$asaffili_admin_products = new asaffili_admin_products();



