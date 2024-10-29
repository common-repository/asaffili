<?php
		


				
		$terms = get_terms( array(
    			'taxonomy' => 'asaffili_product_category',
    			'hide_empty' => false,    			
    			'parent' => $cat_id
			) );
		if($terms)
		{
			echo "<div class=\"asaffili-products-cats\">\n";		
			echo "<ul>\n";
			foreach($terms as $term)
			{
				echo "<li>\n";								
				$term_link = get_term_link($term);
				echo "<a href=\"".$term_link."\">".$term->name."</a>\n";
				echo "</li>\n";				
			}
			echo "</ul>\n";
			echo "<div style=\"clear:both;\"></div>\n";
			echo "</div>\n";
		}	
?>
				
			
			
	<div id="post-wrapper" class="asaffili-grid-container asaffili-grid-container-<?php echo $asaffili_options_products['asaffili_options_products_count_col_listings'];?>">

	<?php while ( $custom_query->have_posts() ) : $custom_query->the_post();?>
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
				?><div class="asaffili-grid-preis"><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;");?><span><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_old))." &euro;");?></span></div><?php
			}else
			{
				?><div class="asaffili-grid-preis"><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis))." &euro;");?></div><?php	
			}
						
			if($post->_asaffili_preis_g>0)
			{
				?>
				<div class="asaffili-grid-preis-g"><span>Grundpreis:</span><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_preis_g))." &euro;");?></div>
				<?php
			}
			?>
			<div class="asaffili-grid-shipping"><span>Versand:</span><?php echo esc_html(str_replace(".",",",sprintf("%0.2f", $post->_asaffili_shipping))." &euro;");?></div>
		</div>
	</div>
	<?php endwhile; ?>

	</div>
	
			
	<?php
		echo "<div class=\"asaffili-content-frame asaffili-pagination\">\n";	
		
		
  		
  		$options=get_option('asaffili_options_products');
		$baselink=$options['asaffili_options_products_pages_listings'];	
  		
  		if(!isset($numpages))
  		{    	
			$numpages = $custom_query->max_num_pages;
    		if( ! $numpages )
    		{
        		$numpages = 1;
    		}
  		}
  	
  		if(strpos($baselink,"?")>0)
  			$format='&pagenum=%#%';else
  			$format='?pagenum=%#%';
  			
  		if($asaffili_search_value!="")
  		{
			$pagination_args = array(
    			'base'         => $baselink.'%_%',
    			//'base'         => $base,
    			'format'       => $format,
    			'total'        => $numpages,
    			'current'      => $paged,
    			
    			'add_args' => array( 'asaffili_search_value' => $asaffili_search_value)
    			
    			
    			//'show_all'     => false,
    			//'end_size'     => 1,
    			//'mid_size'     => $pagerange,
    			//'prev_next'    => true,
    			//'prev_text'    => __( '&laquo;' ),
    			//'next_text'    => __( '&raquo;' ),
    			//'type'         => 'array',
    			//'add_args'     => false,
    			//'add_fragment' => ''
  			);
		}else
		{
			$pagination_args = array(
    			'base'         => $baselink.'%_%',
    			//'base'         => $base,
    			'format'       => $format,
    			'total'        => $numpages,
    			'current'      => $paged,
    			
  			);
		}
  		

  		$paginate_links = paginate_links( $pagination_args );
  			
  		echo "<nav class=\"navigation paging-navigation\">\n";
  		echo $paginate_links;
  		echo "</nav>\n";
  		
  			
  			
		echo "</div>\n";
	?>
	
	<?php
	if($cat_id>0)
	{
		
	echo "<div class=\"asaffili-content-frame\">\n";
	function func_asaffili_cat_pfad($cat_id,$str)
	{
		$options=get_option('asaffili_options_products');
		$products_pages_listings=$options['asaffili_options_products_pages_listings'];	
		$category = get_term_by('id', $cat_id, 'asaffili_product_category');		
		if($category->parent>0) $str=func_asaffili_cat_pfad($category->parent,$str)." > ";		
		$str.="<a href=\"".$products_pages_listings."?cat_id=".$category->term_id."\">".$category->name."</a>\n";				
		return $str;	
	
	};	
	
	$category = get_term_by('id', $cat_id, 'asaffili_product_category');		
	echo func_asaffili_cat_pfad($category->term_id,$str="");
	echo "</div>\n";
	}
	?>
	