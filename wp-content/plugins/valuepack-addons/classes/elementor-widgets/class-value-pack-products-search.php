<?php

/**
 * Value Pack Products Search Widget
 *
 * A custom Elementor widget that provides product search functionality with multiple styles.
 *
 * @package ValuePack
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

/**
 * Class Value_Pack_Products_Search
 *
 * Elementor widget for displaying a products search with multiple styles.
 */
class Value_Pack_Products_Search extends Value_Pack_Widget_Base
{
    protected $value_pack_style_depends = ['value-pack-search-styles'];

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'vp_products_search';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Products Search', 'valuepack-addons');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-search vpack-icon';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['value_pack'];
    }

    /**
     * Register widget controls.
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Widget Settings', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'search_styles',
            [
                'label' => esc_html__('Select Search Style', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'style_1',
                'options' => [
                    'style_1' => esc_html__('Style 1', 'valuepack-addons'),
                    'style_2' => esc_html__('Style 2', 'valuepack-addons'),
                    'style_3' => esc_html__('Style 3', 'valuepack-addons'),
                    'style_4' => esc_html__('Style 4', 'valuepack-addons'),
                    'style_5' => esc_html__('Style 5', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'valuepack-addons'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Icon Color', 'valuepack-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-icon svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .vp-search-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vp-search-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_text',
            [
                'label' => esc_html__('Show Text', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'valuepack-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Search',
                'placeholder' => esc_html__('Enter text here', 'valuepack-addons'),
                'condition' => [
                    'show_text' => 'yes',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Style controls
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vp-search-main-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_icon_spacing',
            [
                'label' => esc_html__('Space Between', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_text' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'valuepack-addons'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .vp-search-main-title',
            ]
        );

        $this->add_control(
            'flex_direction',
            [
                'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => esc_html__('Row', 'valuepack-addons'),
                    'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                    'column' => esc_html__('Column', 'valuepack-addons'),
                    'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'display: flex; flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'align_items',
            [
                'label' => esc_html__('Align Items', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'justify_content',
            [
                'label' => esc_html__('Justify Content', 'valuepack-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'valuepack-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'valuepack-addons'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'enable_bg_color',
            [
                'label' => esc_html__('Enable Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-icon.vp-search-trigger' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_bg_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-icon.vp-search-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_bg_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-icon.vp-search-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_bg_color' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'enable_container_bg_color',
            [
                'label' => esc_html__('Enable Container Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'valuepack-addons'),
                'label_off' => esc_html__('No', 'valuepack-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'container_bg_color',
            [
                'label' => esc_html__('Container Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_container_bg_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Container Padding', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_container_bg_color' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'search_container_border',
                'selector' => '{{WRAPPER}} .vp-search-widget',
            ]
        );
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Container Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_container_bg_color' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_backdrop_filter',
            [
                'label' => esc_html__('Backdrop Filter', 'valuepack-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vp-search-widget' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'enable_container_bg_color' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_popup_button_style',
            [
                'label' => __('Button Styles', 'valuepack-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'search_styles' => ['style_2', 'style_5', 'style_1'],
                ],
            ]
        );


        $this->start_controls_tabs('tabs_popup_button_style');

        // Normal Tab
        $this->start_controls_tab(
            'tab_popup_button_normal',
            [
                'label' => __('Normal', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'popup_button_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a,
                     body  .woocommerce-open-search .woocommerce-left-main-button a path,
                    body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a,
                     body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a path,
                     body .woo-popup5-view-anchor  a path,
                     body .woo-popup5-view-anchor a' => 'color: {{VALUE}} !important;fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'popup_button_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a,
             body .woo-popup5-view-anchor a' => 'background-color: {{VALUE}} !important;background-image: unset !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'popup_button_border',
                'selector' => ' body  .woocommerce-open-search .woocommerce-left-main-button a,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a,
                       body .woo-popup5-view-anchor a',
            ]
        );

        $this->add_control(
            'popup_button_border_radius',
            [
                'label' => __('Border Radius', 'valuepack-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a,
             body .woo-popup5-view-anchor a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
        $this->start_controls_tab(
            'tab_popup_button_hover',
            [
                'label' => __('Hover', 'valuepack-addons'),
            ]
        );

        $this->add_control(
            'popup_button_hover_text_color',
            [
                'label' => __('Text Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a:hover,body .woocommerce-open-search .woocommerce-left-main-button a:hover path,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a:hover,
                      body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a:hover path,
                     body .woo-popup5-view-anchor a:hover path,
             body .woo-popup5-view-anchor a:hover' => 'color: {{VALUE}} !important;color: {{VALUE}} !important;fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'popup_button_hover_bg_color',
            [
                'label' => __('Background Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a:hover,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a:hover,
             body .woo-popup5-view-anchor a:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'popup_button_hover_border_color',
            [
                'label' => __('Border Color', 'valuepack-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    ' body  .woocommerce-open-search .woocommerce-left-main-button a:hover,body .vp-search-style-2-wrapper .pop-up-2 .pop-up-btn a:hover,
             body .woo-popup5-view-anchor a:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $style = $settings['search_styles'];
        $show_text = $settings['show_text'];
        $title = $settings['title'];
        $icon = $settings['icon'];
?>
        <div class="vp-search-widget cursor-pointer" data-style="<?php echo esc_html($style); ?>">
            <div class="vp-search-icon vp-search-trigger" role="button" aria-label="<?php esc_attr_e('Open search', 'valuepack-addons'); ?>">
                <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
            </div>
            <?php if ('yes' === $show_text && !empty($title)) : ?>
                <div class="vp-search-main-title"><?php echo esc_html($title); ?></div>
            <?php endif; ?>
        </div>

        <?php $this->render_search_modal($style); ?>
        <?php
    }

    /**
     * Render the search modal based on selected style.
     *
     * @param string $style The search style to render.
     */
    protected function render_search_modal($style)
    {
        if ('style_1' === $style) : ?>
            <div class="woocommerce-open-search-main vp-search-popup-main" style="opacity: 0;visibility: hidden; position:fixed;" aria-hidden="true">
                <div class="woocommerce-open-search vp-search-popup-inner">
                    <div class="open-search-left-content">
                        <div class="woocommerce-left-result-heading d-flex align-items-center justify-content-between">
                            <h2 class="left-main-heading"><?php echo esc_html__('Search results', 'valuepack-addons'); ?></h2>
                            <p class="display-result"></p>
                        </div>
                        <div id="search-results" class="woocommerce-left-main-content">
                            <div class="left-content-items d-flex">
                                <!-- Search results will be appended here -->
                            </div>
                        </div>
                        <div class="woocommerce-left-main-button">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                                <?php echo esc_html__('See all products', 'valuepack-addons'); ?>
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12.1725 1.69189H4.07656C3.96052 1.69189 3.84924 1.73799 3.7672 1.82004C3.68515 1.90208 3.63906 2.01336 3.63906 2.12939C3.63906 2.24543 3.68515 2.35671 3.7672 2.43875C3.84924 2.5208 3.96052 2.56689 4.07656 2.56689H11.3146L2.33749 11.5444C2.2941 11.5843 2.25923 11.6326 2.23497 11.6864C2.21072 11.7402 2.19759 11.7983 2.19637 11.8572C2.19515 11.9162 2.20586 11.9748 2.22786 12.0295C2.24987 12.0843 2.28271 12.134 2.32441 12.1757C2.36612 12.2174 2.41583 12.2502 2.47055 12.2722C2.52527 12.2942 2.58387 12.3049 2.64284 12.3037C2.7018 12.3025 2.75991 12.2894 2.81367 12.2651C2.86744 12.2408 2.91574 12.206 2.95568 12.1626L11.9332 3.18508V10.4235C11.9332 10.5396 11.9793 10.6508 12.0613 10.7329C12.1434 10.8149 12.2546 10.861 12.3707 10.861C12.4867 10.861 12.598 10.8149 12.68 10.7329C12.7621 10.6508 12.8082 10.5396 12.8082 10.4235V2.32802C12.8079 2.15946 12.7409 1.99786 12.6218 1.87862C12.5026 1.75939 12.3411 1.69224 12.1725 1.69189Z" fill="white" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="open-search-right-content">
                        <div class="woocommerce-right-close vp-search-close-x">
                            <h2><?php echo esc_html__('SEARCH OUR SITE', 'valuepack-addons'); ?></h2>
                            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect width="35" height="35" rx="17.5" fill="white" />
                                <path d="M12.9884 22.4999C12.8918 22.4999 12.7974 22.4713 12.717 22.4176C12.6367 22.364 12.5741 22.2877 12.5372 22.1985C12.5002 22.1092 12.4905 22.0111 12.5094 21.9163C12.5282 21.8216 12.5748 21.7346 12.6431 21.6663L21.6663 12.643C21.7579 12.5514 21.8821 12.5 22.0116 12.5C22.1412 12.5 22.2654 12.5514 22.3569 12.643C22.4485 12.7346 22.5 12.8588 22.5 12.9883C22.5 13.1179 22.4485 13.2421 22.3569 13.3336L13.3337 22.3569C13.2884 22.4023 13.2345 22.4383 13.1753 22.4628C13.116 22.4874 13.0525 22.5 12.9884 22.4999Z" fill="#1D1D1D" />
                                <path d="M22.0117 22.4999C21.9475 22.5 21.884 22.4874 21.8248 22.4628C21.7655 22.4383 21.7117 22.4023 21.6664 22.3569L12.6431 13.3336C12.5515 13.2421 12.5001 13.1179 12.5001 12.9883C12.5001 12.8588 12.5515 12.7346 12.6431 12.643C12.7347 12.5514 12.8589 12.5 12.9884 12.5C13.1179 12.5 13.2421 12.5514 13.3337 12.643L22.357 21.6663C22.4253 21.7346 22.4718 21.8216 22.4906 21.9163C22.5095 22.0111 22.4998 22.1092 22.4629 22.1985C22.4259 22.2877 22.3633 22.364 22.283 22.4176C22.2027 22.4713 22.1083 22.4999 22.0117 22.4999Z" fill="#1D1D1D" />
                            </svg>
                        </div>
                        <div class="woocommerce-right-searchbar">
                            <button id="search-button" class="woocommerce-right-serach-icon" aria-label="<?php esc_attr_e('Search', 'valuepack-addons'); ?>">
                                <?php echo self::vp_get_serach_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </button>
                            <input class="woocommerce-live-search-input" type="text" placeholder="<?php esc_attr_e('What are you looking for?', 'valuepack-addons'); ?>">
                            <div class="spinner" style="display: none;"></div>
                        </div>
                        <div class="vp-suggestions-style-1">
                            <h3><?php echo esc_html__('Suggestions', 'valuepack-addons'); ?></h3>
                            <ul>
                                <!-- suggestions will be loaded dynamically -->
                            </ul>
                        </div>
                        <div class="woocommerce-right-main-content">
                            <h2 class="right-main-heading"><?php echo esc_html__('YOU MAY ALSO LIKE', 'valuepack-addons'); ?></h2>
                            <div class="right-content d-flex">
                                <?php
                                $related_products = $this->value_pack_get_related_products_by_cart_ids(4);

                                if (empty($related_products)) {
                                    $args = [
                                        'limit' => 4,
                                        'orderby' => 'rand',
                                        'status' => 'publish'
                                    ];
                                    $random_products = wc_get_products($args);
                                } else {
                                    $random_products = array_map('wc_get_product', $related_products);
                                }

                                if (!empty($random_products)) :
                                    foreach ($random_products as $product) :
                                        if ($product) :
                                ?>
                                            <div class="content-item">
                                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                                    <?php echo wp_kses_post($product->get_image()); ?>
                                                </a>
                                                <div class="content-item-text">
                                                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                                        <h3 class="content-heading">
                                                            <?php echo esc_html($product->get_name()); ?>
                                                        </h3>
                                                    </a>
                                                    <p class="content-value">
                                                        <?php echo wp_kses_post($product->get_price_html()); ?>
                                                    </p>
                                                </div>
                                            </div>
                                <?php
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ('style_2' === $style) : ?>
            <div class="vp-open-popup vp-search-style-2-wrapper vp-search-popup-main" style="opacity: 0;visibility: hidden;position:fixed;">
                <div class="pop-up-2 vp-search-popup-inner">
                    <div class="pop-up-top">
                        <p class="cancel-out vp-style-three-close-x vp-search-close-x" role="button" aria-label="<?php esc_attr_e('Close search', 'valuepack-addons'); ?>">
                            <?php echo self::value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </p>
                        <div class="pop-up-search d-flex">
                            <span><?php echo self::vp_get_serach_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            <input class="woocommerce-live-search-input" type="text" placeholder="<?php esc_attr_e('What are you looking for?', 'valuepack-addons'); ?>">
                        </div>
                        <div class="spinner"></div>
                    </div>
                    <div class="vp-suggestions-style-2">
                        <p><?php echo esc_html__('Suggestions', 'valuepack-addons'); ?></p>
                        <ul>
                            <!-- suggestions will be loaded dynamically -->
                        </ul>
                    </div>
                    <div class="vp-search-results-2-wrapper">
                        <h4><?php echo esc_html__('TOP RESULTS', 'valuepack-addons'); ?></h4>
                        <div class="vp-search-results-2">
                            <!-- Search results will be appended here -->
                        </div>
                    </div>
                    <div class="pop-up-btn">
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                            <?php echo esc_html__('See all products', 'valuepack-addons'); ?>
                            <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12.1725 1.69189H4.07656C3.96052 1.69189 3.84924 1.73799 3.7672 1.82004C3.68515 1.90208 3.63906 2.01336 3.63906 2.12939C3.63906 2.24543 3.68515 2.35671 3.7672 2.43875C3.84924 2.5208 3.96052 2.56689 4.07656 2.56689H11.3146L2.33749 11.5444C2.2941 11.5843 2.25923 11.6326 2.23497 11.6864C2.21072 11.7402 2.19759 11.7983 2.19637 11.8572C2.19515 11.9162 2.20586 11.9748 2.22786 12.0295C2.24987 12.0843 2.28271 12.134 2.32441 12.1757C2.36612 12.2174 2.41583 12.2502 2.47055 12.2722C2.52527 12.2942 2.58387 12.3049 2.64284 12.3037C2.7018 12.3025 2.75991 12.2894 2.81367 12.2651C2.86744 12.2408 2.91574 12.206 2.95568 12.1626L11.9332 3.18508V10.4235C11.9332 10.5396 11.9793 10.6508 12.0613 10.7329C12.1434 10.8149 12.2546 10.861 12.3707 10.861C12.4867 10.861 12.598 10.8149 12.68 10.7329C12.7621 10.6508 12.8082 10.5396 12.8082 10.4235V2.32802C12.8079 2.15946 12.7409 1.99786 12.6218 1.87862C12.5026 1.75939 12.3411 1.69224 12.1725 1.69189Z" fill="white" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php elseif ('style_3' === $style) : ?>
            <div class="open-serch-filter-and-content vp-search-popup-main" style="top: 0;position:fixed;transform: translateY(-500%);">
                <div class="woocommerce-close-and-serachbar vp-search-popup-inner">
                    <div class="woocomerce-close vp-search-close-x" role="button" aria-label="<?php esc_attr_e('Close search', 'valuepack-addons'); ?>">
                        <?php echo self::value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <div class="woocomerce-searchbar">
                        <button class="woocomerce-serach-icon" aria-label="<?php esc_attr_e('Search', 'valuepack-addons'); ?>">
                            <?php echo self::vp_get_serach_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </button>
                        <input type="text" class="woocommerce-live-search-input" placeholder="<?php esc_attr_e('What are you looking for?', 'valuepack-addons'); ?>">
                        <div class="spinner" style="display: none;"></div>
                    </div>

                    <div class="woocomerce-filters-and-content d-flex">
                        <div class="woocomerce-left-filters-menu">
                            <div class="vp-suggestions-style-3">
                                <h3><?php echo esc_html__('Suggestions', 'valuepack-addons'); ?></h3>
                                <ul>
                                    <!-- suggestions will be loaded dynamically -->
                                </ul>
                            </div>
                        </div>

                        <div class="woocomerce-right-main-content woocommerce-right-main-content-style-3">
                            <h2 class="product-quantity-heading"></h2>
                            <div class="row m-0 p-0 gap-style search-results-style-3">
                                <!-- Search results will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ('style_4' === $style) : ?>
            <div class="vp-search-style-4-wrapper vp-search-popup-main" style="opacity: 0;visibility: hidden;position:fixed;">
                <div class="vp-search-style-4-header vp-search-popup-inner">
                    <span class="vp-search-close-icon-style-4 vp-search-close-x" role="button" aria-label="<?php esc_attr_e('Close search', 'valuepack-addons'); ?>">
                        <?php echo self::value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </span>
                    <div class="vp-search-style-4-input">
                        <span><?php echo self::vp_get_serach_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <input class="woocommerce-live-search-input" type="text" placeholder="<?php esc_attr_e('What are you looking for?', 'valuepack-addons'); ?>">
                    </div>
                    <div class="spinner"></div>
                    <div class="vp-search-style-4-content">
                        <div class="vp-search-style-4-sug-and-terms">
                            <div class="vp-suggestions-style-4">
                                <h4><?php echo esc_html__('Suggestions', 'valuepack-addons'); ?></h4>
                                <ul class="vp-suggestions-style-4-list">
                                    <!-- suggestions will be loaded dynamically -->
                                </ul>
                            </div>
                            <div class="vp-search-terms-style-4">
                                <h4><?php echo esc_html__('POPULAR TERMS', 'valuepack-addons'); ?></h4>
                                <ul class="popular-terms">
                                    <?php
                                    $terms = get_terms([
                                        'taxonomy' => 'product_cat',
                                        'hide_empty' => true,
                                    ]);

                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        usort($terms, function ($a, $b) {
                                            return $b->count - $a->count;
                                        });

                                        $top_terms = array_slice($terms, 0, 6);

                                        foreach ($top_terms as $term) {
                                            $term_link = get_term_link($term);
                                            if (!is_wp_error($term_link)) :
                                    ?>
                                                <li><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></li>
                                    <?php
                                            endif;
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="vp-search-style-4-products">
                            <h2><?php echo esc_html__('PRODUCTS', 'valuepack-addons'); ?></h2>
                            <div class="vp-search-product-items">
                                <!-- Search results will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ('style_5' === $style) : ?>
            <div class="vp-search-style-5-wrapper vp-search-popup-main" style="opacity: 0;visibility: hidden;position:fixed;">
                <div class="close-icon vp-search-close-x" role="button" aria-label="<?php esc_attr_e('Close search', 'valuepack-addons'); ?>">
                    <?php echo self::value_pack_get_close_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <div class="vp-search-style-5-content vp-search-popup-inner">
                    <div class="vp-search-style-5-input">
                        <div class="vp-search-field">
                            <span><?php echo self::vp_get_serach_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            <input class="woocommerce-live-search-input" type="text" placeholder="<?php esc_attr_e('What are you looking for?', 'valuepack-addons'); ?>">
                        </div>
                        <div class="spinner" style="display: none;"></div>
                        <div class="woo-trending-searches">
                            <p class="woo-trending-searches-heading"><?php echo esc_html__('TRENDING SEARCHES', 'valuepack-addons'); ?></p>
                            <ul class="woo-trending-searches-list">
                                <?php
                                $tags = get_terms([
                                    'taxonomy' => 'product_tag',
                                    'hide_empty' => true,
                                ]);

                                if (!empty($tags) && !is_wp_error($tags)) {
                                    usort($tags, function ($a, $b) {
                                        return $b->count - $a->count;
                                    });
                                    $top_tags = array_slice($tags, 0, 4);

                                    foreach ($top_tags as $tag) {
                                        $term_link = get_term_link($tag);
                                        if (!is_wp_error($term_link)) :
                                ?>
                                            <li class="woo-trending-searches-list-item">
                                                <a href="<?php echo esc_url($term_link); ?>">
                                                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                                    <?php echo esc_html($tag->name); ?>
                                                </a>
                                            </li>
                                <?php
                                        endif;
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="row vp-search-style-5-products">
                            <div class="woo-product-found">
                                <h2 class="vp-search-result-count"></h2>
                            </div>
                            <div class="vp-search-style-5-product-items">
                                <!-- Search results will be appended here -->
                            </div>
                        </div>
                        <div class="woo-popup5-view-anchor">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                                <?php echo esc_html__('VIEW MORE', 'valuepack-addons'); ?>
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M12.1725 1.69189H4.07657C3.96054 1.69189 3.84926 1.73799 3.76721 1.82004C3.68516 1.90208 3.63907 2.01336 3.63907 2.12939C3.63907 2.24543 3.68516 2.35671 3.76721 2.43875C3.84926 2.5208 3.96054 2.56689 4.07657 2.56689H11.3146L2.33751 11.5444C2.29411 11.5843 2.25924 11.6326 2.23499 11.6864C2.21074 11.7402 2.1976 11.7983 2.19638 11.8572C2.19516 11.9162 2.20587 11.9748 2.22788 12.0295C2.24988 12.0843 2.28273 12.134 2.32443 12.1757C2.36613 12.2174 2.41584 12.2502 2.47056 12.2722C2.52528 12.2942 2.58389 12.3049 2.64285 12.3037C2.70182 12.3025 2.75993 12.2894 2.81369 12.2651C2.86745 12.2408 2.91575 12.206 2.9557 12.1626L11.9332 3.18508V10.4235C11.9332 10.5396 11.9793 10.6508 12.0613 10.7329C12.1434 10.8149 12.2547 10.861 12.3707 10.861C12.4867 10.861 12.598 10.8149 12.6801 10.7329C12.7621 10.6508 12.8082 10.5396 12.8082 10.4235V2.32802C12.808 2.15946 12.7409 1.99786 12.6218 1.87862C12.5026 1.75939 12.3411 1.69224 12.1725 1.69189Z" fill="#1D1D1D" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
<?php endif;
    }

    /**
     * Get related products based on items in cart.
     *
     * @param int $limit Number of products to return.
     * @return array Array of product IDs.
     */
    protected function value_pack_get_related_products_by_cart_ids($limit = 4)
    {
        if (!function_exists('WC')) {
            return [];
        }

        $cart = WC()->cart;
        if (is_null($cart)) {
            return [];
        }

        $cart_items = $cart->get_cart();
        if (empty($cart_items)) {
            return [];
        }

        $related_products = [];
        foreach ($cart_items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $related = wc_get_related_products($product_id, $limit);
            $related_products = array_merge($related_products, $related);
        }

        // Remove duplicates and randomize
        $related_products = array_unique($related_products);
        shuffle($related_products);

        return array_slice($related_products, 0, $limit);
    }

    /**
     * Get search icon SVG markup.
     *
     * @return string SVG markup
     */
    public static function vp_get_serach_icon_svg()
    {
        return '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M7.98563 1.84033C8.28798 1.84033 8.59032 1.86143 8.89091 1.90186C8.84345 1.89482 8.79774 1.88955 8.75028 1.88252C9.33212 1.96338 9.89989 2.11807 10.4413 2.34482C10.3991 2.32725 10.3569 2.30967 10.3147 2.29209C10.654 2.43623 10.9809 2.60674 11.2903 2.80361C11.4257 2.88975 11.5593 2.98115 11.6876 3.07607C11.7175 3.09893 11.7491 3.12178 11.779 3.14463C11.6366 3.0374 11.7491 3.12178 11.7825 3.14814C11.8475 3.20088 11.9126 3.25537 11.9776 3.30986C12.2237 3.52256 12.454 3.75283 12.6649 3.99893C12.7141 4.05693 12.7634 4.11494 12.8108 4.17471C12.8266 4.1958 12.9093 4.30127 12.8477 4.22041C12.788 4.14307 12.8425 4.21338 12.853 4.22744C12.8688 4.24854 12.8829 4.26787 12.8987 4.28896C12.9936 4.41904 13.085 4.55088 13.1712 4.68623C13.3663 4.99561 13.5368 5.3208 13.6792 5.6583C13.6616 5.61611 13.644 5.57393 13.6265 5.53174C13.855 6.07842 14.0114 6.65146 14.0905 7.23682C14.0835 7.18936 14.0782 7.14365 14.0712 7.09619C14.1485 7.6833 14.1485 8.2792 14.0712 8.86807C14.0782 8.82061 14.0835 8.7749 14.0905 8.72744C14.0114 9.31455 13.855 9.8876 13.6265 10.4325C13.644 10.3903 13.6616 10.3481 13.6792 10.306C13.5509 10.6083 13.4015 10.9001 13.2309 11.1813C13.1466 11.3185 13.0587 11.4538 12.9655 11.5839C12.9216 11.6454 12.8759 11.7069 12.8302 11.7667C12.7897 11.8212 12.9181 11.656 12.8583 11.7298C12.8477 11.7438 12.8372 11.7562 12.8266 11.7702C12.7985 11.8071 12.7686 11.8423 12.7388 11.8774C12.5313 12.1271 12.3046 12.3608 12.062 12.5771C12.0057 12.628 11.9477 12.6772 11.8897 12.7265C11.8599 12.7511 11.8317 12.7757 11.8018 12.7985C11.7702 12.8249 11.6296 12.9286 11.779 12.8179C11.6507 12.9146 11.5206 13.0095 11.387 13.0974C11.0477 13.3206 10.6891 13.5122 10.3147 13.6722L10.4413 13.6194C9.89989 13.8462 9.33212 14.0026 8.75028 14.0817C8.79774 14.0747 8.84345 14.0694 8.89091 14.0624C8.29149 14.1415 7.68329 14.1433 7.08212 14.0624C7.12958 14.0694 7.17528 14.0747 7.22274 14.0817C6.64266 14.0026 6.07489 13.8479 5.53524 13.6212C5.57743 13.6388 5.61962 13.6563 5.66181 13.6739C5.35419 13.5438 5.05712 13.3909 4.77411 13.2169C4.62997 13.129 4.48759 13.0341 4.35048 12.9356C4.31884 12.9128 4.28895 12.8899 4.25731 12.8671C4.24149 12.8548 4.21337 12.8407 4.20282 12.8249C4.20106 12.8231 4.31532 12.9128 4.25556 12.8653C4.18876 12.8126 4.12372 12.7599 4.05868 12.7054C3.80731 12.4927 3.57001 12.2606 3.35204 12.0128C3.29579 11.9495 3.2413 11.8845 3.18856 11.8194C3.16395 11.7896 3.1411 11.7597 3.11649 11.7298C3.1077 11.7175 3.09716 11.7052 3.08837 11.6929C3.20263 11.837 3.12528 11.7403 3.10067 11.7087C3.00399 11.5786 2.91083 11.4468 2.8247 11.3097C2.61903 10.9897 2.44325 10.654 2.29384 10.3042C2.31141 10.3464 2.32899 10.3886 2.34657 10.4308C2.11981 9.89111 1.96513 9.32334 1.88602 8.74326C1.89306 8.79072 1.89833 8.83643 1.90536 8.88389C1.8245 8.28447 1.8245 7.67627 1.90536 7.07686C1.89833 7.12432 1.89306 7.17002 1.88602 7.21748C1.96513 6.6374 2.11981 6.06963 2.34657 5.52998C2.32899 5.57217 2.31141 5.61436 2.29384 5.65654C2.42391 5.34893 2.57684 5.05186 2.75087 4.76885C2.83876 4.62471 2.93368 4.48232 3.03212 4.34521C3.05497 4.31357 3.07782 4.28369 3.10067 4.25205C3.11298 4.23623 3.12704 4.20811 3.14286 4.19756C3.14462 4.1958 3.05497 4.31006 3.10243 4.25029C3.15516 4.1835 3.2079 4.11846 3.26239 4.05342C3.47509 3.80205 3.70712 3.56475 3.95497 3.34678C4.01825 3.29053 4.08329 3.23604 4.14833 3.1833C4.17821 3.15869 4.20809 3.13584 4.23798 3.11123C4.25028 3.10244 4.26259 3.09189 4.27489 3.08311C4.13075 3.19736 4.22743 3.12002 4.25907 3.09541C4.38915 2.99873 4.52098 2.90557 4.65809 2.81943C4.97802 2.61377 5.31376 2.43799 5.66356 2.28857C5.62138 2.30615 5.57919 2.32373 5.537 2.34131C6.07665 2.11455 6.64442 1.95986 7.2245 1.88076C7.17704 1.88779 7.13134 1.89307 7.08388 1.9001C7.38095 1.86143 7.68329 1.84033 7.98563 1.84033C8.26161 1.84033 8.52528 1.59775 8.51298 1.31299C8.50067 1.02822 8.28095 0.785645 7.98563 0.785645C6.60399 0.787402 5.22059 1.18818 4.06395 1.94756C2.94423 2.68232 2.01786 3.72471 1.45888 4.94639C1.17059 5.57568 0.963172 6.23311 0.86825 6.91865C0.762782 7.66572 0.768055 8.40225 0.882313 9.14932C1.08973 10.4958 1.71552 11.7649 2.61903 12.7827C3.51024 13.7864 4.69677 14.53 5.98524 14.9026C7.50751 15.3438 9.20028 15.27 10.6681 14.6653C11.4257 14.3524 12.1147 13.9341 12.7352 13.3962C13.2837 12.9198 13.7583 12.3485 14.138 11.7298C14.929 10.436 15.2981 8.87685 15.1575 7.36514C15.0837 6.56885 14.8868 5.81299 14.5722 5.07998C14.2804 4.40322 13.8725 3.78096 13.3874 3.22549C12.3468 2.03721 10.8878 1.19873 9.33388 0.913965C8.88915 0.833105 8.43739 0.787402 7.98563 0.785645C7.70966 0.785645 7.44598 1.02822 7.45829 1.31299C7.47059 1.59951 7.69032 1.84033 7.98563 1.84033Z" fill="#1D1D1D"/>
            <path d="M12.3309 13.0762L13.6809 14.4262L15.8237 16.5689L16.3141 17.0594C16.5093 17.2545 16.8679 17.2686 17.0595 17.0594C17.2528 16.8484 17.2686 16.5215 17.0595 16.3141C16.6095 15.8641 16.1595 15.4141 15.7095 14.9641C14.9958 14.2504 14.2804 13.535 13.5667 12.8213C13.4032 12.6578 13.2397 12.4943 13.0763 12.3309C12.8811 12.1357 12.5225 12.1217 12.3309 12.3309C12.1376 12.54 12.1218 12.867 12.3309 13.0762Z" fill="#1D1D1D"/>
        </svg>
        ';
    }

    /**
     * Get close icon SVG markup.
     *
     * @return string SVG markup
     */
    public static function value_pack_get_close_icon_svg()
    {
        return '<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M0.988364 10.4999C0.891779 10.4999 0.797359 10.4713 0.717046 10.4176C0.636734 10.364 0.574137 10.2877 0.537173 10.1985C0.500209 10.1092 0.49054 10.0111 0.509387 9.91633C0.528235 9.8216 0.574753 9.73459 0.643057 9.6663L9.66633 0.64303C9.75791 0.55145 9.88212 0.5 10.0116 0.5C10.1412 0.5 10.2654 0.55145 10.3569 0.64303C10.4485 0.734611 10.5 0.858822 10.5 0.988337C10.5 1.11785 10.4485 1.24206 10.3569 1.33364L1.33367 10.3569C1.28837 10.4023 1.23454 10.4383 1.17528 10.4628C1.11602 10.4874 1.0525 10.5 0.988364 10.4999Z" fill="#1D1D1D"/>
            <path d="M10.0116 10.4999C9.94747 10.5 9.88395 10.4874 9.82469 10.4628C9.76543 10.4383 9.71161 10.4023 9.6663 10.3569L0.643031 1.33364C0.55145 1.24206 0.5 1.11785 0.5 0.988337C0.5 0.858822 0.55145 0.734611 0.643031 0.64303C0.734611 0.55145 0.858822 0.5 0.988337 0.5C1.11785 0.5 1.24206 0.55145 1.33364 0.64303L10.3569 9.6663C10.4252 9.73459 10.4717 9.8216 10.4906 9.91633C10.5094 10.0111 10.4998 10.1092 10.4628 10.1985C10.4258 10.2877 10.3632 10.364 10.2829 10.4176C10.2026 10.4713 10.1082 10.4999 10.0116 10.4999Z" fill="#1D1D1D"/>
        </svg>
        ';
    }
}
?>