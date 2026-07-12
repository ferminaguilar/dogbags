<?php
/**
 * Dynamic Page Title Tag for Elementor
 *
 * @package     ValuePackAddons
 *
 * Provides a dynamic tag for Elementor that displays the title/heading of archive,
 * taxonomy, search, and post type archive pages.
 *
 * Returns:
 * - Term name on taxonomy/category/tag archives
 * - Post type label on post type archives
 * - Search query on search pages
 * - Author name on author archives
 * - Date archive title on date archives
 * - Fallback for other contexts
 */

defined('ABSPATH') || exit;

/**
 * Class Value_Pack_Dynamic_Page_Title_Tag
 *
 * Elementor dynamic tag that returns the page title based on archive/taxonomy/search context.
 */
class Value_Pack_Dynamic_Page_Title_Tag extends \Elementor\Core\DynamicTags\Data_Tag
{
    /**
     * Get the name of the dynamic tag
     *
     * @return string
     */
    public function get_name()
    {
        return 'vp-dynamic-page-title';
    }

    /**
     * Get the title of the dynamic tag
     *
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Dynamic Page Title', 'valuepack-addons');
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
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    /**
     * Get the value of the dynamic tag
     *
     * @param array $options Optional options array
     * @return string The archive/taxonomy/search page title
     */
    public function get_value(array $options = [])
    {
        $format = $this->get_settings('title_format');

        if (is_search()) {
            $title = get_search_query();
            if (empty($title)) {
                if(isset($_GET['post_type']) && !empty($_GET['post_type'])) {
                    $post_type = $_GET['post_type'];
                    $post_type_obj = get_post_type_object($post_type);
                    if($post_type_obj && isset($post_type_obj->labels->name)) {
                        $title = $post_type_obj->labels->name;
                    }
                }
                if(empty($title)) {
                    $title = esc_html__('Search Results', 'valuepack-addons');
                }
            }
            return $this->maybe_wrap_format($title, $format, 'search');
        }

        if (is_404()) {
            return esc_html__('Page Not Found', 'valuepack-addons');
        }

        $queried_object = get_queried_object();

        // Taxonomy archive: category, tag, or custom taxonomy
        if (is_category() || is_tag() || is_tax()) {
            if ($queried_object instanceof \WP_Term) {
                return $this->maybe_wrap_format($queried_object->name, $format, 'term');
            }
            // Fallback
            $title = single_term_title('', false);
            return $this->maybe_wrap_format($title ?: esc_html__('Archive', 'valuepack-addons'), $format, 'term');
        }

        // Post type archive
        if (is_post_type_archive()) {
            if ($queried_object instanceof \WP_Post_Type) {
                $label = $queried_object->labels->name ?? $queried_object->label ?? $queried_object->name;
                return $this->maybe_wrap_format($label, $format, 'post_type');
            }
            $post_type = get_query_var('post_type');
            if (is_array($post_type)) {
                $post_type = reset($post_type);
            }
            if ($post_type) {
                $pt_obj = get_post_type_object($post_type);
                if ($pt_obj && isset($pt_obj->labels->name)) {
                    return $this->maybe_wrap_format($pt_obj->labels->name, $format, 'post_type');
                }
            }
            return $this->maybe_wrap_format(esc_html__('Archives', 'valuepack-addons'), $format, 'post_type');
        }

        // Author archive
        if (is_author()) {
            if ($queried_object instanceof \WP_User) {
                $name = $queried_object->display_name ?: $queried_object->user_login;
                return $this->maybe_wrap_format($name, $format, 'author');
            }
            return $this->maybe_wrap_format(esc_html__('Author Archive', 'valuepack-addons'), $format, 'author');
        }

        // Date archive
        if (is_date()) {
            $title = $this->get_date_archive_title();
            return $this->maybe_wrap_format($title, $format, 'date');
        }

        // Home / Blog (when not front page)
        if (is_home() && !is_front_page()) {
            $page_id = get_option('page_for_posts');
            $title = $page_id ? get_the_title($page_id) : esc_html__('Blog', 'valuepack-addons');
            return $this->maybe_wrap_format($title, $format, 'blog');
        }

        // Single post/page – optional: return post title
        if (is_singular() && 'yes' === $this->get_settings('use_on_singular')) {
            return $this->maybe_wrap_format(get_the_title(), $format, 'singular');
        }

        // Fallback
        return '';
    }

    /**
     * Get date archive title (year, month, day)
     *
     * @return string
     */
    private function get_date_archive_title()
    {
        if (is_year()) {
            return get_the_date(_x('Y', 'yearly archives date format', 'valuepack-addons'));
        }
        if (is_month()) {
            return get_the_date(_x('F Y', 'monthly archives date format', 'valuepack-addons'));
        }
        if (is_day()) {
            return get_the_date();
        }
        return esc_html__('Date Archives', 'valuepack-addons');
    }

    /**
     * Optionally wrap the title with a format (e.g. prefix/suffix)
     *
     * @param string $title  The title text
     * @param string $format Format key: 'plain', 'with_prefix', 'with_suffix', 'with_both'
     * @param string $context Context: 'term', 'post_type', 'search', 'author', 'date', 'blog', 'singular'
     * @return string
     */
    private function maybe_wrap_format($title, $format, $context)
    {
        if (empty($title) || 'plain' === $format) {
            return $title;
        }

        $prefix = $this->get_settings('title_prefix');
        $suffix = $this->get_settings('title_suffix');

        if ('with_prefix' === $format && !empty($prefix)) {
            return $prefix . $title;
        }
        if ('with_suffix' === $format && !empty($suffix)) {
            return $title . $suffix;
        }
        if ('with_both' === $format) {
            return ( $prefix ?? '' ) . $title . ( $suffix ?? '' );
        }

        return $title;
    }

    /**
     * Register controls for the dynamic tag
     */
    protected function register_controls()
    {
        $this->add_control(
            'title_format',
            [
                'label'   => esc_html__('Format', 'valuepack-addons'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'plain',
                'options' => [
                    'plain'       => esc_html__('Plain (title only)', 'valuepack-addons'),
                    'with_prefix' => esc_html__('With prefix', 'valuepack-addons'),
                    'with_suffix' => esc_html__('With suffix', 'valuepack-addons'),
                    'with_both'   => esc_html__('With prefix & suffix', 'valuepack-addons'),
                ],
            ]
        );

        $this->add_control(
            'title_prefix',
            [
                'label'     => esc_html__('Prefix', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => '',
                'condition' => [
                    'title_format' => ['with_prefix', 'with_both'],
                ],
            ]
        );

        $this->add_control(
            'title_suffix',
            [
                'label'     => esc_html__('Suffix', 'valuepack-addons'),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => '',
                'condition' => [
                    'title_format' => ['with_suffix', 'with_both'],
                ],
            ]
        );

        $this->add_control(
            'use_on_singular',
            [
                'label'       => esc_html__('Use on singular pages', 'valuepack-addons'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'default'     => '',
                'description' => esc_html__('When on a single post/page, show the post title instead of empty.', 'valuepack-addons'),
            ]
        );
    }
}
