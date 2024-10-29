

	
	
		<div class="row">
			<?php
			if($post->_asaffili_image!="")
			{
				?>
				<div class="col-5 asaffili-product-image">
					<img src="<?php echo $post->_asaffili_image;?>"/>
				</div>
				<?php
			}	
			?>
			<div class="col-7 asaffili-product-info">
			
				<?php
				if($post->_asaffili_preis_old>0 and $post->_asaffili_preis!=$post->_asaffili_preis_old)
				{
					?><div class="asaffili-product-preis"><strong>Preis: </strong><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;"?><span><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_old))." &euro;";?></span></div><?php
				}else
				{
					?><div class="asaffili-product-preis"><strong>Preis: </strong><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;"?></div><?php	
				}
			
				if($post->_asaffili_preis_g>0)
				{
					?><div class="asaffili-product-preis-g"><span>Grundpreis:</span><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_g))." &euro;";?></div>						<?php
				}
				?>
			
				<div class="asaffili-product-shipping"><strong>Versand: </strong><?php echo str_replace(".",",",sprintf("%0.2f", $post->_asaffili_shipping))." &euro;";?></div>					
			
				<div class="asaffili-product-link">
					<a class="btn btn-primary" href="<?php echo $post->_asaffili_url;?>" target="_blank">Zum Shop</a>
				</div>
					
				<div><b>Anbieter: </b><?php echo $post->_asaffili_shop_title;?></div>	
				
				<?php
				$option_products=get_option('asaffili_options_products');
				if($option_products["asaffili_options_products_affili_note"]!="")
				{
					echo "<div class=\"asaffili-product-affili-note\">\n";
					echo $option_products["asaffili_options_products_affili_note"];
					echo "</div>\n";
				}	
				?>
			</div>
			
		</div>
		
		
		<?php
		if($post->_asaffili_product_info!="")
		{
			echo "<div class=\"asaffili-product-desc\">\n";
			echo str_replace("\n","<br>",$post->_asaffili_product_info);
			echo "</div>\n";
		}
		?>
		
		<div class="asaffili-product-desc">
			<?php echo $post->post_content;?>		
		</div>
		
		<?php


		if($post->_asaffili_shop_id>0)
		{
			$shop_row=get_post($post->_asaffili_shop_id);
			echo "<div>\n";
				echo "<h4>".$shop_row->_asaffili_shops_products_info_titel."</h4>\n";				
				echo "<p>".$shop_row->_asaffili_shops_products_info_text."</p>\n";
			echo "</div>\n";
			
		
			$args = array(
 				'post_type' => 'asaffili_actions',		
 				'posts_per_page' => 100,
 				'meta_query' => array(
            	array(
                'key' => '_asaffili_actions_shop_id',
                'value' => $shop_row->ID,
                'compare' => '='
            	),
            	array(
                'key' => '_asaffili_actions_zeit_bis',
                'value' => time(),
                'compare' => '>'
            	),            	
            	array(
                'key' => '_asaffili_actions_zeit_von',
                'value' => time(),
                'compare' => '<'
            		)            	
            	)	 		 		 						
			);
			
			$custom_query = get_posts($args); 				
			if($custom_query)
			{			
				?>			
				<div class="asaffili-actions-frame">
				<h2>Aktionen</h2>
			
				<?php	
				foreach($custom_query as $item)
				{
					?>
					<div class="asaffili-actions-item">
						<div><a class="btn btn-primary" href="<?php echo $item->_asaffili_actions_url;?>" target="_blank">Zur Aktion</a></div>
							
						<a href="<?php echo $item->_asaffili_actions_url;?>" target="_blank"><?php echo $item->post_title;?></a>
						<p><?php echo $item->post_content;?></p>
					
					</div>
					<?php
				}
				?>
				</div>
				<?php
			
			}
		
		
		}	
		
			
			$options=get_option('asaffili_options_products');
			$products_pages_listings=$options['asaffili_options_products_pages_listings'];
		
						
			
			$post_categories = get_the_terms( $post->ID, 'asaffili_product_category' );				
			if($post_categories)
			{
				$term_link = get_term_link( $post_categories[0] );
				
				echo "<div class=\"asaffili-product-catlist\"><strong>Kategorie: </strong><a href=\"".$term_link."\">".$post_categories[0]->name."</a></div>\n";		
				$cat_id=$post_categories[0]->term_id;
			}
				
			
			
			$args = array(
 				'post_type' => 'asaffili_products', 		
 				'posts_per_page' => 8,
 				'orderby' => '_asaffili_preis',
 				'order' => 'ASC', 			
			);
					
			
					
			if(isset($cat_id))
			{
				if($cat_id>0)
				{
					$args['tax_query']=array(
					array(
					'taxonomy' => 'asaffili_product_category',
					'field'    => 'id',
					'terms'    => $cat_id,
					'include_children' => true
					)
				);
				}
			}
			
			
		
			$custom_query = new WP_Query($args); 
			if($custom_query->have_posts())
			{
			
				$retstring="
		
				<style>
				.asaffili-productset-grid-container
				{
					display:grid;
					grid-template-columns: 25% 25% 25% 25%;
  					padding: 0px;
  					grid-column-gap: 10px;
  					grid-row-gap: 10px;
				}
		
				.asaffili-productset-grid-item
				{
					background-color:#fff;
					padding:10px;
				}
		.asaffili-productset-grid-item-image
		{
			width:80%;
			margin:auto;
			height:220px;
			overflow:hidden;
			margin-bottom:10px;
		}
		.asaffili-productset-grid-item-image img
		{
			width:100%;
		}
		.asaffili-grid-item-info
		{
			text-align:center;
		}
		.asaffili-grid-preis
		{
			font-size:14px;
		}
		.asaffili-grid-preis span
		{
			text-decoration:line-through;
			color:#ff0000;
		}
		
		.asaffili-grid-shipping, .asaffili-grid-preis-g
		{
			font-size:12px;
		}
		.asaffili-grid-item-title
		{
			font-size:14px;line-height:16px;display:block;margin-bottom:10px;
		}
		
		.asaffili-products-cats
		{
			background-color:#fff;
			padding:10px;
			margin-bottom:10px;
		}
		.asaffili-products-cats ul {
			list-style:none;
		}
		.asaffili-products-cats li {
			width:20%;
			float:left;
		}
	</style>\n";	
		
		$retstring.="<div class=\"row\">\n";
		
		while ($custom_query->have_posts() ) : $custom_query->the_post();
			$retstring.="<div class=\"col-3 asaffili-productset-grid-item\">\n";
			$post=get_post();		
			$retstring.="<a href=\"".esc_url( get_permalink())."\">\n";
			if($post->_asaffili_image!="")
			{
				$retstring.="<div class=\"asaffili-productset-grid-item-image\">\n";				
				$retstring.="<img src=\"".$post->_asaffili_image."\"/>\n";				
				$retstring.="</div>\n";						
			}	
		
			$retstring.="<div class=\"asaffili-grid-item-info\">\n";
			$retstring.=$post->post_title;
			$retstring.="</a>\n";
			
			if($post->_asaffili_preis_old>0 and $post->_asaffili_preis!=$post->_asaffili_preis_old)
			{
				$retstring.="<div class=\"asaffili-grid-preis\">".str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro; <span>".str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_old))." &euro; </span></div>\n";
			}else
			{
				$retstring.="<div class=\"asaffili-grid-preis\">".str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;</div>\n";
			}
						
			if($post->_asaffili_preis_g>0)
			{
				$retstring.="<div class=\"asaffili-grid-preis-g\"><span>Grundpreis:</span>".str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_g))." &euro;</div>\n";
			}
		
			$retstring.="<div class=\"asaffili-grid-shipping\"><span>Versand:</span>".str_replace(".",",",sprintf("%0.2f", $post->_asaffili_shipping))." &euro;</div>\n";
			$retstring.="</div>\n";
			$retstring.="</div>\n";
		endwhile; 
		$retstring.="</div>\n";
	}else
	{
		echo "<p>Keine Beiträge</p>";
			
	}
	
	wp_reset_postdata();
		
		
	
		echo $retstring;
		
		
		?>
		
		        		
	
	

		

