<?php
defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;


/**
 * CubeWP Nav Menu Widgets.
 *
 * Elementor Widget For Nav Menu By CubeWP.
 *
 * @since 1.0.0
 */
class Value_Pack_Compare_Products extends Value_Pack_Widget_Base
{

	protected $mega_menu_index = 1;

	public function get_name()
	{
		return 'vp_compare_products';
	}

	public function get_title()
	{
		return esc_html__('Products Comparison', 'valuepack-addons');
	}

	public function get_icon()
	{
		return 'eicon-products vpack-icon';
	}

	public function get_categories()
	{
		return array('value_pack');
	}

	public function get_keywords()
	{
		return array(
			'banner',
			'featured',
			'click',
			'promotion',
			'promotional',
			'website',
			'search',
			'searches',
			'multi'
		);
	}

	protected function register_controls()
	{
		$this->start_controls_section('product_settings', array(
			'label' => esc_html__('Product Settings', 'valuepack-addons'),
			'tab'   => Controls_Manager::TAB_CONTENT,
		));

		// Comparison type: Products or Images
		$this->add_control('comparison_type', array(
			'type'    => Controls_Manager::SELECT,
			'label'   => esc_html__('Comparison Type', 'valuepack-addons'),
			'options' => array(
				'products' => esc_html__('Products Comparison', 'valuepack-addons'),
				'images'   => esc_html__('Image Comparison', 'valuepack-addons'),
			),
			'default' => 'products',
		));

		// Product IDs (shown only if "Compare Products" is selected)
		$this->add_control('product_ID_1', array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__('Product 1 ID', 'valuepack-addons'),
			'description' => esc_html__('Enter the ID of the first product for comparison', 'valuepack-addons'),
			'placeholder' => '125',
			'default' => value_pack_get_any_one_product_id(),
			'condition'   => array(
				'comparison_type' => 'products',
			),
		));

