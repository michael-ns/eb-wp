<?php
global $dfd_ronneby;
if(isset($dfd_ronneby['enable_default_addons']) && strcmp($dfd_ronneby['enable_default_addons'], '1') === 0) {
	if(!class_exists('ULT_HotSpot')) {
		class ULT_HotSpot {

			function __construct() {
				// Use this when creating a shortcode addon
				add_shortcode( 'ult_hotspot', array( $this, 'ult_hotspot_callback' ) );
				add_shortcode( 'ult_hotspot_items', array($this, 'ult_hotspot_items_callback' ) );

				// We safely integrate with VC with this hook
				add_action( 'admin_init', array( $this, 'ult_hotspot_init' ),99 );
				add_action('admin_enqueue_scripts',array($this, 'enqueue_admin_assets'),999);

				// Register CSS and JS
				add_action( 'wp_enqueue_scripts', array( $this, 'ult_hotspot_scripts' ), 1 );

				if(function_exists('add_shortcode_param')) {
					add_shortcode_param('ultimate_hotspot_param', array($this, 'ultimate_hotspot_param_callback'), get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/admin/vc_extend/js/vc-hotspot-param.js');
				}
			}

			function ultimate_hotspot_param_callback($settings, $value) {
				$dependency = vc_generate_dependencies_attributes($settings);
				$class = isset($settings['class']) ? $settings['class'] : '';
				$output = '<div class="ult-hotspot-image-wrapper '.$class.'">';
					$output .= '<img src="" class="ult-hotspot-image" alt="image"/>';
					$output .= '<div class="ult-hotspot-draggable"></div>';
					$output .= '<input type="hidden" name="'.$settings['param_name'].'" value="'.$value.'" class="ult-hotspot-positions wpb_vc_param_value" '.$dependency.'/>';
				$output .= '</div>';
				return $output;
			}

			function enqueue_admin_assets($hook) {
				if($hook == "post.php" || $hook == "post-new.php" || $hook == "edit.php"){
					wp_register_script('hotspt-admin-js', get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/admin/vc_extend/js/admin_enqueue_js.js',array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),null,true);
					wp_enqueue_script('hotspt-admin-js');
				}
			}

			function ult_hotspot_callback( $atts, $content = null ) {

				global $tooltip_continuous_animation;

				$main_img = $main_img_size = $main_img_width = $el_class = '';
				extract( shortcode_atts( array(
					'main_img'        => '',
					'main_img_size'   => '',
					'main_img_width'  => '',
					'el_class'        => '',
				), $atts ) );

				$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

				$mnimg = apply_filters('ult_get_img_single', $main_img, 'url'); 
				$cust_size = '';
				if( $main_img_size== 'main_img_custom'){
					if($main_img_width!='') {   
						$cust_size .= 'width:'.esc_attr($main_img_width).'px;';  
					}
				}
				$output  = '<div class="ult_hotspot_container ult-hotspot-tooltip-wrapper '.esc_attr($el_class).'" style="'.$cust_size.'">';
				$output .= '  <img class="ult_hotspot_image" src="'.esc_url($mnimg).'" />';
				$output .= '     <div class="utl-hotspot-items ult-hotspot-item">'.do_shortcode($content).'</div>';
				$output .= '     <div style="color:#000;" data-image="'.esc_attr($GLOBALS['hotspot_icon'].' '.$GLOBALS['hotspot_icon_bg_color'].' '.$GLOBALS['hotspot_icon_color'].' '.$GLOBALS['hotspot_icon_size'].' '.$GLOBALS['tooltip_continuous_animation']).'"></div>';
				$output .= '</div>';
				return $output;
			}
			function ult_hotspot_items_callback( $atts, $content = null ){
			   global $hotspot_icon, $hotspot_icon_bg_color, $hotspot_icon_color, $hotspot_icon_size;

				extract( shortcode_atts( array(
					'hotspot_content' 					=> '',
					'hotspot_label'                   	=> '',
					'hotspot_position'              	=> '0,0',
					'tooltip_content'                 	=> '',
					'tooltip_width'                   	=> '300',
					'tooltip_padding'                 	=> '',
					'tooltip_position'                	=> '',
					"icon_type"							=> '',
					'icon'								=> 'Defaults-map-marker',
					'icon_color'						=> '',
					'icon_style'						=> '',
					'icon_color_bg'						=> '',
					'icon_border_style'					=> '',
					'icon_color_border'					=> '',
					'icon_border_size'					=> '',
					'icon_border_radius'				=> '',
					'icon_border_spacing'				=> '',
					'icon_img'							=> '',
					'img_width'							=> '60',
					'link_style'						=> '',
					'icon_link'							=> '',
					'icon_size'							=> '',
					"alignment"							=>	"center",
					'tooltip_trigger'                 	=> '',
					'tooltip_animation'               	=> '',
					'tooltip_continuous_animation'    	=> '',
					'glow_color'						=> '',
					'enable_bubble_arrow'             	=> 'on',
					'tooltip_custom_bg_color'         	=> '#fff',
					'tooltip_custom_color'            	=> '#4c4c4c',
					'tooltip_font'                    	=> '',
					'tooltip_font_style'              	=> '',
					'tooltip_font_size'               	=> '',
					'tooltip_font_line_height'        	=> '',
					'tooltip_custom_border_size'      	=> '',
					'tooltip_align'						=> '',
				), $atts ) 	);

				//$content = wpb_js_remove_wpautop($content, false); // fix unclosed/unwanted paragraph tags in $content

				//	Animation effects
				$glow = $pulse = '';
				if( $tooltip_continuous_animation!='' ) {

					switch( $tooltip_continuous_animation ) {
						case "on": 
									$pulse = "ult-pulse";
							break;
						case "glow":
							if($glow_color !== '')
								$glow_color = 'style="background-color:'.esc_attr($glow_color).'"';
							else
								$glow_color = '';
									$glow = " <div class='ult-glow' ".esc_attr($glow_color)."></div>";
							break;
					}
				}

				if(trim($content) !== '') {
					$hotspot_content = $content;
				}


				/**    Tooltip [Content] Styling 
				 *--------------------------------------*/
				$font_args = array();
				$tooltip_content_style = '';
				$tooltip_base_style = '';

				if($tooltip_font != '') {
					$font_family = get_ultimate_font_family($tooltip_font);
					$tooltip_content_style .= 'font-family:'.esc_attr($font_family).';';
					array_push($font_args, $tooltip_font);
				}
				if($tooltip_font_style != '') { $tooltip_content_style .= get_ultimate_font_style($tooltip_font_style); }
				if($tooltip_font_size != '') { $tooltip_content_style .= 'font-size:'.esc_attr($tooltip_font_size).'px;'; }
				if($tooltip_font_line_height != '') { $tooltip_content_style .= 'line-height:'.esc_attr($tooltip_font_line_height).'px;'; }

				//  Width
				if($tooltip_width!=''){
					$tooltip_content_style .= 'width:' .esc_attr($tooltip_width). 'px;';
				}

				//  Padding
				if($tooltip_padding!=''){
					$tooltip_content_style .= $tooltip_padding;
				}

				/**
				 *    Tooltip [Base] Styling options
				 *
				 */
				//  Background
				if($tooltip_custom_bg_color!=''){ $tooltip_base_style .= 'background-color:' .esc_attr($tooltip_custom_bg_color).  ';'; }

				/*if($tooltip_theme == 'custom' ) {*/
				if($tooltip_custom_color!=''){ $tooltip_base_style .=  'color:' .esc_attr($tooltip_custom_color).  ';'; }

				//  Border Styling
				if($tooltip_custom_border_size!=''){             
				$bstyle = str_replace( '|', '', $tooltip_custom_border_size );
				$tooltip_base_style .= $bstyle;
				}
				if($tooltip_align!=''){
					$tooltip_base_style .= 'text-align:'.esc_attr($tooltip_align).';';
				}


				$data = '';
				if($tooltip_content_style!='')  { $data .= 'data-tooltip-content-style="'.esc_attr($tooltip_content_style). '"'; }
				if($tooltip_base_style!='')     { $data .= 'data-tooltip-base-style="'.esc_attr($tooltip_base_style). '"'; }

				if($enable_bubble_arrow!='' && $enable_bubble_arrow == 'on') {
				  $data .= ' data-bubble-arrow="true" '; 
				} else { 
				  $data .= ' data-bubble-arrow="false" ';
				}

				$hotspot_position = explode(',', $hotspot_position);
				if($icon_type == 'custom') {
					$temp_icon_size = ($img_width/2)-14;
				} else {
					$temp_icon_size = ($icon_size/2)-14;
					//$temp_icon_size = 0;
				}

				$hotspot_x_position = $hotspot_position[0];
				$hotspot_y_position = (isset($hotspot_position[1])) ? $hotspot_position[1] : '0';
				$tooltip_offsetY = '';

				//if($icon_size != '')  { 
					//  set offsetY for tooltip
					$tooltip_offsetY = $temp_icon_size;
				//}

				if($tooltip_animation!='')      { $data .= 'data-tooltipanimation="'.esc_attr($tooltip_animation).'"';}
				if($tooltip_trigger!='')        { $data .= 'data-trigger="'.esc_attr($tooltip_trigger).'"';}
				if($tooltip_offsetY!='')        { $data .= 'data-tooltip-offsety="'.esc_attr($tooltip_offsetY).'"';}
				if($tooltip_position!='')       { $data .= 'data-arrowposition="'.esc_attr($tooltip_position).'"';}

				$icon_animation = '';
				$icon_inline = do_shortcode('[just_icon icon_align="'.$alignment.'" icon_type="'.$icon_type.'" icon="'.$icon.'" icon_img="'.$icon_img.'" img_width="'.$img_width.'" icon_size="'.$icon_size.'" icon_color="'.$icon_color.'" icon_style="'.$icon_style.'" icon_color_bg="'.$icon_color_bg.'" icon_color_border="'.$icon_color_border.'"  icon_border_style="'.$icon_border_style.'" icon_border_size="'.$icon_border_size.'" icon_border_radius="'.$icon_border_radius.'" icon_border_spacing="'.$icon_border_spacing.'" icon_animation="'.$icon_animation.'"]');

				$url = $link_title = $target = '';

				// Hotspot has simple link
				if( $link_style == 'link' && $icon_link !='' ){
					$href 		= 	vc_build_link($icon_link);
					$url 		= 	$href['url'];
					$link_title	=	' title="'.esc_attr($href['title']).'" ';
					$target		=	' target="'.trim($href['target']).'" ';
				}

				//$output  = "<div class='ult-hotspot-item ".$pulse."' style='top:-webkit-calc(".$hotspot_x_position."% - ".$temp_icon_size."px);top:-moz-calc(".$hotspot_x_position."% - ".$temp_icon_size."px);top:calc(".$hotspot_x_position."% - ".$temp_icon_size."px);left: -webkit-calc(".$hotspot_y_position."% - ".$temp_icon_size."px);left: -moz-calc(".$hotspot_y_position."% - ".$temp_icon_size."px);left: calc(".$hotspot_y_position."% - ".$temp_icon_size."px);' >";
				$output  = "<div class='ult-hotspot-item ".esc_attr($pulse)."' style='top:-webkit-calc(".esc_attr($hotspot_x_position)."% - ".esc_attr($temp_icon_size)."px);top:-moz-calc(".esc_attr($hotspot_x_position)."% - ".esc_attr($temp_icon_size)."px);top:calc(".esc_attr($hotspot_x_position)."% - ".esc_attr($temp_icon_size)."px);left: -webkit-calc(".esc_attr($hotspot_y_position)."% - ".esc_attr($temp_icon_size)."px);left: -moz-calc(".esc_attr($hotspot_y_position)."% - ".esc_attr($temp_icon_size)."px);left: calc(".esc_attr($hotspot_y_position)."% - ".esc_attr($temp_icon_size)."px);' >";
				$output .= "  <div style='z-index: 39;position: relative;'>";

				if($link_style == 'link'){
					$output .= "   <a data-link_style='simple' class='ult-tooltip ult-tooltipstered ult-hotspot-tooltip' href='".esc_url($url)."' ".$link_title." ".$target." data-status='hide'>";
					$output .= $icon_inline;
					$output .= "  </a>";
				} else {
					$output .= "   <a data-link_style='tootip' ".$data." class='ult-tooltip ult-tooltipstered ult-hotspot-tooltip' href='#' data-status='show'>";
						$output .= $icon_inline;
						$output .= '<span class="hotspot-tooltip-content">'.esc_html( str_replace('"', '\'', $hotspot_content ) ).'</span>';
					$output .= '</a>';
				}

				$output .= '</div><!-- ICON WRAP -->';

				$output .= $glow;

				$output .= '</div>';
				return $output;
			}
			function ult_hotspot_init() {
				if(function_exists('vc_map')){
					vc_map( array(
						'name' => __('Hotspot', 'dfd'),
						'base' => 'ult_hotspot',
						'as_parent' => array('only' => 'ult_hotspot_items'),
						'content_element' => true,
						'show_settings_on_create' => true,
						'category' => 'Ultimate VC Addons',
						'icon' => 'ult_hotspot',
						'class' => 'ult_hotspot',
						'description' => __('Display Hotspot on Image.', 'dfd'),
						//'is_container'    => true,
						'params' => array(
								array(
									'type' => 'ult_img_single',
									'class' => '',
									'heading' => __('Select Hotspot Image', 'dfd'),
									'param_name' => 'main_img',
									'value' =>'',
									/*'description' => __('Add Hotspot image.', 'ultimate'),*/
									/*'holder' =>'div'*/
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Image Size', 'dfd'),
									'param_name' => 'main_img_size',
									'value' => array(
										'Default / Full Size' => 'main_img_original',
										'Custom' => 'main_img_custom',
									),
									/*'description' => __('Select Hotspot image size.', 'ultimate')*/
								),
								array(
									'type' => 'number',
									'heading' => __('Image Width', 'dfd'),
									'class' => '',
									'value' => '',
									'suffix' => 'px',
									'param_name' => 'main_img_width',
									/*'description' => __('Enter image Width, Height will calculate automatically.', 'ultimate'),*/
									'dependency' => array('element' => 'main_img_size', 'value' => 'main_img_custom' ),
								),
								array(
									'type' => 'textfield',
									'heading' => __('Extra Class Name', 'dfd'),
									'param_name' => 'el_class',
									'description' => __('Ran out of options? Need more styles? Write your own CSS and mention the class name here.', 'dfd')
								)
							),
							'js_view' => 'ULTHotspotContainerView'
					) );

					global $ultimate_hostspot_image;
					vc_map( array(
						  'name' => __('Hotspot Item', 'dfd'),
						  'base' => 'ult_hotspot_items',
						  'content_element' => true,
						  'as_child' => array('only' => 'ult_hotspot'),
						  'icon' => 'ult_hotspot',
						  'class' => 'ult_hotspot',    
						  'js_view' => 'ULTHotspotSingleView', 
						  'is_container'    => false,
						  'params' => array(
								array(
									'type' => 'ultimate_hotspot_param',
									'heading' => 'Position',
									'param_name' => 'hotspot_position',
									//'admin_label' => true
								),
								/*array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Horizontal Position', 'ultimate'),
									'param_name' => 'hotspot_x_position',
									'value' => '50',
									'admin_label' => true,
								),
								array(
									'type' => 'number',
									'class' => '',
									'admin_label' => true,
									'heading' => __('Vertical Position', 'ultimate'),
									'param_name' => 'hotspot_y_position',
									'value' => '50',
									'suffix'  => '%',
								),*/
								// Hotspot Icon
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Icon to display:', 'dfd'),
									'param_name' => 'icon_type',
									'value' => array(
										__('Font Icon Manager','dfd') => 'selector',
										__('Custom Image Icon','dfd') => 'custom',
									),
									'description' => __('Use an existing font icon or upload a custom image.', 'dfd'),
									//'dependency' => Array('element' => 'spacer', 'value' => array('line_with_icon','icon_only')),
									'group' => 'Icon',
								),
								array(
									'type' => 'icon_manager',
									'class' => '',
									'heading' => __('Select Icon ','dfd'),
									'param_name' => 'icon',
									'value' => '',
									'description' => __('Click and select icon of your choice. If you can\'t find the one that suits for your purpose','dfd').', '.__('you can','dfd').' <a href="admin.php?page=font-icon-Manager" target="_blank">'.__('add new here','dfd').'</a>.',
									'dependency' => Array('element' => 'icon_type','value' => array('selector')),
									'group' => 'Icon',
								),
								array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Size of Icon', 'dfd'),
									'param_name' => 'icon_size',
									'value' => 32,
									'min' => 12,
									'max' => 72,
									'suffix' => 'px',
									'description' => __('How big would you like it?', 'dfd'),
									'dependency' => Array('element' => 'icon_type','value' => array('selector')),
									'group' => 'Icon',
								),
								array(
									'type' => 'colorpicker',
									'class' => '',
									'heading' => __('Color', 'dfd'),
									'param_name' => 'icon_color',
									'value' => '',
									'description' => __('Give it a nice paint!', 'dfd'),
									'dependency' => Array('element' => 'icon_type','value' => array('selector')),
									'group' => 'Icon',
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Icon Style', 'dfd'),
									'param_name' => 'icon_style',
									'value' => array(
										__('Simple','dfd') => 'none',
										__('Circle Background','dfd') => 'circle',
										__('Square Background','dfd') => 'square',
										__('Design your own','dfd') => 'advanced',
									),
									'description' => __('We have given three quick preset if you are in a hurry. Otherwise, create your own with various options.', 'dfd'),
									'dependency' => Array('element' => 'icon_type','value' => array('selector')),
									'group' => 'Icon',
								),
								array(
									'type' => 'colorpicker',
									'class' => '',
									'heading' => __('Background Color', 'dfd'),
									'param_name' => 'icon_color_bg',
									'value' => '',
									'description' => __('Select background color for icon.', 'dfd'),	
									'dependency' => Array('element' => 'icon_style', 'value' => array('circle','square','advanced')),
									'group' => 'Icon',
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Icon Border Style', 'dfd'),
									'param_name' => 'icon_border_style',
									'value' => array(
										__('None','dfd') => '',
										__('Solid','dfd') => 'solid',
										__('Dashed','dfd') => 'dashed',
										__('Dotted','dfd') => 'dotted',
										__('Double','dfd') => 'double',
										__('Inset','dfd') => 'inset',
										__('Outset','dfd') => 'outset',
									),
									'description' => __('Select the border style for icon.','dfd'),
									'dependency' => Array('element' => 'icon_style', 'value' => array('advanced')),
									'group' => 'Icon',
								),
								array(
									'type' => 'colorpicker',
									'class' => '',
									'heading' => __('Border Color', 'dfd'),
									'param_name' => 'icon_color_border',
									'value' => '#333333',
									'description' => __('Select border color for icon.', 'dfd'),	
									'dependency' => Array('element' => 'icon_border_style', 'not_empty' => true),
									'group' => 'Icon',
								),
								array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Border Width', 'dfd'),
									'param_name' => 'icon_border_size',
									'value' => 1,
									'min' => 1,
									'max' => 10,
									'suffix' => 'px',
									'description' => __('Thickness of the border.', 'dfd'),
									'dependency' => Array('element' => 'icon_border_style', 'not_empty' => true),
									'group' => 'Icon',
								),
								array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Border Radius', 'dfd'),
									'param_name' => 'icon_border_radius',
									'value' => 500,
									'min' => 1,
									'max' => 500,
									'suffix' => 'px',
									'description' => __('0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'dfd'),
									'dependency' => Array('element' => 'icon_border_style', 'not_empty' => true),
									'group' => 'Icon',
								),
								array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Background Size', 'dfd'),
									'param_name' => 'icon_border_spacing',
									'value' => 50,
									'min' => 30,
									'max' => 500,
									'suffix' => 'px',
									'description' => __('Spacing from center of the icon till the boundary of border / background', 'dfd'),
									'dependency' => Array('element' => 'icon_style', 'value' => array('advanced')),
									'group' => 'Icon',
								),
								array(
									'type' => 'ult_img_single',
									'class' => '',
									'heading' => __('Upload Image Icon:', 'dfd'),
									'param_name' => 'icon_img',
									'value' => '',
									'description' => __('Upload the custom image icon.', 'dfd'),
									'dependency' => Array('element' => 'icon_type','value' => array('custom')),
									'group' => 'Icon',
								),
								array(
									'type' => 'number',
									'class' => '',
									'heading' => __('Image Width', 'dfd'),
									'param_name' => 'img_width',
									'value' => 48,
									'min' => 16,
									'max' => 512,
									'suffix' => 'px',
									'description' => __('Provide image width', 'dfd'),
									'dependency' => Array('element' => 'icon_type','value' => array('custom')),
									'group' => 'Icon',
								),

								// link style
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('On Click HotSpot Item:', 'dfd'),
									'param_name' => 'link_style',
									'value' => array(
										__('Tooltip','dfd') => 'tooltip',
										__('Simple Link','dfd') => 'link',
									),
									'description' => __('Display tooltip or just link for the hotspot icon.', 'dfd'),
								),
								array(
									'type' => 'vc_link',
									'class' => '',
									'heading' => __('Link ','dfd'),
									'param_name' => 'icon_link',
									'value' => '',
									'description' => __('Add a custom link or select existing page. You can remove existing link as well.','dfd'),
									'dependency' => Array('element' => 'link_style','value' => 'link'),
								),
								// Link style
								// TOOLTIP
								array(
									'type' => 'textarea_html',
									'class' => '',
									'value' => 'Tooltip content goes here!',
									'heading' => __('Hotspot Tooltip Content', 'dfd'),
									'param_name' => 'content',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
									'admin_label' => true,
									'edit_field_class' => 'ult_hide_editor_fullscreen vc_col-xs-12 vc_column wpb_el_type_textarea_html vc_wrapper-param-type-textarea_html vc_shortcode-param',
								),

								/*array(
									'type' => 'ult_switch',
									'class' => '',
									'heading' => __('Continuous Pulse Animation For Hotspot', 'dfd'),
									'param_name' => 'tooltip_continuous_animation',
									// 'admin_label' => true,
									'value' => 'on',
									'default_set' => true,
									'options' => array(
										'on' => array(
											'label' => __('Enable Pulse Animation?','dfd'),
											'on' => __('Yes','dfd'),
											'off' => __('No','dfd'),
										),
									),
									'description' => __('Animate tooltip continuously or not', 'dfd'),
								),*/

								// Tooltip
								array(
								  'type' => 'colorpicker',
								  'class' => '',
								  'heading' => __('Tooltip Text Color', 'dfd'),
								  'param_name' => 'tooltip_custom_color',
								  /*'edit_field_class' => 'vc_col-sm-6',*/
								  'value' => '#4c4c4c',
								  /*'dependency' => array('element' => 'tooltip_theme', 'value' => 'custom'),*/
								  'group' => 'Tooltip',
								  'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								  /*'description' => __('Select the color for tooltip text.', 'smile'),                */
								),
								array(
									'type' => 'colorpicker',
									'class' => '',
									'heading' => __('Background Color', 'dfd'),
									'param_name' => 'tooltip_custom_bg_color',
									/*'edit_field_class' => 'vc_col-sm-6',*/
									'value' => '#fff',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
									'group' => 'Tooltip',
								),
								array(
									'type' => 'number',
									'class' => '',
									'value' => '300',
									'heading' => __('Width', 'dfd'),
									'param_name' => 'tooltip_width',
									'group' => 'Tooltip',
									'suffix'  => 'px',
									'description' => __('Tooltip Default width: auto.', 'ultimate'),
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Trigger On', 'dfd'),
									'param_name' => 'tooltip_trigger',
									'value' => array('Hover' => 'hover', 'Click' => 'click'),
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
									/*'description' => __('When to display the tooltip onclick or hover', 'ultimate')*/
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Position', 'dfd'),
									'param_name' => 'tooltip_position',
									'value' => array(
										__('Top','dfd') => 'top',
										__('Bottom','dfd') => 'bottom',
										__('Left','dfd') => 'left',
										__('Right','dfd') => 'right'
									),
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
									/*'description' => __('Set position of tooltip.', 'ultimate')*/
								),

								array(
									'type' => 'ultimate_border',
									'heading' => __('Border','dfd'),
									'param_name' => 'tooltip_custom_border_size',
									'unit'     => 'px',                        //  [required] px,em,%,all     Default all
									'positions' => array(
										__('Top','dfd')     => '1',
										__('Right','dfd')   => '1',
										__('Bottom','dfd')  => '1',
										__('Left','dfd')    => '1'
									),
									//'enable_radius' => false,                   //  Enable border-radius. default true
									'radius' => array(                          
										__('Top Left','dfd')        => '3',                // use 'Top Left'
										__('Top Right','dfd')       => '3',                  // use 'Top Right'
										__('Bottom Right','dfd')    => '3',                // use 'Bottom Right'
										__('Bottom Left','dfd')     => '3'                   // use 'Bottom Left'
									),
									'label_color'   => __('Border Color','dfd'),       //  label for 'border color'   default 'Border Color'
									'label_radius'  => __('Border Radius','dfd'),        //  label for 'radius'  default 'Border Redius'
									//'label_border'  => 'Border Style',       //  label for 'style'   default 'Border Style'
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
									'type' => 'ult_switch',
									'class' => '',
									'heading' => __('Arrow', 'dfd'),
									'param_name' => 'enable_bubble_arrow',
									// 'admin_label' => true,
									'value' => 'on',
									'default_set' => true,
									'options' => array(
										'on' => array(
											'label' => __('Enable Tooltip Arrow?','dfd'),
											'on' => __('Yes','dfd'),
											'off' => __('No','dfd'),
										  ),
									  ),
									/*'description' => __('', 'smile'),*/
									/*'description' => __('Hide or Show Tooltip Arrow. Default: Hide. ', 'ultimate'),*/
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),

								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Appear Animation', 'dfd'),
									'param_name' => 'tooltip_animation',
									'value' => array(
										__('Fade','dfd')=>'fade',
										__('Grow','dfd')=>'glow',
										__('Swing','dfd')=>'swing',
										__('Slide','dfd')=>'slide',
										__('Fall','dfd')=>'fall',
										__('Euclid','dfd')=>'euclid'
									),
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
									'type' => 'ultimate_spacing',
									'heading' => __('Padding', 'dfd'),
									'param_name' => 'tooltip_padding',
									'mode'  => 'padding',                    //  margin/padding
									'unit'  => 'px',                        //  [required] px,em,%,all     Default all
									'positions' => array(                   //  Also set 'defaults'
									  __('Top','dfd')     => '',
									  __('Right','dfd')   => '',
									  __('Bottom','dfd')  => '',
									  __('Left','dfd')    => ''
									),
									'group' => 'Tooltip',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
									/*'description' => __('Thickness of the Tooltip Border. E.g. 5px 10px 5px 10px or 5px 10px etc.', 'ultimate'),*/
									/*'dependency' => array('element' => 'tooltip_custom_border_style', 'not_empty' => true),*/
								),
								// Typography
								array(
								  'type' => 'ultimate_google_fonts',
								  'heading' => __('Font Family','dfd'),
								  'param_name' => 'tooltip_font',
								  'value' => '',
								  'group' => 'Typography',
								  'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
								  'type' => 'ultimate_google_fonts_style',
								  'heading' => __('Font Style','dfd'),
								  'param_name' => 'tooltip_font_style',
								  'value' => '',
								  'group' => 'Typography',
								  'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
								  'type' => 'number',
								  'param_name' => 'tooltip_font_size',
								  'heading' => __('Font size','dfd'),
								  'value' => '12',
								  'suffix' => 'px',
								  'min' => 10,
								  'group' => 'Typography',
								  'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
								  'type' => 'number',
								  'param_name' => 'tooltip_font_line_height',
								  'heading' => __('Line Height','dfd'),
								  'value' => '18',
								  'suffix' => 'px',
								  'min' => 10,
								  'group' => 'Typography',
								  'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Text Align', 'dfd'),
									'param_name' => 'tooltip_align',
									'value' => array(
										__('Left','dfd') 	=> 'left',
										__('Center','dfd') 	=> 'center',
										__('Right','dfd') 	=> 'right',
										__('Justify','dfd') 	=> 'justify'
									),
									'group' => 'Typography',
									'dependency' => Array('element' => 'link_style','value' => 'tooltip'),
								),
								array(
									'type' => 'dropdown',
									'class' => '',
									'heading' => __('Animation For Hotspot', 'dfd'),
									'param_name' => 'tooltip_continuous_animation',
									'value' => array(
										__('None','dfd') => '',
										__('Pulse','dfd') => 'on',
										__('Glow','dfd') => 'glow',
									),
									'description' => __('Select animation effect for hotspot icon/image.', 'dfd'),
									'group' => 'Animation'
								),
								array(
									'type' => 'colorpicker',
									'heading' => __('Glow Color', 'dfd'),
									'param_name' => 'glow_color',
									'value' => '',
									'group' => 'Animation',
									'dependency' => Array('element' => 'tooltip_continuous_animation','value' => 'glow'),
								),
						  )
					) );
				}
			}

			function ult_hotspot_scripts() {
				//  css
				//wp_register_style( 'ult_hotspot_tooltip_min_css',plugins_url( 'assets/css/hotspot-tooltip-min.css', dirname( __FILE__ )) );
				wp_register_style( 'ult_hotspot_css', get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/assets/min-css/hotspot.min.css',array(),null,false);
				wp_register_style( 'ult_hotspot_tooltipster_css',get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/assets/min-css/hotspot-tooltipster.min.css',array(),null,false);

				//  js
				wp_register_script( 'ult_hotspot_js',get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/assets/min-js/hotspot.min.js',array('jquery'),null,false );
				wp_register_script( 'ult_hotspot_tooltipster_js',get_template_directory_uri().'/inc/vc_custom/Ultimate_VC_Addons/assets/min-js/hotspot-tooltipster.min.js',array('jquery'),null,false);
			}
		}

		new ULT_HotSpot;

		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		  class WPBakeryShortCode_ult_hotspot extends WPBakeryShortCodesContainer {
		  }
		}
		if ( class_exists( 'WPBakeryShortCode' ) ) {
		  class WPBakeryShortCode_ult_hotspot_items extends WPBakeryShortCode {
		  }
		}

	}
}