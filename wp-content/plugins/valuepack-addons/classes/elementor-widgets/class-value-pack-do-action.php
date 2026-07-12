<?php
/**
 * Do Action Widget
 *
 * @package valuepack-addons/cube/classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;

class Value_Pack_Do_Action extends Value_Pack_Widget_Base {

    public function get_name() {
        return 'value_pack_do_action';
    }

    public function get_title() {
        return __( 'Do Action', 'valuepack-addons' );
    }

    public function get_icon() {
        return 'eicon-code vpack-icon';
    }

    public function get_categories() {
        return [ 'value_pack' ];
    }

    protected function _register_controls() {
        // Predefined Actions Control
        $this->start_controls_section(
            'action_section',
            [
                'label' => __( 'Action Settings', 'valuepack-addons' ),
            ]
        );

        $this->add_control(
            'action_type',
            [
                'label' => __( 'Select Action', 'valuepack-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'predefined' => __( 'Predefined Action', 'valuepack-addons' ),
                    'custom' => __( 'Custom Action', 'valuepack-addons' ),
                ],
                'default' => 'custom',
            ]
        );

        $this->add_control(
            'predefined_action',
            [
                'label' => __( 'Predefined Action', 'valuepack-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_predefined_actions(),
                'condition' => [
                    'action_type' => 'predefined',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'custom_action',
            [
                'label' => __( 'Custom Action', 'valuepack-addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter your action', 'valuepack-addons' ),
                'condition' => [
                    'action_type' => 'custom',
                ],
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_predefined_actions() {
        // Return empty array if WooCommerce not active
        $default_actions = array();
        if (class_exists('WooCommerce') && function_exists('value_pack_get_woocommerce_product_hooks')) {
            $hooks = value_pack_get_woocommerce_product_hooks();
            $default_actions = is_array($hooks) ? $hooks : array();
        }
        return apply_filters( 'value_pack_do_action_options', $default_actions );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Only allow administrators to execute actions
        if (!current_user_can('manage_options')) {
            return;
        }

        if ('predefined' === $settings['action_type'] && !empty($settings['predefined_action'])) {
            // Sanitize predefined action
            $valuepack_action = sanitize_key($settings['predefined_action']);
            if (array_key_exists($valuepack_action, $this->get_predefined_actions())) {
                do_action($valuepack_action); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
            }
        } elseif ('custom' === $settings['action_type'] && !empty($settings['custom_action'])) {
            // Sanitize and verify nonce for custom actions
            $valuepack_action = sanitize_key($settings['custom_action']);
                do_action($valuepack_action); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
        }
    }

    protected function _content_template() {
        ?>
        <# 
        var action = '';
        if ( 'predefined' === settings.action_type && settings.predefined_action ) {
            action = settings.predefined_action;
        } else if ( 'custom' === settings.action_type && settings.custom_action ) {
            action = settings.custom_action;
        }
        #>
        {{{ action ? action : '' }}}
        <?php
    }
}
