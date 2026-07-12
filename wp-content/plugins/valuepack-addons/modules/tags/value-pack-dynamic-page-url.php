<?php
/**
 * Dynamic Page URL Tag for Elementor
 *
 * @package     ValuePackAddons
 *
 * Provides a dynamic tag for Elementor that returns URLs for various page types:
 * - Post type archive
 * - Single post (with post ID)
 * - Home, Blog, Search, Current page
 * - Author archive, Term archive, Date archive
 */

defined('ABSPATH') || exit;

/**
 * Class Value_Pack_Dynamic_Page_Url_Tag
 *
 * Elementor dynamic tag that returns page URLs based on selected type.
 */
class Value_Pack_Dynamic_Page_Url_Tag extends \Elementor\Core\DynamicTags\Data_Tag
{
    /**
     * Get the name of the dynamic tag
     *
     * @return string
     */
    public function get_name()
    {
        return 'vp-dynamic-page-url';
    }

    /**
     * Get the title of the dynamic tag
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Dynamic Page URL', 'valuepack-addons');
    }

    /**
     * Get the group this tag belongs to
     *
     * @return array
     */
    public function get_group()
    {
        return ['vp-tags'];
    }

    /**
     * Get the categories this tag belongs to
     *
     * @return array
     */
    public function get_categories()
    {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    /**
     * Get the value of the dynamic tag (URL)
     *
     * @param array $options Optional options array
     * @return string The generated URL
     */
    public function get_value(array $options = [])
    {
        $url_type = $this->get_settings('url_type');
        $url_type = !empty($url_type) ? $url_type : 'home';

        switch ($url_type) {
            case 'post_type_archive':
                return $this->get_post_type_archive_url();
            case 'single_post':
                return $this->get_single_post_url();
            case 'home':
                return esc_url_raw(home_url('/'));
            case 'blog':
                return $this->get_blog_url();
            case 'search':
                return $this->get_search_url();
            case 'current_page':
                return $this->get_current_page_url();
            case 'author_archive':
                return $this->get_author_archive_url();
            case 'term_archive':
                return $this->get_term_archive_url();
            case 'date_archive':
                return $this->get_date_archive_url();
            case 'logout':
                $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
                $current_url = home_url($request_uri);
                return esc_url_raw(wp_logout_url($current_url));
            case 'custom':
                return $this->get_custom_url();
            default:
                return esc_url_raw(home_url('/'));
        }
    }

    /**
     * Get post type archive URL
     */
    private function get_post_type_archive_url()
    {
        $post_type = $this->get_settings('post_type');
        if (empty($post_type) || !post_type_exists($post_type)) {
            return '#';
        }
        $url = get_post_type_archive_link($post_type);
        return $url ? esc_url_raw($url) : '#';
    }

    /**
     * Get single post URL
     */
    private function get_single_post_url()
    {
        $post_type = $this->get_settings('post_type');
        $post_id = absint($this->get_settings('post_id'));

        if (empty($post_type) || !post_type_exists($post_type)) {
            return '#';
        }

        if ($post_id > 0) {
            $post = get_post($post_id);
            if ($post && $post->post_type === $post_type && $post->post_status === 'publish') {
                return esc_url_raw(get_permalink($post_id));
            }
        }

        // Fallback: current post if on singular
        if (is_singular($post_type)) {
            return esc_url_raw(get_permalink());
        }

        return '#';
    }

    /**
     * Get blog/posts page URL
     */
    private function get_blog_url()
    {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            return esc_url_raw(get_permalink($page_for_posts));
        }
        return esc_url_raw(home_url('/'));
    }

    /**
     * Get search URL
     */
    private function get_search_url()
    {
        $search_query = $this->get_settings('search_query');
        if (!empty($search_query)) {
            return esc_url_raw(get_search_link($search_query));
        }
        return esc_url_raw(get_search_link());
    }

    /**
     * Get current page URL
     */
    private function get_current_page_url()
    {
        if (is_singular()) {
            return esc_url_raw(get_permalink());
        }
        return esc_url_raw(get_pagenum_link());
    }

    /**
     * Get author archive URL
     */
    private function get_author_archive_url()
    {
        $author_source = $this->get_settings('author_source');
        $user_id = 0;

        if ($author_source === 'specific' && !empty($this->get_settings('author_id'))) {
            $user_id = absint($this->get_settings('author_id'));
        } elseif (is_author()) {
            $user_id = get_queried_object_id();
        } elseif (is_singular()) {
            $user_id = (int) get_post_field('post_author', get_the_ID());
        }

        if ($user_id > 0) {
            return esc_url_raw(get_author_posts_url($user_id));
        }
        return '#';
    }

    /**
     * Get term archive URL
     */
    private function get_term_archive_url()
    {
        $term_source = $this->get_settings('term_source');
        $term_link = '#';

        if ($term_source === 'current' && (is_category() || is_tag() || is_tax())) {
            $term = get_queried_object();
            if ($term instanceof \WP_Term) {
                $term_link = get_term_link($term);
            }
        } elseif ($term_source === 'specific') {
            $taxonomy = $this->get_settings('taxonomy');
            $term_id = absint($this->get_settings('term_id'));
            if ($taxonomy && $term_id > 0) {
                $term_link = get_term_link($term_id, $taxonomy);
            }
        }

        return (!is_wp_error($term_link)) ? esc_url_raw($term_link) : '#';
    }

