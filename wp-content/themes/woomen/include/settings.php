<?php
defined('ABSPATH') || exit;

/**
 * Adds typography, blog and WooCommerce settings sections
 * 
 * @since 1.0.0
 * @param array $sections Existing settings sections
 * @return array Modified sections array with new additions
 * @hook cubewp/options/sections
 */
if (! function_exists('woomen_settings_new_sections')) {
	function woomen_settings_new_sections($sections)
	{
		$single_settings['woomen_typography'] = array(
			'title'  => __('Typography', 'woomen'),
			'id'     => 'woomen_typography',
			'icon'   => 'dashicons dashicons-edit',
			'fields' => array(
				array(
					'id'      => 'typography-h1',
					'title'   => __('Heading 1 (h1)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '24px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-h2',
					'title'   => __('Heading 2 (h2)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '22px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-h3',
					'title'   => __('Heading 3 (h3)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '20px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-h4',
					'title'   => __('Heading 4 (h4)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '18px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-h5',
					'title'   => __('Heading 5 (h5)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '16px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-h6',
					'title'   => __('Heading 6 (h6)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'color'       => '#1d1d1d',
						'font-size'   => '14px',
						'font-family' => 'Source Sans Pro',
						'font-weight' => '700',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'subsets'     => 'latin'
					)
				),
				array(
					'id'      => 'typography-p',
					'title'   => __('Paragraph (p)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'font-family' => 'Source Sans Pro',
						'font-weight' => '400',
						'color'       => '#1d1d1d',
						'subsets'     => 'latin',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'font-size'   => '13px'
					)
				),
				array(
					'id'      => 'typography-label',
					'title'   => __('Label', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'font-family' => 'Source Sans Pro',
						'font-weight' => '400',
						'color'       => '#1d1d1d',
						'subsets'     => 'latin',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'font-size'   => '13px'
					)
				),
				array(
					'id'      => 'typography-body',
					'title'   => __('Overall Font', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'font-family' => 'Source Sans Pro',
						'font-weight' => '400',
						'color'       => '#1d1d1d',
						'subsets'     => 'latin',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'font-size'   => '13px'
					)
				),
				array(
					'id'      => 'typography-a',
					'title'   => __('Anchor Tag (a)', 'woomen'),
					'type'    => 'typography',
					'default' => array(
						'font-family' => 'Source Sans Pro',
						'font-weight' => '400',
						'color'       => '#1d1d1d',
						'subsets'     => 'latin',
						'line-height' => '1.5em',
						'letter-spacing' => '0px',
						'font-size'   => '13px'
					)
				),
				array(
					'id'      => 'typography-a:hover',
					'title'   => __('Anchor Tag (a) Hover Color', 'woomen'),
					'desc'    => __('Please select the text color on hover state.', 'woomen'),
					'type'    => 'color',
					'default' => '#1d1d1d',
				),
				array(
					'id'      => 'theme-defualt-butotns-color',
					'title'   => __('Theme Defualt Buttons', 'woomen'),
					'type'    => 'heading',
				),
				array(
					'id'    => 'button_text_color',
					'title' => __('Button Text Color (Outline)', 'woomen'),
					'desc'  => __('Choose the color for the (Outline) button text.', 'woomen'),
					'type'  => 'color',
					'default' => '#1D1D1D',
				),
				array(
					'id'    => 'button_bg_color',
					'title' => __('Button Background Color', 'woomen'),
					'desc'  => __('Choose the background color for the (Outline) button.', 'woomen'),
					'type'  => 'color',
					'default' => '#fff',
				),
				array(
					'id'    => 'button_border_color',
					'title' => __('Button Border Color', 'woomen'),
					'desc'  => __('Choose the border color for the (Outline) button.', 'woomen'),
					'type'  => 'color',
					'default' => '#1D1D1D',
				),
				array(
					'id'    => 'button_border_radius',
					'title' => __('Button Border Radius', 'woomen'),
					'desc'  => __('Enter the border radius for the button (e.g. 5px, 10px).', 'woomen'),
					'type'  => 'text',
					'default' => '0px',
				),
				array(
					'id'    => 'button_hover_text_color',
					'title' => __('Button (Filled) Text Color', 'woomen'),
					'desc'  => __('Choose the text color For (Filled) buttons.', 'woomen'),
					'type'  => 'color',
					'default' => '#fff',
				),
				array(
					'id'    => 'button_hover_bg_color',
					'title' => __('Button Hover Background Color', 'woomen'),
					'desc'  => __('Choose the background color For (Filled) buttons', 'woomen'),
					'type'  => 'color',
					'default' => '#1D1D1D',
				),
				array(
					'id'    => 'button_hover_border_color',
					'title' => __('Button Hover Border Color', 'woomen'),
					'desc'  => __('Choose the border color For (Filled) buttons', 'woomen'),
					'type'  => 'color',
					'default' => '#1D1D1D',
				),
			),
		);

		// Adding Blog Section
		$single_settings['woomen_blog'] = array(
			'title'  => __('Blog', 'woomen'),
			'id'     => 'woomen_blog',
			'icon'   => 'dashicons dashicons-archive',
			'fields' => array(
				array(
					'id'      => 'blog_default_style',
					'title'   => __('Blog Grid Style', 'woomen'),
					'desc'    => __('Please select blog style for index or Blog Page', 'woomen'),
					'type'    => 'select',
					'options' => array(
						'style_1' => esc_html__("Style 1", "woomen"),
						'style_2' => esc_html__("Style 2", "woomen"),
						'style_3' => esc_html__("Style 3", "woomen"),
						'style_4' => esc_html__("Style 4", "woomen"),
					),
					'default' => 'style_2',
				),
				array(
					'id'      => 'blog_banner_title',
					'title'   => __('Blog Banner Title', 'woomen'),
					'desc'    => __('Specify your banner title', 'woomen'),
					'type'    => 'text',
					'default' => __('Home', 'woomen'),
				)
			),
		);
		return woomen_add_into_array_after_key($sections, $single_settings, 'general-settings');
	}

	add_filter('cubewp/options/sections', 'woomen_settings_new_sections', 11, 1);
}

/**
 * Add theme-specific fields to general settings section
 *
 * @since 1.0.0
 * @param array $section_fields Existing general settings fields
 * @return array Modified fields array with theme additions
 * @hook cubewp/settings/section/general-settings
 */
if (! function_exists('woomen_adding_general_settings')) {
	function woomen_adding_general_settings($section_fields)
	{
		$fields   = array();
		$fields[] = array(
			'id'      => 'primary_color',
			'title'   => __('Primary Color', 'woomen'),
			'type'    => 'color',
			'desc'    => __('Please select primary color for the text, hover or active states etc.', 'woomen'),
			'default' => '#1D1D1D',
		);
		$fields[] = array(
			'id'      => 'secondary_color',
			'title'   => __('Secondary Color', 'woomen'),
			'type'    => 'color',
			'desc'    => __('Please select secondary color for the text, hover or active states etc.', 'woomen'),
			'default' => '#0f0f0f',
		);

		return array_merge($section_fields, $fields);
	}

	add_filter('cubewp/settings/section/general-settings', 'woomen_adding_general_settings');
}

/**
 * Adds additional weight variants for selected Google Fonts
 *
 * @since 1.0.0
 * @param array $font Existing font configurations
 * @return array Modified font configurations with added variants
 * @hook cubewp/settings/google_fonts
 */
if (!function_exists('woomen_font_settings')) {
	function woomen_font_settings($font)
	{

		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Plus Jakarta Sans']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['DM Sans']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Jost']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Outfit']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Bebas Neue']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '100',
			'name' => 'Thin 100'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Inter']['variants'][] = array(
			'id'   => '900',
			'name' => 'Black 900'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '100',
			'name' => 'Thin 100'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Fraunces']['variants'][] = array(
			'id'   => '900',
			'name' => 'Black 900'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '100',
			'name' => 'Thin 100'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Instrument Sans']['variants'][] = array(
			'id'   => '900',
			'name' => 'Black 900'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '100',
			'name' => 'Thin 100'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Figtree']['variants'][] = array(
			'id'   => '900',
			'name' => 'Black 900'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '100',
			'name' => 'Thin 100'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '200',
			'name' => 'ExtraLight 200'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '300',
			'name' => 'Light 300'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '400',
			'name' => 'Regular 400'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '500',
			'name' => 'Medium 500'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '600',
			'name' => 'SemiBold 600'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '700',
			'name' => 'Bold 700'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '800',
			'name' => 'ExtraBold 800'
		);
		$font['Syne']['variants'][] = array(
			'id'   => '900',
			'name' => 'Black 900'
		);

		return $font;
	}
	add_filter('cubewp/settings/google_fonts', 'woomen_font_settings', 20, 1);
}