		$this->add_control('product_ID_2', array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__('Product 2 ID', 'valuepack-addons'),
			'description' => esc_html__('Enter the ID of the second product for comparison', 'valuepack-addons'),
			'placeholder' => '523',
			'default' => value_pack_get_any_one_product_id(),
			'condition'   => array(
				'comparison_type' => 'products',
			),
		)); 

		// Image Uploaders (shown only if "Compare Images" is selected)
		$this->add_control('image_1', array(
			'type'        => Controls_Manager::MEDIA,
			'label'       => esc_html__('Image 1', 'valuepack-addons'),
			'description' => esc_html__('Upload the first image for comparison', 'valuepack-addons'),
			'condition'   => array(
				'comparison_type' => 'images',
			),
		));

		$this->add_control('image_1_title', array(
			'type'        => Controls_Manager::TEXT,
			'label'       => esc_html__('Image 1 Title', 'valuepack-addons'),
			'description' => esc_html__('Enter a title for the first image', 'valuepack-addons'),
			'placeholder' => esc_html__('Image 1 Title', 'valuepack-addons'),
			'condition'   => array(
				'comparison_type' => 'images',
			),
		));

		$this->add_control('image_2', array(
			'type'        => Controls_Manager::MEDIA,
			'label'       => esc_html__('Image 2', 'valuepack-addons'),
			'description' => esc_html__('Upload the second image for comparison', 'valuepack-addons'),
			'condition'   => array(
				'comparison_type' => 'images',
			),
		));

		$this->add_control('image_2_title', array(
			'type'        => Controls_Manager::TEXT,
			'label'       => esc_html__('Image 2 Title', 'valuepack-addons'),
			'description' => esc_html__('Enter a title for the second image', 'valuepack-addons'),
			'placeholder' => esc_html__('Image 2 Title', 'valuepack-addons'),
			'condition'   => array(
				'comparison_type' => 'images',
			),
		));
		$this->add_control('icon_slide', array(
			'type'        => Controls_Manager::ICONS,
			'label'       => esc_html__('Icon For Slide', 'valuepack-addons'),
			'default'     => [
				'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAABGCAYAAACOjMdmAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAI7SURBVHgB7ZvvUcJAEMVfrABn/OSMM6ECsQKhEzvQDtAKoAOwArUCsAKhgqQD6GDdJXcYmIDA5M9u5n4zS5gkA/fYu82RvItQEkQU86bPce+2nVx4UhcLjiXHPIqiFE3Dje9wDDkSupyEY+R+iNoF9DlmVD6TWgTJl3B8UPVMKhPEH/zMsaL6SDieUCaU9eGmGJ3SxugfAVJxZhw9NItUuQFXuPWhEw4KUSTCc1TMMSE/0CPCs2AhD0UHrop2un6pTYTQO3XMiIgn0s/LfrujPRExsnERQzcyTh7y05v9rjWEfhGCFKKdLrbNiMtGAltIFZvLm3xGhrDHts2bjBjNhudari0+Iy+wy6btPiOSjRg2STkj3ch4t/LE0rX6sM9AhGicipxLT4Tcwz6PIiSGfToy2An2WbdFSPH/kUu5ubvdRFnnnUOpQpokCNFGEKKNIEQbQYg2ghBtBCHaCEK0EYRoIwjRRqtuPqzRAtoiJG2VkG/YZylCFrDPoi0PerpX7qF7CrvIo7fUXxDfYZcvefEPQ8VJsIJNutuMOA/UHPaYej9K3sLRR2aosUTXC9lOGp2n4xN2mObdQUU2J3HOdaCbFJmhJvU7dqbx7sAb9PN2khWdMzMmvYyL2txuc6ZjAF3zsI1d9tDB1hiYT4KaHTNjlAllNtqE6kMWFFRjiKNs2cWUqkeWdsSomgoFzSibKtWLEyTjJ6HLSTheKSssFxOhJOhvsZhUOPGAxdi1UK1zIfcJpBKVtljsFx8i62qdr3+VAAAAAElFTkSuQmCC'
			]
		));
		
		$this->end_controls_section();
		$this->start_controls_section(
			'container_style',
			[
				'label' => esc_html__('Container Settings', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__('Container Height', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 700,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-img-comp-responsive img' => 'min-height: {{SIZE}}{{UNIT}} !important; height:  {{SIZE}}{{UNIT}}  !important;',
					'{{WRAPPER}} .wc-woo-img-comp-slider' => 'min-height: {{SIZE}}{{UNIT}} !important; height:  {{SIZE}}{{UNIT}}  !important;',
					'{{WRAPPER}} .wc-woo-img-comp-container' => 'min-height: {{SIZE}}{{UNIT}} !important; height:  {{SIZE}}{{UNIT}}  !important;',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__('Icon Size', 'valuepack-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-img-comp-slider i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wc-woo-img-comp-slider svg' => 'min-width: {{SIZE}}{{UNIT}} !important; height: auto !important',
					'{{WRAPPER}} .wc-woo-img-comp-slider img' => 'min-width: {{SIZE}}{{UNIT}} !important; height: auto !important',
				],
			]
		);
	
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-img-comp-slider i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wc-woo-img-comp-slider svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wc-woo-img-comp-slider path' => 'fill: {{VALUE}};',
			 	],
			]
		);
	
		$this->end_controls_section();

		$this->start_controls_section(
			'text_style_section',
			[
				'label' => esc_html__('Text Content Styling', 'valuepack-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		); 
		$this->add_responsive_control(
			'r_text_padding',
			[
				'label' => esc_html__('Text Padding', 'valuepack-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => 0,
					'bottom' => 34,
					'left' => 40,
					'right' => 0,
					'unit' => 'px', 
				],
				'selectors' => [
					'{{WRAPPER}} .wc-woo-img-comp-responsive .wc-wo-compare-products' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'fields_options' => [
					'top' => [
						'default' => 0,
						'is_locked' => true,  
					],
				],
			] 
		); 
	
		$this->add_control(
			'image_h3_color',
			[
				'label' => esc_html__('Text Color', 'valuepack-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .wc-woo-img-comp-container.images .wc-wo-compare-products h3' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .wc-woo-img-comp-responsive .wc-wo-compare-products h2 a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .wc-woo-img-comp-responsive .wc-wo-compare-products h2 a svg path' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wc-woo-img-comp-responsive .wc-wo-compare-products h3 ' => 'color: {{VALUE}};',
				],
			]
		);
	
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'image_h3_typography',
				'label' => esc_html__('Text Typography', 'valuepack-addons'),
				'selector' => '{{WRAPPER}} .wc-woo-img-comp-container.images .wc-wo-compare-products h3,.wc-woo-img-comp-responsive .wc-wo-compare-products h3 ',
			]
		);
	
		$this->end_controls_section();
	}



	protected function render()
	{
		$settings = $this->get_active_settings();
		$settings   = $this->get_settings_for_display();
		$comparison_type = $settings['comparison_type'];
		$icon_slide = $settings['icon_slide'];  
		if ($comparison_type == 'products') {
		 
			// new changes aded 
			$post_IDS1 = isset($settings['product_ID_1']) ? absint($settings['product_ID_1']) : 0;
			$post_IDS2 = isset($settings['product_ID_2']) ? absint($settings['product_ID_2']) : 0;
			
			$default_products = value_pack_get_woocommerce_products();
			$first_default_id = !empty($default_products) ? absint(array_key_first($default_products)) : 0;
			
			if (empty($post_IDS1) || get_post_type($post_IDS1) !== 'product' || get_post_status($post_IDS1) !== 'publish') {
				$post_IDS1 = $first_default_id;
			}
			
			if (empty($post_IDS2) || get_post_type($post_IDS2) !== 'product' || get_post_status($post_IDS2) !== 'publish') {
				$post_IDS2 = $first_default_id;
			}
			
			// new changes aded close
			
			// Verify products exist before processing
			if (!$post_IDS1 || !$post_IDS2 || !get_post_status($post_IDS1) || !get_post_status($post_IDS2)) {
				return esc_html_e('Invalid product selection', 'valuepack-addons');
			}

			$product_1_data = array(
				'image' => esc_url($this->wc_woo_get_image($post_IDS1)),
				'url'   => esc_url(get_permalink($post_IDS1)),
				'type'  => esc_html(get_the_title($post_IDS1)),
			);
			$product_2_data = array(
				'image' => esc_url($this->wc_woo_get_image($post_IDS2)),
				'url'   => esc_url(get_permalink($post_IDS2)),
				'type'  => esc_html(get_the_title($post_IDS2)),
			);
		} else {
			$image_1 = $settings['image_1'];
			$image_2 = $settings['image_2'];
			$image_1_title = $settings['image_1_title'];
			$image_2_title = $settings['image_2_title'];
			$product_1_data = array(
				'image' =>   $image_1['url'],
				'url'   => '',
				'type'  => $image_1_title,
			);
	
	
			$product_2_data = array(
				'image' =>   $image_2['url'],
				'url'   =>'',
				'type'  => $image_2_title,
			);
		} 

?>
		<div class="wc-woo-img-comp-container <?php echo esc_attr($comparison_type); ?>">
			<div class="wc-woo-img-comp-slider">
				<?php  
					if(!empty($icon_slide['value'] )){ 
							Icons_Manager::render_icon($icon_slide, ['aria-hidden' => 'true']);
						}else{
						echo '	<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAABGCAYAAACOjMdmAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAI7SURBVHgB7ZvvUcJAEMVfrABn/OSMM6ECsQKhEzvQDtAKoAOwArUCsAKhgqQD6GDdJXcYmIDA5M9u5n4zS5gkA/fYu82RvItQEkQU86bPce+2nVx4UhcLjiXHPIqiFE3Dje9wDDkSupyEY+R+iNoF9DlmVD6TWgTJl3B8UPVMKhPEH/zMsaL6SDieUCaU9eGmGJ3SxugfAVJxZhw9NItUuQFXuPWhEw4KUSTCc1TMMSE/0CPCs2AhD0UHrop2un6pTYTQO3XMiIgn0s/LfrujPRExsnERQzcyTh7y05v9rjWEfhGCFKKdLrbNiMtGAltIFZvLm3xGhrDHts2bjBjNhudari0+Iy+wy6btPiOSjRg2STkj3ch4t/LE0rX6sM9AhGicipxLT4Tcwz6PIiSGfToy2An2WbdFSPH/kUu5ubvdRFnnnUOpQpokCNFGEKKNIEQbQYg2ghBtBCHaCEK0EYRoIwjRRqtuPqzRAtoiJG2VkG/YZylCFrDPoi0PerpX7qF7CrvIo7fUXxDfYZcvefEPQ8VJsIJNutuMOA/UHPaYej9K3sLRR2aosUTXC9lOGp2n4xN2mObdQUU2J3HOdaCbFJmhJvU7dqbx7sAb9PN2khWdMzMmvYyL2txuc6ZjAF3zsI1d9tDB1hiYT4KaHTNjlAllNtqE6kMWFFRjiKNs2cWUqkeWdsSomgoFzSibKtWLEyTjJ6HLSTheKSssFxOhJOhvsZhUOPGAxdi1UK1zIfcJpBKVtljsFx8i62qdr3+VAAAAAElFTkSuQmCC" />';
						}
				
				?>
			</div>
			<div class="wc-woo-img-comp-responsive">
				<div class="img-comp-img">
					<img src="<?php echo esc_url($product_1_data['image']); ?>" style="width:auto;height:100%;max-width:initial;" />
					<div class="wc-wo-compare-products">
						<h3><?php echo esc_html($product_1_data['type']); ?></h2>
							<h2><a href="<?php echo esc_url($product_1_data['url']); ?>">
									<?php echo esc_html__('VIEW PRODUCT', 'valuepack-addons'); ?>
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="#1D1D1D" />
									</svg>

								</a>
							</h2>
					</div>
				</div>
				<div class="img-comp-img img-comp-overlay" id="img-comp-overlay">
					<img src="<?php echo esc_url($product_2_data['image']); ?>" style="width:auto;height:100%;max-width:initial;" />
					<div class="wc-wo-compare-products">
						<h3><?php echo esc_html($product_2_data['type']); ?></h2>
							<h2><a href="<?php echo esc_url($product_2_data['url']); ?>">
									<?php echo esc_html__('VIEW PRODUCT', 'valuepack-addons'); ?>
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="#1D1D1D" />
									</svg>

								</a>
							</h2>
					</div>
				</div>
			</div>
		</div>

<?php
	}
	private function wc_woo_get_image($tempID)
	{
		$tempID = absint($tempID);
		if (!$tempID) {
			return '';
		}

		if (has_post_thumbnail($tempID)) {
			$getURL1 = esc_url(get_the_post_thumbnail_url($tempID));
		} else {
			$getURL1 = 'https://placehold.co/600x400.png';
		}
		return $getURL1;
	}
}