    /**
     * Get date archive URL
     */
    private function get_date_archive_url()
    {
        if (is_year()) {
            return esc_url_raw(get_year_link(get_query_var('year')));
        }
        if (is_month()) {
            return esc_url_raw(get_month_link(get_query_var('year'), get_query_var('monthnum')));
        }
        if (is_day()) {
            return esc_url_raw(get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')));
        }
        return esc_url_raw(home_url('/'));
    }

    /**
     * Get custom URL (from settings)
     */
    private function get_custom_url()
    {
        $custom_url = $this->get_settings('custom_url');
        if (is_array($custom_url) && !empty($custom_url['url'])) {
            return esc_url_raw($custom_url['url']);
        }
        if (is_string($custom_url) && !empty($custom_url)) {
            return esc_url_raw($custom_url);
        }
        return '#';
    }

    /**
     * Get post type options
     */
    private function get_post_type_options()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = ['' => esc_html__('Select Post Type', 'valuepack-addons')];
        foreach ($post_types as $pt) {
            if (in_array($pt->name, ['attachment', 'elementor_library', 'e-landing-page'], true)) {
                continue;
            }
            $options[$pt->name] = $pt->label;
        }
        return $options;
    }

    /**
     * Get taxonomy options
     */
    private function get_taxonomy_options()
    {
        $taxonomies = get_taxonomies(['public' => true], 'objects');
        $options = ['' => esc_html__('Select Taxonomy', 'valuepack-addons')];
        foreach ($taxonomies as $tax) {
            $options[$tax->name] = $tax->label;
        }
        return $options;
    }

    /**
     * Register controls
     */
    protected function register_controls()
    {
        $this->add_control(
            'url_type',
            [
                'label'   => esc_html__('URL Type', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'home',
                'options' => [
                    'post_type_archive' => esc_html__('Post Type Archive', 'valuepack-addons'),
                    'single_post'       => esc_html__('Single Post', 'valuepack-addons'),
                    'home'              => esc_html__('Home', 'valuepack-addons'),
                    'blog'              => esc_html__('Blog / Posts Page', 'valuepack-addons'),
                    'search'            => esc_html__('Search', 'valuepack-addons'),
                    'current_page'      => esc_html__('Current Page', 'valuepack-addons'),
                    'author_archive'    => esc_html__('Author Archive', 'valuepack-addons'),
                    'term_archive'      => esc_html__('Term Archive', 'valuepack-addons'),
                    'date_archive'      => esc_html__('Date Archive', 'valuepack-addons'),
                    'logout'            => esc_html__('Logout', 'valuepack-addons'),
                    'custom'            => esc_html__('Custom URL', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label'     => esc_html__('Post Type', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->get_post_type_options(),
                'default'   => 'post',
                'condition' => [
                    'url_type' => ['post_type_archive', 'single_post'],
                ],
            ]
        );

        $this->add_control(
            'post_id',
            [
                'label'       => esc_html__('Post ID', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => '',
                'placeholder' => esc_html__('Enter post ID', 'valuepack-addons'),
                'condition'   => [
                    'url_type' => 'single_post',
                ],
            ]
        );

        $this->add_control(
            'search_query',
            [
                'label'       => esc_html__('Search Query', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__('Leave empty for current search', 'valuepack-addons'),
                'condition'   => [
                    'url_type' => 'search',
                ],
            ]
        );

        $this->add_control(
            'author_source',
            [
                'label'     => esc_html__('Author Source', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'current',
                'options'   => [
                    'current'   => esc_html__('Current (author page / post author)', 'valuepack-addons'),
                    'specific'  => esc_html__('Specific Author', 'valuepack-addons'),
                ],
                'condition' => [
                    'url_type' => 'author_archive',
                ],
            ]
        );

        $this->add_control(
            'author_id',
            [
                'label'       => esc_html__('Author User ID', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => '',
                'placeholder' => esc_html__('Enter user ID', 'valuepack-addons'),
                'condition'   => [
                    'url_type'       => 'author_archive',
                    'author_source'  => 'specific',
                ],
            ]
        );

        $this->add_control(
            'term_source',
            [
                'label'     => esc_html__('Term Source', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'current',
                'options'   => [
                    'current'  => esc_html__('Current (on taxonomy page)', 'valuepack-addons'),
                    'specific' => esc_html__('Specific Term', 'valuepack-addons'),
                ],
                'condition' => [
                    'url_type' => 'term_archive',
                ],
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label'     => esc_html__('Taxonomy', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->get_taxonomy_options(),
                'condition' => [
                    'url_type'     => 'term_archive',
                    'term_source'  => 'specific',
                ],
            ]
        );

        $this->add_control(
            'term_id',
            [
                'label'       => esc_html__('Term ID', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 1,
                'default'     => '',
                'placeholder' => esc_html__('Enter term ID', 'valuepack-addons'),
                'condition'   => [
                    'url_type'     => 'term_archive',
                    'term_source'  => 'specific',
                ],
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label'       => esc_html__('Custom URL', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'condition'   => [
                    'url_type' => 'custom',
                ],
            ]
        );
    }
}
