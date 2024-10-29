<?php

	function explode_own($separator,$string)
	{		
		$i=0;
		$string=str_replace('""','',$string);
		$pos=strpos($string,$separator);
		if($pos>0)
		{
			while($pos>=0 and $i<30)
			{
				$i++;
				//Erstes Zeichen "
				if(substr($string,0,1)=='"')
				{
					$pos1=strpos($string,'"',1);
	
					$piece1=substr($string,1,$pos1-1);
					$string=substr($string,$pos1+2);
				}else
				{
					$piece1=substr($string,0,$pos);
					$string=substr($string,$pos+1);						
				}
				$ret_array[]=$piece1;
				$pos=strpos($string,$separator);
				if($pos===false)
				{
					if(strlen($string)>0)$ret_array[]=trim($string);
					break;
				}	
			}	
		}else
		{
			$ret_array=array($string);
		}		
		return $ret_array;
	}
	
	
	function asaffili_import_file_get_field($field,$data,$pieces)
	{
		if($field=='artnumber')	$data->artnumber.=$pieces;
		if($field=='title')		$data->title.=	$pieces;
		if($field=='desc')		$data->desc.=	$pieces;
		if($field=='deeplink')	$data->deeplink.=$pieces;
		if($field=='image')		$data->image.=	$pieces;
		if($field=='cat')		$data->cat.=		$pieces;
		if($field=='preis')		$data->preis.=$pieces;
		if($field=='preis_g')	$data->preis_g.=$pieces;
		if($field=='shipping')	$data->shipping.=$pieces;	
		if($field=='programid') $data->programid=$pieces;					
		if($field=='preis_old') $data->preis_old=$pieces;					
		return $data;
	} 
	
	function asaffili_get_cat_list_form($parent_id,$str,$ebene)
	{
		global $wpdb;
		$ebene++;
		$sql="select t.term_id,t.name from ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_taxonomy ta where ta.taxonomy='asaffili_product_category' and ta.parent=".$parent_id." and ta.term_id=t.term_id";
	
		$results=$wpdb->get_results($sql);
		foreach($results as $item)
		{
			$str.="<option value=\"".$item->term_id."\">";
			for($i=0;$i<$ebene;$i++)$str.="-";
			$str.=esc_html($item->name)."</option>\n";
			$str=asaffili_get_cat_list_form($item->term_id,$str,$ebene);
		}
	
		return $str;
	}



	function asaffili_get_cat_list_form_sel($parent_id,$str,$ebene,$default_cat)
	{
		global $wpdb;
		$ebene++;
		$sql="select t.term_id,t.name from ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_taxonomy ta where ta.taxonomy='asaffili_product_category' and ta.parent=".$parent_id." and ta.term_id=t.term_id";
	
		$results=$wpdb->get_results($sql);
		foreach($results as $item)
		{
			$str.="<option value=\"".$item->term_id."\"";
			if($default_cat==$item->term_id)$str.=" selected";
			$str.=">";
			for($i=0;$i<$ebene;$i++)$str.="-";
			$str.=esc_html($item->name)."</option>\n";
			$str=asaffili_get_cat_list_form_sel($item->term_id,$str,$ebene,$default_cat);
		}
	
	return $str;
	}
	
	
	
class asaffili_admin_imports
{
	public function __construct()
	{
		add_action('init', array($this,'post_type_asaffili_imports'));
		add_action('admin_init', array($this,'asaffili_imports_metainit'));
		add_action('save_post', array($this,'asaffili_imports_save_details'));
		add_action( 'admin_enqueue_scripts', array($this,'asaffili_import_scripts' ));
		
		add_action('wp_ajax_asaffili-set-catid',array($this,'ajax_asaffili_set_catid'));
		add_action('wp_ajax_asaffili-del-catid',array($this,'ajax_asaffili_del_catid'));
		add_action('wp_ajax_asaffili-noimport-catid',array($this,'ajax_asaffili_noimport_catid'));
		
		add_action('wp_ajax_asaffilinewcatstr',array($this,'ajax_asaffilisetnewcatstr'));
		add_action('wp_ajax_nopriv_asaffilinewcatstr',array($this,'ajax_asaffilisetnewcatstr'));

		add_action('wp_ajax_asaffili-head-print',array($this,'ajax_asaffili_head_print'));
		add_action('wp_ajax_asaffili-read-import',array($this,'ajax_asaffili_read_import'));
		
		add_action('wp_ajax_asaffili-import-file2',array($this,'ajax_asaffili_import_file2'));
		add_action('wp_ajax_asaffili-import-file2-setdate',array($this,'ajax_asaffili_import_file2_setdate'));
		add_action('wp_ajax_asaffili-stat-delcat',array($this,'ajax_asaffili_stat_delcat'));
		add_action('wp_ajax_asaffili-stat-delposts',array($this,'ajax_asaffili_stat_delposts'));
		add_action('wp_ajax_asaffili-stat-delpostsold',array($this,'ajax_asaffili_stat_delpostsold'));
		
		add_filter('manage_edit-asaffili_imports_columns',array($this,'asaffili_edit_admin_columns')) ;
		add_action('manage_asaffili_imports_posts_custom_column',array($this,'asaffili_post_custom_columns'));
		add_filter('manage_edit-asaffili_imports_sortable_columns', array($this,'asaffili_post_sortierbare_columns' ));
	
	
	}	
	
	



	public static function post_type_asaffili_imports()
	{
    	register_post_type(

               'asaffili_imports',
                array(
                    'label' => __('asAffili-Imports'),
                    'public' => true,
					'exclude_from_search'=>true,
					'publicly_queryable'=>false,
					'show_in_menu'=>false,
					'show_in_nav_menus'=>false,
                    'show_ui' => true,
                    'supports' => array('title')
                )
        );
	}


	
	public static function asaffili_imports_metainit()
	{
  		add_meta_box("asaffili_imports_meta", esc_html_x('Configuration','','asaffili'), array($this,'asaffili_imports_meta'), "asaffili_imports", "normal", "high");
  		add_meta_box("asaffili_imports_meta_shop", esc_html_x('Shop','','asaffili'), array($this,'asaffili_imports_meta_shop'), "asaffili_imports", "normal", "high");
  		add_meta_box("asaffili_imports_meta_import", esc_html_x('Import','','asaffili'), array($this,'asaffili_imports_meta_import'), "asaffili_imports", "side", "low");
  		add_meta_box("asaffili_imports_meta_fields", esc_html_x('Fields','','asaffili'), array($this,'asaffili_imports_meta_fields'), "asaffili_imports", "normal", "high");
  		add_meta_box("asaffili_imports_meta_catid", esc_html_x('Link Category','','asaffili'), array($this,'asaffili_imports_meta_catid'), "asaffili_imports", "normal", "high");
  		add_meta_box("asaffili_imports_meta_stat", esc_html_x('Stats','','asaffili'), array($this,'asaffili_imports_meta_stat'),"asaffili_imports","side","low");
	}


