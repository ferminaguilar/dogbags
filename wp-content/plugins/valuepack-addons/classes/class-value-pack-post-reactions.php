<?php

/**
 * Value Pack Post Reactions System.
 *
 * @package valuepack-addons/classes
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Value Pack Post Reactions Class.
 *
 * @class Value_Pack_Post_Reactions
 */
class Value_Pack_Post_Reactions
{

    /**
     * Instance
     *
     * @var Value_Pack_Post_Reactions
     */
    private static $instance = null;

    /**
     * Meta key for storing reactions
     *
     * @var string
     */
    private $meta_key = 'cwp_post_reactions';

    /**
     * Cookie prefix
     *
     * @var string
     */
    private $cookie_prefix = 'cwp_post_reacted_';
    
    /**
     * Meta key for storing user reactions (which users reacted with which types)
     *
     * @var string
     */
    private $user_reactions_meta_key = 'cwp_post_user_reactions';

    /**
     * Get instance
     *
     * @return Value_Pack_Post_Reactions
     * @since  1.0.0
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @since  1.0.0
     */
    private function init_hooks()
    {
        add_action('wp_ajax_cwp_post_add_reaction', array($this, 'ajax_add_reaction'));
        add_action('wp_ajax_nopriv_cwp_post_add_reaction', array($this, 'ajax_add_reaction'));
        add_action('wp_ajax_cwp_post_get_reactions', array($this, 'ajax_get_reactions'));
        add_action('wp_ajax_nopriv_cwp_post_get_reactions', array($this, 'ajax_get_reactions'));
    }

    /**
     * Add reaction via AJAX
     *
     * @since  1.0.0
     */
    public function ajax_add_reaction()
    {
        check_ajax_referer('value_pack_ajax', 'nonce');

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $reaction_type = isset($_POST['reaction_type']) ? sanitize_text_field($_POST['reaction_type']) : '';

        if (empty($post_id) || empty($reaction_type)) {
            wp_send_json_error(array(
                'message' => esc_html__('Invalid request.', 'valuepack-addons')
            ));
        }

        // Check if post exists
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(array(
                'message' => esc_html__('Invalid post.', 'valuepack-addons')
            ));
        }

        // Check if user already reacted with this specific reaction type (cookie check)
        $cookie_name = $this->get_cookie_name($post_id, $reaction_type);
        if (isset($_COOKIE[$cookie_name])) {
            wp_send_json_error(array(
                'message' => esc_html__('You have already reacted with this reaction type.', 'valuepack-addons'),
                'reacted_type' => $reaction_type
            ));
        }

        // Get current reactions (counts per type)
        $reactions = get_post_meta($post_id, $this->meta_key, true);
        if (!is_array($reactions)) {
            $reactions = array();
        }

        // Get user reactions (track which users reacted with which types)
        $user_reactions = get_post_meta($post_id, $this->user_reactions_meta_key, true);
        if (!is_array($user_reactions)) {
            $user_reactions = array();
        }

        // Initialize reaction type if not exists
        if (!isset($reactions[$reaction_type])) {
            $reactions[$reaction_type] = 0;
        }
        if (!isset($user_reactions[$reaction_type])) {
            $user_reactions[$reaction_type] = array();
        }

        // Get user identifier (IP address or user ID)
        $user_identifier = $this->get_user_identifier();

        // Check if this user already reacted with this type (double check)
        if (in_array($user_identifier, $user_reactions[$reaction_type])) {
            wp_send_json_error(array(
                'message' => esc_html__('You have already reacted with this reaction type.', 'valuepack-addons'),
                'reacted_type' => $reaction_type
            ));
        }

        // Increment reaction count
        $reactions[$reaction_type]++;

        // Add user identifier to this reaction type
        $user_reactions[$reaction_type][] = $user_identifier;

        // Save reactions (counts)
        update_post_meta($post_id, $this->meta_key, $reactions);

        // Save user reactions (track who reacted)
        update_post_meta($post_id, $this->user_reactions_meta_key, $user_reactions);

        // Set cookie for this specific reaction type (30 days)
        setcookie($cookie_name, '1', time() + (30 * 24 * 60 * 60), '/');

