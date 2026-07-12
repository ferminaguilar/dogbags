<?php
/**
 * Tabs Module
 *
 * @package valuepack-addons/cube/modules
 * @version 1.0.0
 */

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;
 

if (!function_exists('value_pack_add_tab_controls')) {
    function value_pack_add_tab_controls($element, $section_id, $args)
    {

        if ('nested-tabs' === $element->get_name() && 'section_tabs' === $section_id) {
            $element->start_controls_section(
                'tab_options_section',
                [
                    'label' => __('Options - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT, // Main content tab
                ]
            );
            $element->add_control(
                'tab_trigger_options',
                [
                    'label' => esc_html__('Trigger - Value Pack', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'default' => esc_html__('Default', 'valuepack-addons'),
                        'hover' => esc_html__('Hover', 'valuepack-addons'),
                    ],
                    'default' => 'default', // Default value
                ]
            );
 
            $element->add_control(
                'value_pack_enable_loader', 
                [
                    'label' => esc_html__('Enable Loader', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no', 
                    'condition' => [
                        'tab_trigger_options' => 'default', 
                    ],
                ]
            );
 
            $element->add_control(
                'value_pack_loader_bg_color', 
                [
                    'label' => esc_html__('Loader Background Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff', 
                    'condition' => [
                        'tab_trigger_options' => 'default', 
                        'value_pack_enable_loader' => 'yes', 
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wc-vp-image-loader' => 'background-color: {{VALUE}};',
                    ],
                ]
            ); 
            $element->add_control(
                'value_pack_loader_color',  
                [
                    'label' => esc_html__('Loader Color', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',  
                    'condition' => [
                        'tab_trigger_options' => 'default', 
                        'value_pack_enable_loader' => 'yes', 
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wc-vp-image-loader::after' => 'border-top-color: {{VALUE}};',
                    ],
                ]
            );

            $element->end_controls_section();


            // Start the Style tab section
            $element->start_controls_section(
                'tab_options_style_section',
                [
                    'label' => __('Options - Value Pack', 'valuepack-addons'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE, // Move to the Style tab
                ]
            );

            // Add Flex Display Option
            $element->add_responsive_control(
                'display_flex',
                [
                    'label' => esc_html__('Display', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'block' => esc_html__('Block', 'valuepack-addons'),
                        'flex' => esc_html__('Flex', 'valuepack-addons'),
                    ],
                    'default' => 'flex', // Default to flex
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'display: {{VALUE}};',
                    ],
                ]
            );

            // Conditionally show Flex controls
            $element->add_responsive_control(
                'align_items',
                [
                    'label' => esc_html__('Align Items', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'flex-start' => esc_html__('Start', 'valuepack-addons'),
                        'center' => esc_html__('Center', 'valuepack-addons'),
                        'flex-end' => esc_html__('End', 'valuepack-addons'),
                        'stretch' => esc_html__('Stretch', 'valuepack-addons'),
                    ],
                    'condition' => [
                        'display_flex' => 'flex', // Show only when flex is selected
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'align-items: {{VALUE}};',
                    ],
                ]
            );

            $element->add_responsive_control(
                'justify_content',
                [
                    'label' => esc_html__('Justify Content', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'flex-start' => esc_html__('Start', 'valuepack-addons'),
                        'center' => esc_html__('Center', 'valuepack-addons'),
                        'flex-end' => esc_html__('End', 'valuepack-addons'),
                        'space-between' => esc_html__('Space Between', 'valuepack-addons'),
                        'space-around' => esc_html__('Space Around', 'valuepack-addons'),
                    ],
                    'condition' => [
                        'display_flex' => 'flex', // Show only when flex is selected
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );


            // Add Flex Direction Control (visible if Flex is selected)
            $element->add_responsive_control(
                'flex_direction',
                [
                    'label' => esc_html__('Flex Direction', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'row' => esc_html__('Row', 'valuepack-addons'),
                        'row-reverse' => esc_html__('Row Reverse', 'valuepack-addons'),
                        'column' => esc_html__('Column', 'valuepack-addons'),
                        'column-reverse' => esc_html__('Column Reverse', 'valuepack-addons'),
                    ],
                    'default' => 'row',
                    'condition' => [
                        'display_flex' => 'flex',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'flex-direction: {{VALUE}};',
                    ],
                ]
            );

            // Add Position Select Control
            $element->add_responsive_control(
                'position_select',
                [
                    'label' => esc_html__('Position', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'static' => esc_html__('Static', 'valuepack-addons'),
                        'absolute' => esc_html__('Absolute', 'valuepack-addons'),
                        'relative' => esc_html__('Relative', 'valuepack-addons'),
                        'fixed' => esc_html__('Fixed', 'valuepack-addons'),
                    ],
                    'default' => 'static',
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'position: {{VALUE}};',
                    ],
                ]
            );

            // Add Top Control (visible if position is absolute)
            $element->add_responsive_control(
                'tab_width_top',
                [
                    'label' => esc_html__('Tabs Width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .e-n-tabs-heading button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $element->add_responsive_control(
                'position_top',
                [
                    'label' => esc_html__('Top', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Add Bottom Control (visible if position is absolute)
            $element->add_responsive_control(
                'position_bottom',
                [
                    'label' => esc_html__('Bottom', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Add Left Control (visible if position is absolute)
            $element->add_responsive_control(
                'position_left',
                [
                    'label' => esc_html__('Left', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max'  => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Add Right Control (visible if position is absolute)
            $element->add_responsive_control(
                'position_right',
                [
                    'label' => esc_html__('Right', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Add Z-Index Control (visible if position is absolute)
            $element->add_responsive_control(
                'position_z_index',
                [
                    'label' => esc_html__('Z-Index', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => -9999,
                    'max' => 9999,
                    'condition' => [
                        'position_select' => 'absolute',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'z-index: {{VALUE}};',
                    ],
                ]
            );
            // Add Right Control (visible if position is absolute)
            $element->add_responsive_control(
                'tabs_Width',
                [
                    'label' => esc_html__('width', 'valuepack-addons'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'em'],
                    'range' => [
                        'px' => ['min' => -500, 'max' => 500],
                        '%' => ['min' => -100, 'max' => 100],
                        'em' => ['min' => -50, 'max' => 50],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.hover .e-n-tabs-heading,{{WRAPPER}} .e-n-tabs-heading' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // End the Style tab section
            $element->end_controls_section();
        }
    }
    add_action('elementor/element/before_section_start', 'value_pack_add_tab_controls', 10, 3);
}

if (!function_exists('value_pack_render_tabs')) {
    function value_pack_render_tabs($widget)
    {
        // Ensure this only applies to the 'nested-tabs' widget
        if ('nested-tabs' === $widget->get_name()) {
            // Get the widget settings
            $settings = $widget->get_settings_for_display();

            // Add the class to the nested tabs wrapper
            $nwxtwp_loader_class = '';
            if(isset($settings['value_pack_enable_loader']) && !empty($settings['value_pack_enable_loader'])){
              $nwxtwp_loader_class =   $settings['value_pack_enable_loader'];
            }
            $widget->add_render_attribute('nested-tabs', 'class', $settings['tab_trigger_options']);
            $widget->add_render_attribute(
                '_wrapper',
                [
                    'class' => 'vpack-nested-tabs ' . $settings['tab_trigger_options'] .' ' .$nwxtwp_loader_class,
                ]
            );

            // Check if Elementor is in edit mode
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                // This ensures it also works in the editor (preview mode)
?>
                <script type="text/javascript">
                    (function($) {
                        $(document).ready(function() {
                            var triggerOption = '<?php echo esc_js($settings['tab_trigger_options']); ?>';
                            $('.elementor-element-<?php echo esc_attr($widget->get_id()); ?> .e-n-tabs-heading').addClass(triggerOption+' '+$nwxtwp_loader_class);
                        });
                    })(jQuery);
                </script>
<?php
            }
        }
    }

    add_action('elementor/frontend/before_render', 'value_pack_render_tabs');
}