	function asaffili_imports_meta_field_box($label,$name,$sel)
	{
		$str='
  		<div class="asaffili-admin-field-box">
  		<p><label>'.$label.'</label><br/>
  		<select name="'.$name.'">
  			<option value="leer">leer</option>
  			<option value="artnumber" ';if($sel=="artnumber")$str.= " selected";$str.='>artnumber</option>
  			<option value="title" ';if($sel=="title")$str.= " selected";$str.='>title</option>
  			<option value="desc" ';if($sel=="desc")$str.= " selected";$str.='>desc</option>
  			<option value="deeplink" ';if($sel=="deeplink")$str.= " selected";$str.='>deeplink</option>
  			<option value="image" ';if($sel=="image")$str.= " selected";$str.='>image</option>
  			<option value="cat" ';if($sel=="cat")$str.= " selected";$str.='>cat</option>
  			<option value="preis" ';if($sel=="preis")$str.= " selected";$str.='>preis</option>
  			<option value="preis_g" ';if($sel=="preis_g")$str.= " selected";$str.='>preis_g</option>
  			<option value="shipping" '; if($sel=="shipping")$str.= " selected";$str.='>shipping</option>
  			<option value="preis_old" '; if($sel=="preis_old")$str.= " selected";$str.='>preis_old</option>
  			<option value="programmid" '; if($sel=="programmid")$str.= " selected";$str.='>programmid</option>
  		</select></p>
  		</div>';
  		return $str;
	}
  
  
  
  
	function asaffili_imports_meta()
	{
  		global $post;
  
  		$custom = get_post_custom($post->ID);
  		if(isset($custom["import_url"][0])) $import_url = $custom["import_url"][0];else $import_url="";
  
  		if(isset($custom["file_compression"][0]))$file_compression = $custom["file_compression"][0];else $file_compression=0;
  		if(isset($custom["suchbegriff"][0]))$suchbegriff = $custom["suchbegriff"][0];else $suchbegriff="";
  		if(isset($custom["limit"][0]))$limit = $custom["limit"][0];else $limit=0;
  		if(isset($custom["anfentfernen"][0]))$anfentfernen= $custom["anfentfernen"][0];else $anfentfernen=0;
  		if(isset($custom["zeichen_entfernen"][0]))$zeichen_entfernen = $custom["zeichen_entfernen"][0];else $zeichen_entfernen="";
  		if(isset($custom["utf8encode"][0]))$utf8encode = $custom["utf8encode"][0];else $utf8encode=0;
  		if(isset($custom["utf8decode"][0]))$utf8decode = $custom["utf8decode"][0];else $utf8decode=0;
  
  		if(isset($custom["importsource"][0]))$importsource = $custom["importsource"][0];else $importsource='asaffili';
  		if(isset($custom["location"][0]))$location = $custom["location"][0];else $location="";
  		
  		if(isset($custom["default_cat"][0]))$default_cat = $custom["default_cat"][0];else $default_cat=0;
  		if(isset($custom["separator_image"][0]))$separator_image = $custom["separator_image"][0];else $separator_image="";
  
  		?>
  		<div class="asaffili-admin-field-box-wide">
  			<p><label><?php echo esc_html_e('Import-URL:','asaffili');?></label><br />
  			<input type="text" size="70" name="import_url" value="<?php echo esc_url($import_url);?>"/></p>
  		</div>	
  
  		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Compression:','asaffili');?></label><br />
  			<select name="file_compression">
  				<option value="0" <?php if($file_compression==0)echo " selected";?>>ohne</option>
  				<option value="1" <?php if($file_compression==1)echo " selected";?>>zip</option>	
  			</select>	
  			</p>
  		</div>	
  
  		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Limit:','asaffili');?></label><br />
  			<input type="text" size="10" name="limit" value="<?php echo esc_html($limit);?>"/></p>
  		</div>	
  
 		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Remove quotation marks','asaffili');?></label><br />
  			<select name="anfentfernen">
  				<option value="0" <?php if($anfentfernen==0)echo " selected";?>><?php echo esc_html_e('No','asaffili');?></option>
  				<option value="1" <?php if($anfentfernen==1)echo " selected";?>><?php echo esc_html_e('Yes','asaffili');?></option>
  			</select>
  			</p>
  		</div>	
  		
  		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Default cateogry','asaffili');?></label><br />
  			<select name="default_cat">
  				<option value="0"><?php echo esc_attr_e('No selected','asaffili');?></option>
  				<?php
  				echo asaffili_get_cat_list_form_sel(0,"",0,$default_cat);
  				?>
	
  			</select>
  			</p>
  		</div>	
  		
  		<div style="clear:left;"></div>
  		
  		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Remove Characters','asaffili');?></label><br />
  			<input type="text" size="10" name="zeichen_entfernen" value="<?php echo esc_attr($zeichen_entfernen);?>"/></p>
  		</div>	
  
  		<div class="asaffili-admin-field-box">
  			<p><label>utf8decode:</label><br />
  			<select name="utf8decode">
  				<option value="0" <?php if($utf8decode==0)echo " selected";?>><?php echo esc_html_e('No','asaffili');?></option>
  				<option value="1" <?php if($utf8decode==1)echo " selected";?>><?php echo esc_html_e('Yes','asaffili');?></option>
  			</select>
  			</p>
  		</div>	
  
  		<div class="asaffili-admin-field-box">
  			<p><label>utf8encode:</label><br />
  			<select name="utf8encode">
  				<option value="0" <?php if($utf8encode==0)echo " selected";?>><?php echo esc_html_e('No','asaffili');?></option>
  				<option value="1" <?php if($utf8encode==1)echo " selected";?>><?php echo esc_html_e('Yes','asaffili');?></option>
  			</select>
  			</p>
  		</div>	
  
  		<div class="asaffili-admin-field-box">
  			<p><label><?php echo esc_html_e('Image-Separator','asaffili');?></label><br />
  			<select name="separator_image">
  				<option value=""<?php if($separator_image=="")echo " selected";?>>Keine Trennung</option>
  				<option value=";"<?php if($separator_image==";")echo " selected";?>>;</option>
  				<option value=","<?php if($separator_image==",")echo " selected";?>>,</option>
  				<option value="|"<?php if($separator_image=="|")echo " selected";?>>|</option>
  				<option value="^"<?php if($separator_image=="^")echo " selected";?>>^</option>
  			</select></p>
  		</div>
  		
  		
   		<div style="clear:left;"></div>
   		
  		<div class="asaffili-admin-field-box-wide">
  			<p><label><?php echo esc_html_e('Search phrase','asaffili');?></label><br />
  			<input type="text" size="70" name="suchbegriff" value="<?php echo esc_attr($suchbegriff);?>"/></p>
  		</div>	
  
  		
  		
  		<div style="clear:left"></div>
  		
  		<?php
	}