        wp_send_json_success(array(
            'message' => esc_html__('Reaction added successfully.', 'valuepack-addons'),
            'reactions' => $reactions,
            'reaction_type' => $reaction_type
        ));
    }

    /**
     * Get reactions via AJAX
     *
     * @since  1.0.0
     */
    public function ajax_get_reactions()
    {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        if (empty($post_id)) {
            wp_send_json_error(array(
                'message' => esc_html__('Invalid request.', 'valuepack-addons')
            ));
        }

        $reactions = get_post_meta($post_id, $this->meta_key, true);
        if (!is_array($reactions)) {
            $reactions = array();
        }

        // Check which reaction types the user has reacted with
        $reacted_types = $this->get_user_reacted_types($post_id);

        wp_send_json_success(array(
            'reactions' => $reactions,
            'reacted_types' => $reacted_types
        ));
    }

    /**
     * Get reactions for a post
     *
     * @param int $post_id Post ID
     * @return array
     * @since  1.0.0
     */
    public function get_reactions($post_id)
    {
        $reactions = get_post_meta($post_id, $this->meta_key, true);
        return is_array($reactions) ? $reactions : array();
    }

    /**
     * Check if user has reacted to a post with a specific reaction type
     *
     * @param int $post_id Post ID
     * @param string $reaction_type Reaction type to check (optional)
     * @return bool|array Returns bool if reaction_type provided, array of reacted types if not
     * @since  1.0.0
     */
    public function has_reacted($post_id, $reaction_type = '')
    {
        if (empty($reaction_type)) {
            // Return all reacted types if no specific type provided
            $reacted_types = $this->get_user_reacted_types($post_id);
            return !empty($reacted_types) ? $reacted_types : false;
        }
        
        $cookie_name = $this->get_cookie_name($post_id, $reaction_type);
        return isset($_COOKIE[$cookie_name]);
    }
    
    /**
     * Get all reaction types the user has reacted with for a post
     *
     * @param int $post_id Post ID
     * @return array Array of reaction types
     * @since  1.0.0
     */
    public function get_user_reacted_types($post_id)
    {
        $reacted_types = array();
        
        // Get user identifier
        $user_identifier = $this->get_user_identifier();
        
        // Get user reactions meta
        $user_reactions = get_post_meta($post_id, $this->user_reactions_meta_key, true);
        if (!is_array($user_reactions)) {
            $user_reactions = array();
        }
        
        // Check which reaction types this user has reacted with (from meta)
        foreach ($user_reactions as $reaction_type => $user_ids) {
            if (in_array($user_identifier, $user_ids)) {
                $reacted_types[] = $reaction_type;
            }
        }
        
        // Also check cookies as fallback (for cases where meta might not be synced)
        // Get all reactions to check cookies
        $reactions = $this->get_reactions($post_id);
        foreach (array_keys($reactions) as $reaction_type) {
            $cookie_name = $this->get_cookie_name($post_id, $reaction_type);
            if (isset($_COOKIE[$cookie_name]) && !in_array($reaction_type, $reacted_types)) {
                $reacted_types[] = $reaction_type;
            }
        }
        
        return array_unique($reacted_types);
    }
    
    /**
     * Get cookie name for a specific post and reaction type
     *
     * @param int $post_id Post ID
     * @param string $reaction_type Reaction type
     * @return string
     * @since  1.0.0
     */
    private function get_cookie_name($post_id, $reaction_type)
    {
        return $this->cookie_prefix . $post_id . '_' . $reaction_type;
    }
    
    /**
     * Get user identifier (user ID if logged in, IP address if not)
     *
     * @return string
     * @since  1.0.0
     */
    private function get_user_identifier()
    {
        $user_id = get_current_user_id();
        if ($user_id > 0) {
            return 'user_' . $user_id;
        }
        
        // Use IP address for guests
        $ip = $this->get_user_ip();
        return 'ip_' . md5($ip);
    }
    
    /**
     * Get user IP address
     *
     * @return string
     * @since  1.0.0
     */
    private function get_user_ip()
    {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }

    /**
     * Get reaction count for a specific type
     *
     * @param int $post_id Post ID
     * @param string $reaction_type Reaction type
     * @return int
     * @since  1.0.0
     */
    public function get_reaction_count($post_id, $reaction_type)
    {
        $reactions = $this->get_reactions($post_id);
        return isset($reactions[$reaction_type]) ? intval($reactions[$reaction_type]) : 0;
    }

    /**
     * Initialize the class
     *
     * @since  1.0.0
     */
    public static function init()
    {
        $CubeClass = __CLASS__;
        new $CubeClass;
    }
}