	function asaffili_imports_meta_shop()
	{
  		global $post;
  
  		$custom = get_post_custom($post->ID);
  		
  		if(isset($custom["shop_id"]))	$shop_id=$custom["shop_id"][0];else $shop_id=0;
  		if(isset($custom["shop_title"]))$shop_title = $custom["shop_title"][0];else $shop_title="";
  		if(isset($custom["shop_url"]))$shop_url = $custom["shop_url"][0];else $shop_url="";
  		?>
 
   		<div class="">
			<p><label>Shop:</label><br>
				<select name="shop_id">
					<option value="0">Keine Auswahl</option>
					
					<?php 
					$args = array( 'post_type' => 'asaffili_shops', 'posts_per_page'   => 1000, 'order' => 'ASC','orderby' => 'post_title' );

					$myposts = get_posts($args);

					foreach($myposts as $item)
					{
						
				    	echo '<option value="'.$item->ID.'"';
				    	if($item->ID==$shop_id)echo ' selected';
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
				<input type="text" id="shop_title" name="shop_title" value="<?php echo esc_attr($shop_title);?>" size="60"/>
			</p>	
		</div>
		<div class="">
			<p><label><?php echo esc_html_e('Shop-URL:','asaffili');?></label><br>
				<input type="text" id="shop_url" name="shop_url" value="<?php echo esc_attr($shop_url);?>" size="60"/>
			</p>	
		</div>
  		
  		
  		
  		<?php
	}




	function asaffili_imports_meta_fields()
	{
  		global $post;
  
  		$custom = get_post_custom($post->ID);
  		if(isset($custom["import_url"][0])) $import_url = $custom["import_url"][0];else $import_url="";
  
  		if(isset($custom["file_compression"][0]))$file_compression = $custom["file_compression"][0];else $file_compression=0;
  		if(isset($custom["suchbegriff"][0]))$suchbegriff = $custom["suchbegriff"][0];else $suchbegriff="";
  		if(isset($custom["limit"][0]))$limit = $custom["limit"][0];else $limit=0;
  		if(isset($custom["anfentfernen"][0]))$anfentfernen= $custom["anfentfernen"][0];else $anfentfernen=0;
  		if(isset($custom["zeichen_entfernen"][0]))$zeichen_entfernen = $custom["zeichen_entfernen"][0];else $zeichen_entfernen="";
  		if(isset($custom["utf8encode"][0]))$utf8encode = $custom["utf8encode"][0];else $utf8encode=0;
  		if(isset($custom["utf8decode"][0]))$utf8decode = $custom["utf8decode"][0];else $utf8decode=0;
  
  		if(isset($custom["importsource"][0]))$importsource = $custom["importsource"][0];else $importsource='asaffili';
  		if(isset($custom["location"][0]))$location = $custom["location"][0];else $location="";
  		if(isset($custom["shop_title"]))$shop_title = $custom["shop_title"][0];else $shop_title="";
  		if(isset($custom["shop_url"]))$shop_url = $custom["shop_url"][0];else $shop_url="";
  
  		if(isset($custom["separator"][0]))$separator = $custom["separator"][0];else$separator="";
  		if(isset($custom["field1"][0]))	$field1 = $custom["field1"][0];else $field1="";
  		if(isset($custom["field2"][0]))	$field2 = $custom["field2"][0];else $field2="";
  		if(isset($custom["field3"][0]))	$field3 = $custom["field3"][0];else $field3="";
  		if(isset($custom["field4"][0]))	$field4 = $custom["field4"][0];else $field4="";
  		if(isset($custom["field5"][0]))	$field5 = $custom["field5"][0];else $field5="";
  		if(isset($custom["field6"][0]))	$field6 = $custom["field6"][0];else $field6="";
  		if(isset($custom["field7"][0]))	$field7 = $custom["field7"][0];else $field7="";
  		if(isset($custom["field8"][0]))	$field8 = $custom["field8"][0];else $field8="";
  		if(isset($custom["field9"][0]))	$field9 = $custom["field9"][0];else $field9="";
  		if(isset($custom["field10"][0]))	$field10 = $custom["field10"][0];else $field10="";
  		if(isset($custom["field11"][0]))	$field11 = $custom["field11"][0];else $field11="";
  		if(isset($custom["field12"][0]))	$field12 = $custom["field12"][0];else $field12="";
  		if(isset($custom["field13"][0]))	$field13 = $custom["field13"][0];else $field13="";
  		if(isset($custom["field14"][0]))	$field14 = $custom["field14"][0];else $field14="";
  		if(isset($custom["field15"][0]))	$field15 = $custom["field15"][0];else $field15="";
  		if(isset($custom["field16"][0]))	$field16 = $custom["field16"][0];else $field16="";
  		if(isset($custom["field17"][0]))	$field17 = $custom["field17"][0];else $field17="";
  		if(isset($custom["field18"][0]))	$field18 = $custom["field18"][0];else $field18="";
  		if(isset($custom["field19"][0]))	$field19 = $custom["field19"][0];else $field19="";
  		if(isset($custom["field20"][0]))	$field20 = $custom["field20"][0];else $field20="";
  		if(isset($custom["field21"][0]))	$field21 = $custom["field21"][0];else $field21="";
  		if(isset($custom["field22"][0]))	$field22 = $custom["field22"][0];else $field22="";
  		if(isset($custom["field23"][0]))	$field23 = $custom["field23"][0];else $field23="";
  		if(isset($custom["field24"][0]))	$field24 = $custom["field24"][0];else $field24="";
  		if(isset($custom["field25"][0]))  $field25 = $custom["field25"][0];else $field25="";
  		if(isset($custom["field26"][0]))	$field26 = $custom["field26"][0];else $field26="";
  		if(isset($custom["field27"][0]))	$field27 = $custom["field27"][0];else $field27="";
  		if(isset($custom["field28"][0]))	$field28 = $custom["field28"][0];else $field28="";
  		if(isset($custom["field29"][0]))	$field29 = $custom["field29"][0];else $field29="";
  		if(isset($custom["field30"][0]))	$field30 = $custom["field30"][0];else $field30="";
  
  		?>
  		
  
  		<p><label><?php echo esc_html_x('Field-Separator','asaffili');?></label><br />
  		<select name="separator">
  			<option value="tab"<?php if($separator=="tab")echo " selected";?>>Tabulator</option>
  			<option value=";"<?php if($separator==";")echo " selected";?>>;</option>
  			<option value=","<?php if($separator==",")echo " selected";?>>,</option>
  			<option value="|"<?php if($separator=="|")echo " selected";?>>|</option>
  			<option value="^"<?php if($separator=="^")echo " selected";?>>^</option>
  			<option value='";"'<?php if($separator=='";"')echo " selected";?>>";"</option>
  		</select></p>
  
  		<style>
  		.asaffili-admin-field-box
  		{
			width:200px;
			float:left;
			margin-right:20px;
		}
		.asaffili-admin-field-box-wide
  		{
			width:100%;
			float:left;
			margin-right:20px;
		}
  		</style>
  
  
  		<div id="asaffili-import-ausgabe-fields"></div>
  
  
  		<?php
  		echo $this->asaffili_imports_meta_field_box(__('Field','asaffili')." 1","field1",$field1);
  		echo $this-> asaffili_imports_meta_field_box("Feld 2","field2",$field2);
  		echo $this->asaffili_imports_meta_field_box("Feld 3","field3",$field3);
  		echo $this->asaffili_imports_meta_field_box("Feld 4","field4",$field4);
  		echo $this->asaffili_imports_meta_field_box("Feld 5","field5",$field5);
  		echo $this->asaffili_imports_meta_field_box("Feld 6","field6",$field6);
  		echo $this->asaffili_imports_meta_field_box("Feld 7","field7",$field7);
  		echo $this->asaffili_imports_meta_field_box("Feld 8","field8",$field8);
  		echo $this->asaffili_imports_meta_field_box("Feld 9","field9",$field9);
  		echo $this->asaffili_imports_meta_field_box("Feld 10","field10",$field10);
  		echo $this->asaffili_imports_meta_field_box("Feld 11","field11",$field11);
  		echo $this->asaffili_imports_meta_field_box("Feld 12","field12",$field12);
  		echo $this->asaffili_imports_meta_field_box("Feld 13","field13",$field13);
  		echo $this->asaffili_imports_meta_field_box("Feld 14","field14",$field14);
  		echo $this->asaffili_imports_meta_field_box("Feld 15","field15",$field15);
  		echo $this->asaffili_imports_meta_field_box("Feld 16","field16",$field16);
  		echo $this->asaffili_imports_meta_field_box("Feld 17","field17",$field17);
  		echo $this->asaffili_imports_meta_field_box("Feld 18","field18",$field18);
  		echo $this->asaffili_imports_meta_field_box("Feld 19","field19",$field19);
  		echo $this->asaffili_imports_meta_field_box("Feld 20","field20",$field20);
  		echo $this->asaffili_imports_meta_field_box("Feld 21","field21",$field21);
  		echo $this->asaffili_imports_meta_field_box("Feld 22","field22",$field22);
  		echo $this->asaffili_imports_meta_field_box("Feld 23","field23",$field23);
  		echo $this->asaffili_imports_meta_field_box("Feld 24","field24",$field24);
  		echo $this->asaffili_imports_meta_field_box("Feld 25","field25",$field25);
  		echo $this->asaffili_imports_meta_field_box("Feld 26","field26",$field26);
  		echo $this->asaffili_imports_meta_field_box("Feld 27","field27",$field27);
  		echo $this->asaffili_imports_meta_field_box("Feld 28","field28",$field28);
  		echo $this->asaffili_imports_meta_field_box("Feld 29","field29",$field29);
  		echo $this->asaffili_imports_meta_field_box("Feld 30","field30",$field30);
  		?>
  
  
  		<div style="clear:left"></div>
  		<?php
	}
	
	
	function asaffili_imports_meta_import()
	{
		?>
		<style>
		.button-asaffili-import
		{
			width:90%;
			margin:auto;
			border:solid 1px #afafaf;
			background-color:#dfdfdf;
			padding:10px;
			color:#000;	
			display:block;
			text-align:center;
			cursor:pointer;
		}
		
		</style>
		<?php
		global $post;
		?>
		<a class="button-asaffili-import" id="button-asaffili-read-import" data-first="<?php echo esc_attr($post->ID);?>"><?php echo esc_html_e('Read Import-File','asaffili');?></a><br>
		<a class="button-asaffili-import" id="button-asaffili-head-print" data-first="<?php echo esc_attr($post->ID);?>"><?php echo esc_html_e('Print Header','asaffili');?></a><br>
  		<a class="button-asaffili-import" id="button-asaffili-import-file2" data-first="<?php echo esc_attr($post->ID);?>"><?php echo esc_html_e('Import Datafeed','asaffili');?></a><br>
  		<div id="asaffili-read-import-wrapper"></div>
  		<?php
	}  
  

	function asaffili_imports_meta_stat()
	{		
		global $post;
		global $wpdb;
  	
  		echo "<p>".esc_html_x('Last Import','','asaffili')." ".esc_html($post->_asaffili_last_import)."</p>";
  		
  		$sql="select count(*) as anzahl from ".$wpdb->prefix."posts where post_parent=".$post->ID;
  		$row=$wpdb->get_row($sql);
  		echo "<p>".__('Count Rows','asaffili')." ".$row->anzahl."<br>";
  		echo "<a id=\"button-asaffili-stat-delposts\" data-first=\"".esc_attr($post->ID)."\">".esc_html_x('Delete All','','asaffili')."</a></p>";
  		
  		$sql="select count(*) as anzahl from ".$wpdb->prefix."asaffili_catid where import_id=".$post->ID;
  		$row=$wpdb->get_row($sql);
  		echo "<p>".esc_html_x('Count Category','','asaffili')." ".esc_html($row->anzahl)."<br>";
  		echo "<a id=\"button-asaffili-stat-delcat\" data-first=\"".esc_attr($post->ID)."\">".esc_html_x('Delete All','','asaffili')."</a></p>";
  		
  		//alte Datensätze
  		$current_date=strftime("%Y-%m-%d %H:%M",time());
  		$sql="select count(*) as anzahl from ".$wpdb->prefix."posts where post_modified<'".$post->_asaffili_last_import."' and post_parent=".$post->ID;
  		
  		$row=$wpdb->get_row($sql);
  		echo "<p>".esc_html_x('Count old Rows','','asaffili')." ".esc_html($row->anzahl)."<br>";
  		echo "<a id=\"button-asaffili-stat-delpostsold\" data-first=\"".esc_attr($post->ID)."\">".esc_html_x('Delete All','','asaffili')."</a></p>";
  		
  		
	}  
	
	
	function asaffili_imports_meta_catid()
	{		
		global $wp;
		global $wpdb;
		global $post;			
		$custom = get_post_custom($post->ID);
		if(isset($custom["importsource"][0]))$importsource = $custom["importsource"][0];else $importsource='asaffili';
	
	
		?>
		<style>
		.asaffili-button
		{
			height:22px;
			display:block;
			border:solid 1px #afafaf;
			color:#333;
			background-color:#ddd;
			padding:4px;
			padding-left:30px;
			padding-right:30px;
		}	
		.asaffili_new_cat_container
		{
			background-color:#d0d0d0;
			padding:8px;
			display:none;
		}
		</style>
		<div id="asaffili-setcatid-ausgabe-fields"></div>
		<script>
			function set_catid(id)
			{
				jQuery('#temp_asaffili_new_catid').val(jQuery('#asaffili_new_cat_id'+id).val());
				jQuery('#temp_asaffili_import_id').val(id);
    			jQuery("#button-asaffili-set-catid").trigger("click");    		
			}
			function del_catid(id)
			{		
				jQuery('#temp_asaffili_import_id').val(id);
    			jQuery("#button-asaffili-del-catid").trigger("click");    		
			}
		
			function noimport_catid(id)
			{		
				jQuery('#temp_asaffili_import_id').val(id);
    			jQuery("#button-asaffili-noimport-catid").trigger("click");
    		
			}
		
			function set_new_catid(id)
			{
				jQuery('#temp_asaffili_top_catid').val(jQuery('#asaffili_top_catid'+id).val());
				jQuery('#temp_asaffili_new_cat_str').val(jQuery('#asaffili_new_cat_str'+id).val());
				jQuery('#temp_asaffili_import_id').val(id);
    			jQuery("#button-asaffili-set-new-cat").trigger("click");    		    		
			}
		</script>
		<?php
		
	 	$cat_form_str=asaffili_get_cat_list_form(0,"",0);
	
	
		$sql="select * from ".$wpdb->prefix."asaffili_catid where cat_id=0 and import_id=".$post->ID." order by created limit 0,300";
		$results=$wpdb->get_results($sql);
		echo "<input type=\"hidden\" name=\"temp_asaffili_new_catid\" id=\"temp_asaffili_new_catid\"/>\n";
		echo "<input type=\"hidden\" name=\"temp_asaffili_import_id\" id=\"temp_asaffili_import_id\"/>\n";
		echo "<input type=\"hidden\" name=\"temp_asaffili_url\" id=\"temp_asaffili_url\" value=\"".home_url( add_query_arg( NULL, NULL ))."\"/>\n";
		echo "<input type=\"hidden\" name=\"temp_asaffili_importsource\" id=\"temp_asaffili_importsource\" value=\"".esc_url($importsource)."\"/>\n";
		echo "<input type=\"hidden\" name=\"temp_asaffili_top_catid\" id=\"temp_asaffili_top_catid\"/>\n";
		echo "<input type=\"hidden\" name=\"temp_asaffili_new_cat_str\" id=\"temp_asaffili_new_cat_str\"/>\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "	<th>".esc_html_x('Category','','asaffili')."</th>\n";
		echo "	<th>Shop-Id</th>\n";
		echo "	<th>Cat-Id</th>\n";
		echo "</tr>\n";
		foreach($results as $item)
		{
			echo "<tr>\n";
			echo "	<td>".esc_html($item->cat)."</td>\n";
			echo "  <td>".esc_html($item->shop_id)."</td>\n";		
			echo "  <td>\n";		
				echo "	<select id=\"asaffili_new_cat_id".$item->id."\" name=\"asaffili_new_cat_id".$item->id."\">\n";
				echo $cat_form_str;
				echo "  </select>\n";
			
			echo "  </td>\n";
			echo "  <td>\n";
				echo "<a class=\"btn btn-primary asaffili-button\" onClick=\"set_catid(".$item->id.")\">".esc_html_x('Save','','asaffili')."</a>\n";
	 			echo "<a class=\"btn\" style=\"display:none;\" id=\"button-asaffili-set-catid\">Test</a>\n"; 		
	 		
			echo "  </td>\n";
			echo "  <td>\n";
				echo "<a class=\"btn btn-primary asaffili-button\" onClick=\"del_catid(".$item->id.")\">".esc_html_x('Delete','','asaffili')."</a>\n";
				echo "<a class=\"btn\" style=\"display:none;\" id=\"button-asaffili-del-catid\">Test</a>\n";	 		
			echo "  </td>\n";
			echo "  <td>\n";
				echo "<a class=\"btn btn-primary asaffili-button\" onClick=\"noimport_catid(".$item->id.")\">".esc_html_x('No Import','','asaffili')."</a>\n";
				echo "<a class=\"btn\" style=\"display:none;\" id=\"button-asaffili-noimport-catid\">Test</a>\n";
			echo "  </td>\n";
			echo "  <td>\n";
				echo "<a onClick=\"asaffili_new_cat_id_toggle(".$item->id.")\">".esc_html_x('Toogle to New category','','asaffili')."</a>\n";	 		
			echo "  </td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "  <td colspan=\"6\">\n";
				echo "<div class=\"asaffili_new_cat_container\" id=\"asaffili_new_cat_container".esc_attr($item->id)."\">\n";
				echo __('Parent Category','asaffili');
				echo "	<select id=\"asaffili_top_catid".esc_attr($item->id)."\" name=\"asaffili_new_catid".esc_attr($item->id)."\">\n";
				echo $cat_form_str;
				echo "  </select>\n";
				echo __('New Category','asaffili');
				echo "<input type=\"text\" id=\"asaffili_new_cat_str".esc_attr($item->id)."\" name=\"asaffili_new_cat_str".esc_attr($item->id)."\" size=\"30\">\n";
				echo "<a class=\"btn btn-primary\" onClick=\"set_new_catid(".$item->id.")\">".esc_html_x('Create Category','','asaffili')."</a>\n";
				echo "<a class=\"btn btn-primary\" style=\"display:none\" id=\"button-asaffili-set-new-cat\">Test</a>\n";
				echo "</div>\n";
			echo "  </td>\n";
			echo "</tr>\n";
		
		}
		echo "</table>\n";
		?>
		<script>
		function asaffili_new_cat_id_toggle(id)
	 	{
    	
    		var y = document.getElementsByClassName("asaffili_new_cat_container");
    		var i;
			for (i = 0; i < y.length; i++) {
    			y[i].style.display = "none";
			}
    	
    		var x = document.getElementById("asaffili_new_cat_container"+id);
    		if (x.style.display === "none")
    		{
        		x.style.display = "block";
    		} else {
        		x.style.display = "none";
    		}
		}
		</script>
		<?php
	}


  


	function asaffili_imports_save_details($post_id)
	{
  		
	  	$post_type = get_post_type($post_id);

		if($post_type=="asaffili_imports")
		{
  			//if(isset($_POST["import_url"]))	$import_url= strip_tags(stripslashes(filter_var($_POST["import_url"])));else $import_url="";
  			if(isset($_POST["import_url"]))	$import_url= esc_url_raw($_POST["import_url"]);else $import_url="";
  			if(isset($_POST["file_compression"]))	$file_compression=sanitize_text_field($_POST["file_compression"]);else $file_compression="";
  			if(isset($_POST["suchbegriff"]))	$suchbegriff=sanitize_text_field($_POST["suchbegriff"]);else $suchbegriff="";
  			if(isset($_POST["limit"]))	$limit=sanitize_text_field($_POST["limit"]);else $limit="";
  			if(isset($_POST["anfentfernen"]))	$anfentfernen=sanitize_text_field($_POST["anfentfernen"]);else $anfentfernen="";
  			if(isset($_POST["zeichen_entfernen"]))	$zeichen_entfernen=sanitize_text_field($_POST["zeichen_entfernen"]);else $zeichen_entfernen="";
  			if(isset($_POST["utf8encode"]))	$utf8encode=sanitize_text_field($_POST["utf8encode"]);else $utf8encode="";
  			if(isset($_POST["utf8decode"]))	$utf8decode=sanitize_text_field($_POST["utf8decode"]);else $utf8decode="";
  			if(isset($_POST["separator"]))	$separator=sanitize_text_field($_POST["separator"]);else $separator="";
  			if(isset($_POST["separator_image"]))	$separator_image=sanitize_text_field($_POST["separator_image"]);else $separator_image="";
  			if(isset($_POST["default_cat"])) $default_cat=sanitize_text_field($_POST["default_cat"]);else $default_cat=0;
  			
  			  			
  			update_post_meta($post_id, "import_url", $import_url);
  			update_post_meta($post_id, "file_compression",$file_compression);  			
  			update_post_meta($post_id, "suchbegriff", $suchbegriff);  			
  			update_post_meta($post_id, "limit", $limit);
  			update_post_meta($post_id, "anfentfernen",$anfentfernen);  			
  			update_post_meta($post_id, "zeichen_entfernen", $zeichen_entfernen);  			
  			update_post_meta($post_id, "utf8encode", $utf8encode);
  			update_post_meta($post_id, "utf8decode", $utf8decode);  			
  			update_post_meta($post_id, "separator", $separator);
  			update_post_meta($post_id, "separator_image", $separator_image);
  			update_post_meta($post_id, "default_cat",$default_cat);
  			
  			if(isset($_POST["field1"])) update_post_meta($post_id, "field1", sanitize_text_field($_POST["field1"]));
  			if(isset($_POST["field2"])) update_post_meta($post_id, "field2", sanitize_text_field($_POST["field2"]));
  			if(isset($_POST["field3"])) update_post_meta($post_id, "field3", sanitize_text_field($_POST["field3"]));
  			if(isset($_POST["field4"])) update_post_meta($post_id, "field4", sanitize_text_field($_POST["field4"]));
  			if(isset($_POST["field5"])) update_post_meta($post_id, "field5", sanitize_text_field($_POST["field5"]));
  			if(isset($_POST["field6"])) update_post_meta($post_id, "field6", sanitize_text_field($_POST["field6"]));
  			if(isset($_POST["field7"])) update_post_meta($post_id, "field7", sanitize_text_field($_POST["field7"]));
  			if(isset($_POST["field8"])) update_post_meta($post_id, "field8", sanitize_text_field($_POST["field8"]));
  			if(isset($_POST["field9"])) update_post_meta($post_id, "field9", sanitize_text_field($_POST["field9"]));
  			if(isset($_POST["field10"])) update_post_meta($post_id, "field10", sanitize_text_field($_POST["field10"]));
  			if(isset($_POST["field11"])) update_post_meta($post_id, "field11", sanitize_text_field($_POST["field11"]));
  			if(isset($_POST["field12"])) update_post_meta($post_id, "field12", sanitize_text_field($_POST["field12"]));
  			if(isset($_POST["field13"])) update_post_meta($post_id, "field13", sanitize_text_field($_POST["field13"]));
  			if(isset($_POST["field14"])) update_post_meta($post_id, "field14", sanitize_text_field($_POST["field14"]));
  			if(isset($_POST["field15"])) update_post_meta($post_id, "field15", sanitize_text_field($_POST["field15"]));
  			if(isset($_POST["field16"])) update_post_meta($post_id, "field16", sanitize_text_field($_POST["field16"]));
  			if(isset($_POST["field17"])) update_post_meta($post_id, "field17", sanitize_text_field($_POST["field17"]));
  			if(isset($_POST["field18"])) update_post_meta($post_id, "field18", sanitize_text_field($_POST["field18"]));
  			if(isset($_POST["field19"])) update_post_meta($post_id, "field19", sanitize_text_field($_POST["field19"]));
  			if(isset($_POST["field20"])) update_post_meta($post_id, "field20", sanitize_text_field($_POST["field20"]));
  			if(isset($_POST["field21"])) update_post_meta($post_id, "field21", sanitize_text_field($_POST["field21"]));
  			if(isset($_POST["field22"])) update_post_meta($post_id, "field22", sanitize_text_field($_POST["field22"]));
  			if(isset($_POST["field23"])) update_post_meta($post_id, "field23", sanitize_text_field($_POST["field23"]));
  			if(isset($_POST["field24"])) update_post_meta($post_id, "field24", sanitize_text_field($_POST["field24"]));
  			if(isset($_POST["field25"])) update_post_meta($post_id, "field25", sanitize_text_field($_POST["field25"]));
  			if(isset($_POST["field26"])) update_post_meta($post_id, "field26", sanitize_text_field($_POST["field26"]));
  			if(isset($_POST["field27"])) update_post_meta($post_id, "field27", sanitize_text_field($_POST["field27"]));
  			if(isset($_POST["field28"])) update_post_meta($post_id, "field28", sanitize_text_field($_POST["field28"]));
  			if(isset($_POST["field29"])) update_post_meta($post_id, "field29", sanitize_text_field($_POST["field29"]));
  			if(isset($_POST["field30"])) update_post_meta($post_id, "field30", sanitize_text_field($_POST["field30"]));
  		
  			
  			if(isset($_POST["shop_id"]))	$shop_id=sanitize_text_field($_POST["shop_id"]);else $shop_id=0;
  			
  			update_post_meta($post_id, "shop_id",$shop_id);
  			if($shop_id>0)
  			{
				$shop=get_post($shop_id);
				update_post_meta($post_id, "shop_title",$shop->post_title);
					
				update_post_meta($post_id, "shop_url",$shop->_asaffili_shops_url);
					
			}else
			{
				if(isset($_POST["shop_title"]))	update_post_meta($post_id, "shop_title",sanitize_text_field($_POST["shop_title"]));
  				if(isset($_POST["shop_url"]))	update_post_meta($post_id, "shop_url",sanitize_text_field($_POST["shop_url"]));
			}
			
  				
  			
  			
		}	
	}  





	function asaffili_import_scripts()
	{	
	
		wp_enqueue_script(
		'asaffili-import-script',
		plugins_url('scripts.js',__FILE__),array('jquery'));
	
		$asaffili_importObject = array('ajaxurl' => admin_url('admin-ajax.php'));
	
		wp_localize_script(
		'asaffili-import-script',
		'asaffili_ajaxurl',
		$asaffili_importObject);
	
	}





	



	function ajax_asaffili_set_catid()
	{
		global $wpdb;	
		$id=		sanitize_text_field($_REQUEST["id"]);
		$newcatid=	sanitize_text_field($_REQUEST["newcatid"]);
		$url = 		sanitize_text_field($_REQUEST["url"]);
		
    	$sql="update ".$wpdb->prefix."asaffili_catid set cat_id=".$newcatid." where id=".$id;	
		$wpdb->query($sql);  		
		die();
	}



	function ajax_asaffili_del_catid()
	{
		global $wpdb;
	
		$id=	sanitize_text_field($_REQUEST["id"]);	
		$url = 	sanitize_text_field($_REQUEST["url"]);
		
		$sql="delete from ".$wpdb->prefix."asaffili_catid where id=".$id;	
		$wpdb->query($sql);  		
		die();
	}


	function ajax_asaffili_noimport_catid()
	{
		global $wpdb;
	
		$id=		sanitize_text_field($_REQUEST["id"]);	
		$url = 		sanitize_text_field($_REQUEST["url"]);
		
		$sql="update ".$wpdb->prefix."asaffili_catid set cat_id=-1 where id=".$id;	
		$wpdb->query($sql);  		
		die();
	}
	

	function ajax_asaffilisetnewcatstr()
	{
		global $wpdb;	
		$id=	sanitize_text_field($_REQUEST["id"]);
		$topcatid=	sanitize_text_field($_REQUEST["topcatid"]);
		$newcatstr=	sanitize_text_field($_REQUEST["newcatstr"]);
		$importsource=	sanitize_text_field($_REQUEST["importsource"]);
		$url = 			sanitize_text_field($_REQUEST["url"]);	
	
		$result=wp_insert_term($newcatstr,'asaffili_product_category',array('parent'=>$topcatid));
	
		if($result["term_id"]>0)
		{
			//Kategorie verknüpfen
			$sql="update ".$wpdb->prefix."asaffili_catid set cat_id=".$result["term_id"]." where id=".$id;	
			$wpdb->query($sql);  
	
			echo "Ergebnis: ".$result["term_id"];	
		}		
		die();
	}

	
	
	
	
	function ajax_asaffili_head_print()
	{
		echo "Dateilesen gestartet<br>";
	
		$id=sanitize_text_field($_REQUEST["id"]);
		
		$result=get_post($id);
		$source=$result->import_url;
 		$file_compression=$result->file_compression;
		$separator = $result->separator; 	 		
  		

		//erste beiden Zeilen ausgeben
		$upload_dir=wp_upload_dir();		
		$dest=$upload_dir['basedir']."/asaffili/".$id.".csv";
		$fd=@fopen($dest,"r");
	  	if($fd==false)
  		{
    		echo "Datei $file konnte nicht geöffnet werden.<br>\n";
    		exit();
  		}else
  		{
    		
    		$buffer=fgets($fd,32000);
    		if($separator=="tab")$pieces=explode("\t",$buffer);else $pieces=explode_own($separator,$buffer);
    	
    		$buffer1=fgets($fd,32000);
    		if($separator=="tab")$pieces1=explode("\t",$buffer1);else $pieces1=explode_own($separator,$buffer1);
    	
    		$field_nr=0;
    		foreach($pieces as $piece)
    		{
				$field_nr++;
				echo "<br><b>Feld ".$field_nr.": ".$piece."</b> (".$pieces1[$field_nr-1].")";
			}
    	
    		fclose($fd);
    
	    }	
		
		die();
	}




	function ajax_asaffili_read_import()
	{
		echo "Dateilesen gestartet<br>";
	
		$id=sanitize_text_field($_REQUEST["id"]);
		
		$result=get_post($id);
		$source=$result->import_url;
		
 		$file_compression=$result->file_compression;
		$separator = $result->separator; 	 		
  		$fp=fopen($source,"r");
  		if($fp==false)
  		{
    		echo "Fehler Öffnen der Originaldatei\n";    		   
    		exit();
  		}
  		
  		 		
		if($file_compression==1)
		{	
			$upload_dir=wp_upload_dir();		
			$dest=$upload_dir['basedir']."/asaffili/".$id.".zip";					
			copy($source,$dest);

			$zip = zip_open($dest);

			if (is_resource($zip))
			{
				echo "Zip geöffnet\n";
				$zip_file=zip_read($zip);
				echo zip_entry_filesize($zip_file);
				echo zip_entry_name($zip_file);
			
				zip_entry_open($zip,$zip_file);
				$s=zip_entry_read($zip_file,zip_entry_filesize($zip_file));
			
				$upload_dir=wp_upload_dir();		
				$dest1=$upload_dir['basedir']."/asaffili/".$id.".csv";
				
				$fz=fopen($dest1,"w");
				echo "<br>Anzahl: ".strlen($s);
				fputs($fz,$s,strlen($s));
				fclose($fz);
				zip_close($zip);
				$dest=$dest1;
			}

		}else
		{
			$upload_dir=wp_upload_dir();		
			$dest=$upload_dir['basedir']."/asaffili/".$id.".csv";				
	
	  		$fz=fopen($dest,"w");
  			if($fz==false)
  			{
				echo "Fehler Öffnen $dest";
			}
  			while(!feof($fp))
  			{
    			$buffer=fgets($fp,4096);
    			fputs($fz,$buffer);
  			}
  		
  			echo "Datei $t_url gelesen.\n\n";
  			fclose($fp);
  	
		}  		
  	
  	
  	
  		chmod($dest,0777);


		//erste beiden Zeilen ausgeben
	
		$fd=@fopen($dest,"r");
  		if($fd==false)
  		{
    		echo "Datei $file konnte nicht geöffnet werden.<br>\n";
    		exit();
  		}else
  		{
    		$ii=0;
    		$i_insert=0;
    		$i_update=0;
    		$i_ohne_rubrik=0;

    		$buffer=fgets($fd,32000);
    		if($separator=="tab")$pieces=explode("\t",$buffer);else $pieces=explode_own($separator,$buffer);
    	
    		$buffer1=fgets($fd,32000);
    		if($separator=="tab")$pieces1=explode("\t",$buffer1);else $pieces1=explode_own($separator,$buffer1);
    	
    		$field_nr=0;
    		foreach($pieces as $piece)
    		{
				$field_nr++;
				echo "<br><b>Feld ".$field_nr.": ".$piece."</b> (".$pieces1[$field_nr-1].")";
			}
    	
    		fclose($fd);
    
    	}	
			
		die();
	}






	function ajax_asaffili_import_file2()
	{
		//echo "Import gestartet.<br>";
	
		$id=	sanitize_text_field($_REQUEST["id"]);
		$start=	sanitize_text_field($_REQUEST["start"]);
		$step=	sanitize_text_field($_REQUEST["step"]);
		$end=$start+$step;
		
		$result_import=get_post($id);
		$source=$result_import->import_url;
		$separator = $result_import->separator;
		$separator_image = $result_import->separator_image;
		$limit = $result_import->limit;
		$zeichen_entfernen = $result_import->zeichen_entfernen;
		$utf8decode = $result_import->utf8decode;
		$utf8encode = $result_import->utf8encode;
		$location = $result_import->location;
		$location_lat = $result_import->location_lat;
		$location_lng = $result_import->location_lng;
		$shop_id=		$result_import->shop_id;
		$shop_title = $result_import->shop_title;
		$shop_url = $result_import->shop_url;
		$default_cat=$result_import->default_cat;
		
		$post_parent=$id;
	
		    	
    	$upload_dir=wp_upload_dir();		
		$dest=$upload_dir['basedir']."/asaffili/".$id.".csv";
		  	
    
      	
    	$fd=@fopen($dest,"r");
  		if($fd==false)
  		{
    		echo "Lesefehler";
    		/*
    		Fehler-Status setzen
    		*/
			die();	
    		
  		}else
  		{
    		$stat_gesamt=0;
    		$stat_insert=0;
    		$stat_update=0;
    		$stat_delete=0;
    		
    		$current_date=strftime("%Y-%m-%d %H:%M",time());
    		
    		fgets($fd,32000);

    		$new_status=2;
		
			global $wpdb;
			//Neue Id holen für Insert
			$sql="select max(ID) as id from ".$wpdb->prefix."posts";
		
			$result=$wpdb->get_row($sql);
			$new_id=$result->id;
					
			$abbruch=false;
		
    		
    		while(!$abbruch and !feof($fd) and $stat_gesamt<$end)
    		{
    			$akt_time=time();
    			$stat_gesamt++;
    		
    			$buffer = fgets($fd, 32000);
    			//Ende des Files prüfen
    			if(feof($fd))
    			{				
					$end=-1;
				}
			
    			if($limit>0)
    			{
					if($stat_gesamt>$limit)
					{
						$abbruch=true;
						$end=-1;
					}
				}
    			    		
   				if(strlen($buffer)<10)break;
   					
       			
        	
				//$pieces=explode($separator,$buffer);	
				if($separator=="tab")$pieces=explode("\t",$buffer);else $pieces=explode_own($separator,$buffer);
							
				if($stat_gesamt>=$start and $stat_gesamt<$end)
				{
				
       				$data->artnumber="";
					$data->title="";
					$data->desc="";
					$data->deeplink="";
					$data->image="";
					$data->cat="";
					$data->preis="";
					$data->preis_old="";
					$data->preis_g="";
					$data->shipping="";
					$data->waehrung="";
					$data->programid="";
					$data->preis_old="";
					
				
        			//Felder auswerten
        			
        			if($result_import->field1!='leer') $data=		asaffili_import_file_get_field($result_import->field1,$data,$pieces[0]);
        			if($result_import->field2!='leer') $data=		asaffili_import_file_get_field($result_import->field2,$data,$pieces[1]);
        			if($result_import->field3!='leer') $data=		asaffili_import_file_get_field($result_import->field3,$data,$pieces[2]);
        			if($result_import->field4!='leer') $data=		asaffili_import_file_get_field($result_import->field4,$data,$pieces[3]);
        			if($result_import->field5!='leer') $data=		asaffili_import_file_get_field($result_import->field5,$data,$pieces[4]);
        			if($result_import->field6!='leer') $data=		asaffili_import_file_get_field($result_import->field6,$data,$pieces[5]);
        			if($result_import->field7!='leer') $data=		asaffili_import_file_get_field($result_import->field7,$data,$pieces[6]);
        			if($result_import->field8!='leer') $data=		asaffili_import_file_get_field($result_import->field8,$data,$pieces[7]);
        			if($result_import->field9!='leer') $data=		asaffili_import_file_get_field($result_import->field9,$data,$pieces[8]);
        			if($result_import->field10!='leer') $data=		asaffili_import_file_get_field($result_import->field10,$data,$pieces[9]);
        			if($result_import->field11!='leer') $data=		asaffili_import_file_get_field($result_import->field11,$data,$pieces[10]);
        			if($result_import->field12!='leer') $data=		asaffili_import_file_get_field($result_import->field12,$data,$pieces[11]);
        			if($result_import->field13!='leer') $data=		asaffili_import_file_get_field($result_import->field13,$data,$pieces[12]);
        			if($result_import->field14!='leer') $data=		asaffili_import_file_get_field($result_import->field14,$data,$pieces[13]);
        			if($result_import->field15!='leer') $data=		asaffili_import_file_get_field($result_import->field15,$data,$pieces[14]);
        			if($result_import->field16!='leer') $data=		asaffili_import_file_get_field($result_import->field16,$data,$pieces[15]);
        			if($result_import->field17!='leer') $data=		asaffili_import_file_get_field($result_import->field17,$data,$pieces[16]);
        			if($result_import->field18!='leer') $data=		asaffili_import_file_get_field($result_import->field18,$data,$pieces[17]);
        			if($result_import->field19!='leer') $data=		asaffili_import_file_get_field($result_import->field19,$data,$pieces[18]);
        			if($result_import->field20!='leer') $data=		asaffili_import_file_get_field($result_import->field20,$data,$pieces[19]);
        			if($result_import->field21!='leer') $data=		asaffili_import_file_get_field($result_import->field21,$data,$pieces[20]);
        			if($result_import->field22!='leer') $data=		asaffili_import_file_get_field($result_import->field22,$data,$pieces[21]);
        			if($result_import->field23!='leer') $data=		asaffili_import_file_get_field($result_import->field23,$data,$pieces[22]);
        			if($result_import->field24!='leer') $data=		asaffili_import_file_get_field($result_import->field24,$data,$pieces[23]);
        			if($result_import->field25!='leer') $data=		asaffili_import_file_get_field($result_import->field25,$data,$pieces[24]);
        			if($result_import->field26!='leer') $data=		asaffili_import_file_get_field($result_import->field26,$data,$pieces[25]);
        			if($result_import->field27!='leer') $data=		asaffili_import_file_get_field($result_import->field27,$data,$pieces[26]);
        			if($result_import->field28!='leer') $data=		asaffili_import_file_get_field($result_import->field28,$data,$pieces[27]);
        			if($result_import->field29!='leer') $data=		asaffili_import_file_get_field($result_import->field29,$data,$pieces[28]);
        			if($result_import->field30!='leer') $data=		asaffili_import_file_get_field($result_import->field30,$data,$pieces[29]);
					
					$data->preis_g=0;
					$data->programmid=0;
					
					$artnumber=	trim($data->artnumber,$result_import->zeichen_entfernen);
					$title=		trim($data->title,$result_import->zeichen_entfernen);
					$desc=		trim($data->desc,$result_import->zeichen_entfernen);
					$deeplink=	trim($data->deeplink,$result_import->zeichen_entfernen);
					$image=		trim($data->image,$result_import->zeichen_entfernen);
					$cat=		trim($data->cat,$result_import->zeichen_entfernen);
					$preis=		trim($data->preis,$result_import->zeichen_entfernen);
					$preis_old= trim($data->preis_old,$result_import->zeichen_entfernen);
					$preis_g=	trim($data->preis_g,$result_import->zeichen_entfernen);
					$shipping=	trim($data->shipping,$result_import->zeichen_entfernen);
					$affiliid=	trim($data->programid,$result_import->zeichen_entfernen);
       				
					if($result_import->anfentfernen==1)
					{
						$artnumber=	trim($artnumber,'"');				
						$title=		trim($title,'"');				
						$desc=		trim($desc,'"');
						$deeplink=	trim($deeplink,'"');
						$image=		trim($image,'"');
						$cat=		trim($cat,'"');	
						$preis=		trim($preis,'"');
						$preis_old= trim($preis_old,'"');
						$preis_g=	trim($preis_g,'"');
						$shipping=	trim($shipping,'"');
						$affiliid=	trim($affiliid,'"');				
					}					
			
				
					if($separator_image!="")
					{
						$image_pieces=explode($separator_image,$image);
						$image=$image_pieces[0];
						$image=trim($image,"'");	
					}
					
					
					
					
					$artnumber=	trim($artnumber);
					$title=		trim($title);
					$desc=		trim($desc);
					$deeplink=	trim($deeplink);
				
				
					$image=		trim($image);
					$cat=		trim($cat);
					$preis=		trim($preis);
					$preis_g=	trim($preis_g);
					$preis_old= trim($preis_old);
					$shipping=	trim($shipping);
					$affiliid=  trim($affiliid);
				
					$preis=strtr($preis,',','.');
					$preis_old=strtr($preis_old,',','.');
					$shipping=strtr($shipping,',','.');	
					
					
					
					
					//$alias = JFilterOutput::stringURLSafe($title);
				
					if($result_import->utf8encode==1)
					{
						$artnumber=	utf8_encode($artnumber);
						$title=		utf8_encode($title);
						$desc=		utf8_encode($desc);
						$deeplink=	utf8_encode($deeplink);
						$image=		utf8_encode($image);
						$cat=		utf8_encode($cat);					
						$preis=		utf8_encode($preis);
						$preis_g=	utf8_encode($preis_g);
						$shipping=	utf8_encode($shipping);
						$preis_old= utf8_encode($preis_old);	
					}
				
					if($result_import->utf8decode==1)
					{
						$artnumber=	utf8_decode($artnumber);
						$title=		utf8_decode($title);
						$desc=		utf8_decode($desc);
						$deeplink=	utf8_decode($deeplink);
						$image=		utf8_decode($image);
						$cat=		utf8_decode($cat);					
						$preis=		utf8_decode($preis);
						$preis_g=	utf8_decode($preis_g);
						$shipping=	utf8_decode($shipping);	
						$preis_old= utf8_decode($preis_old);
					}	
					
					
					
					//Texte bearbeiten
					$title=str_replace("\"","",$title);
					$title=str_replace("'","´",$title);
					$desc=str_replace("\"","",$desc);
					$desc=str_replace("'","´",$desc);
					$image=str_replace("\"","",$image);
					$preis=str_replace(",",".",$preis);
					$cat = str_replace("'","´",$cat);
        			$fehler=0;
        	
        	
        			if($image=="")
        			{
						$image=ASAFFILI_PLUGIN_URL."assets/images/asaffili-products-no-image.png";
					}
        			//artnumber umwandeln
        			$artnumber=$result_import->ID."-".$artnumber;
        	
        		     			
        	
        	
        			//Themen setzen
        		
        			if($fehler==0)
      				{
        				//Thema setzen      	
       					$sql= "select * from ".$wpdb->prefix."asaffili_catid where cat='".$cat."'";        	
      		  			$result_cat=$wpdb->get_results($sql);        				
        				if($wpdb->num_rows>0)
        				{
						
							if($result_cat[0]->cat_id>0)
							{
								//Kategorie prüfen
							
								$sql = "select * from ".$wpdb->prefix."terms where term_id=".$result_cat[0]->cat_id;
								$result_test=$wpdb->get_results($sql);
								if($wpdb->num_rows>0)
								{
									$catid=$result_cat[0]->cat_id;					
								}else
								{											
									//nicht vorhanden Cat-Verknüpfung löschen
									$catid=0;
									$sql="update ".$wpdb->prefix."asaffili_catid set cat_id=0 where cat_id=".$result_cat[0]->cat_id;
									$wpdb->query($sql);										
								}
							
								
							
							}else
							{
								$catid=0;
							}
                    
						}else
						{
							//Neue catlink einfügen
							$created=strftime("%Y-%m-%d %H:%M:%S",time());
				
							$sql= "insert into ".$wpdb->prefix."asaffili_catid (cat,created,import_id) values ('$cat','$created',$result_import->ID)";
					
							$wpdb->query($sql);
          					$catid=0;
          				
          				}
					}
			
					if($catid==0 and $default_cat>0)$catid=$default_cat;
					if($catid>0)
        			{
				
						$search_vorhanden=1;
						/*
						bleibt gelöscht
						if($result_import->searchphrase!="")
						{
							$search_vorhanden=0;
							$pieces=explode(",",$result_import->searchphrase);
							for($i=0;$i<count($pieces);$i++)
							{
								if(strpos(strtolower($title),strtolower($pieces[$i]))>0)
								{
									$search_vorhanden=1;
								}
							}
						}
						*/	
					
						if($search_vorhanden==1)
						{			
					
        					//Datum erstellen
							
						    			
        					$sql="select * from ".$wpdb->prefix."postmeta where meta_key='_asaffili_artnumber' and meta_value='".$artnumber."'";        				
        					$result=$wpdb->get_results($sql);
        					
        					if($wpdb->num_rows==0)
        					{
								//$new_id++;						
								$post_author=get_current_user_id();			
						
								$post_content=$desc;
								$post_title=$title;
								$post_excerpt=$desc;
								$post_status="publish";
								$comment_status="closed";
								$post_name=sanitize_title($post_title);
								$post_modified=$post_date;
							
								$guid=$post_name;
								$post_type='asaffili_products';
						
								//$sql="insert into ".$wpdb->prefix."posts (ID,post_author,post_date,post_content,post_title,post_excerpt,post_status,comment_status,post_name,post_modified,post_parent,guid,post_type) values (".$new_id.",'".$post_author."','".$current_date."','".$post_content."','".$post_title."','".$post_excerpt."','".$post_status."','".$comment_status."','".$post_name."','".$current_date."','".$post_parent."','".$guid."','".$post_type."')";
								//$result=$wpdb->query($sql);
								//if(!$result)echo "Fehler: ".$sql."<br>";						
							
								$my_post = array(
  									'post_title'    => wp_strip_all_tags($title),
  									'post_content'  => $desc,
  									'post_status'   => 'publish',
  									'post_author'   => get_current_user_id(),
  									'post_parent'	=> $post_parent,
  									'post_type'		=> $post_type,
  									//'post_category' => array( $catid ),
  									'meta_input' => array(
  												'_asaffili_artnumber' => $artnumber,
  												'_asaffili_preis' => $preis,
  												'_asaffili_preis_g' => $preis_g,
  												'_asaffili_preis_old' => $preis_old,
  												'_asaffili_shipping' => $shipping,
  												'_asaffili_url' => $deeplink,
  												'_asaffili_image' => $image,
  												'_asaffili_shop_title' => $shop_title,
  												'_asaffili_shop_url' => $shop_url)
								);
 								
 								
 								
								
								
								// Insert the post into the database
								$post_id=wp_insert_post($my_post);
											    
								
								//$post_id=$new_id;
								$stat_insert++;
							}else
							{
								//Datensatz vorhanden
								$row=$result[0];
								//echo "Datensatz vorhanden - ".$result[0]->post_id."<br>";
								$post_id=$result[0]->post_id;
						
								$post_author=get_current_user_id();							
						
								$post_content=$desc;
								$post_title=$title;
								$post_excerpt=$desc;
								$post_status="publish";
								$comment_status="closed";
								$post_name=sanitize_title($post_title);
								$post_modified=$post_date;
							
								$guid=$post_name;
								$post_type='asaffili_products';
								
								$sql="update ".$wpdb->prefix."posts set post_type='".$post_type."',post_title='".$post_title."',post_modified='".$current_date."',post_parent='".$post_parent."' where ID=".$post_id;
								
							
								$result=$wpdb->query($sql);
								if(!$result)echo "Fehler: ".$sql."<br>";
							
								
								$stat_update++;
							
							}
							
							//Zusätzliche Felder setzen
							update_post_meta($post_id, "_asaffili_artnumber", $artnumber);
							update_post_meta($post_id, "_asaffili_url", $deeplink);
							update_post_meta($post_id, "_asaffili_image", $image);
							update_post_meta($post_id, "_asaffili_preis", $preis);
							update_post_meta($post_id, "_asaffili_preis_g", $preis_g);
							update_post_meta($post_id, "_asaffili_preis_old", $preis_old);
							if($preis_old>0)$preis_diff=$preis_old-$preis;else $preis_diff=0;
							update_post_meta($post_id, "_asaffili_preis_diff",$preis_diff);
							
							update_post_meta($post_id, "_asaffili_shipping", $shipping);
							update_post_meta($post_id, "_asaffili_shop_title", $shop_title);
							update_post_meta($post_id, "_asaffili_shop_url", $shop_url);
							update_post_meta($post_id, "_asaffili_shop_id",	$shop_id);
								
							//Kategorie setzen							
							$sql="select * from ".$wpdb->prefix."term_relationships where object_id=".$post_id." and term_taxonomy_id=".$catid;
							$result_cattest=$wpdb->get_results($sql);
							if($wpdb->num_rows==0)
							{
								$sql="insert into ".$wpdb->prefix."term_relationships (object_id,term_taxonomy_id) values (".$post_id.",".$catid.")";
								$wpdb->query($sql);
							}	
							
						
						
						
							$sql="select * from ".$wpdb->prefix."term_relationships where object_id=".$post_id." and term_taxonomy_id=".$catid;
							$result_cattest=$wpdb->get_results($sql);
							if($wpdb->num_rows==0)
							{
								$sql="insert into ".$wpdb->prefix."term_relationships (object_id,term_taxonomy_id) values (".$post_id.",".$catid.")";
								$wpdb->query($sql);
							}
						
						
						}	
				
					}
        			
				
        	
				}
			
			}
			
			echo $stat_gesamt.",".$stat_insert.",".$stat_update.",".$start.",".$end;
			
			//update_post_meta($result_import->ID,"_asaffili_last_import",$current_date);
			//update_post_meta($result_import->ID,"_asaffili_last_import_time",time());
			
		}
		
		//echo "<br>&nbsp;<br>";
		//echo "Anzahl gelesen: ".$stat_gesamt."<br>";
		//echo "Anzahl importiert: ".$stat_insert."<br>";
		//echo "Anzahl ge&auml;ndert: ".$stat_update."<br>";
		die();
	}



	function ajax_asaffili_stat_delcat()
	{
			
		$id=sanitize_text_field($_REQUEST["id"]);
		global $wpdb;		
		$result=get_post($id);
		$source=$result->import_url; 		
		$sql="select count(*) as anzahl from ".$wpdb->prefix."asaffili_catid where import_id=".$id;
  		$row=$wpdb->get_row($sql);	
  		echo $row->anzahl;
  		$sql="delete from ".$wpdb->prefix."asaffili_catid where import_id=".$id;
  		$wpdb->query($sql);
		die();
	}
	
	
	
	function ajax_asaffili_stat_delposts()
	{			
		$id=sanitize_text_field($_REQUEST["id"]);
		global $wpdb;
		$max=20;		
		$result=get_post($id);
		$source=$result->import_url; 		
 		$sql="select ID from ".$wpdb->prefix."posts where post_parent=".$id." limit 0,".$max;
 		$results=$wpdb->get_results($sql);
 		foreach($results as $item)
 		{
			wp_delete_post($item->ID,true);
		}		
		die();
	}


	function ajax_asaffili_stat_delpostsold()
	{			
		$id=sanitize_text_field($_REQUEST["id"]);
		global $wpdb;		
		$max=100;		
		$result=get_post($id);
		$source=$result->import_url; 		
 		$sql="select ID from ".$wpdb->prefix."posts where post_modified<'".$result->_asaffili_last_import."' and post_parent=".$result->ID." limit 0,".$max;
 		$results=$wpdb->get_results($sql);
 		foreach($results as $item)
 		{
			wp_delete_post($item->ID,true);
		}		
		die();
	}
	
	
	function ajax_asaffili_import_file2_setdate()
	{			
		$id=sanitize_text_field($_REQUEST["id"]);
		global $wpdb;
		$current_date=strftime("%Y-%m-%d %H:%M",time());		
		update_post_meta($id,"_asaffili_last_import",$current_date);
		update_post_meta($id,"_asaffili_last_import_time",time());
				
		die();
	}
	
	
	
	// Tabellenkopf und -fuß um Felder erweitern
	
	function asaffili_edit_admin_columns($columns)
	{		
  		$columns = array(
    	'cb' => '<input type="checkbox" />',
    	'title' => __('Title'),    	    	
    	'lastdate' => __('LastDate'),
    	'lasttime' => __('LastTime'),
    	'date' => __('Date')
  		);
  		return $columns;
	}

	// Inhalte aus benutzerdefinierten Feldern auslesen und den Spalten hinzufügen
	
	function asaffili_post_custom_columns($column)
	{
  		global $post;
  		$custom = get_post_custom();
  		switch ($column)
  		{
    		case "lastdate": $kb_field = strftime("%d.%m.%Y %H:%M",get_post_meta($post->ID, '_asaffili_last_import_time', true ));
      		echo $kb_field;
    		break;
    		case "lasttime": $kb_field = get_post_meta($post->ID, '_asaffili_last_import_time', true );
      		echo $kb_field;
    		break;
    		
  		}
	}

	// Hinzugefügte Spalten sortierbar machen
	
	function asaffili_post_sortierbare_columns( $columns )
	{
  		
  		$columns['lasttime'] = 'lasttime';
  		return $columns;
	}

	
}



$asaffili_admin_imports_obj = new asaffili_admin_imports();

