<?php

/**
 * NextWP Sites Manager
 *
 * Handles the display and management of NextWP starter templates in the admin area.
 * Provides template browsing, previews, and installation initiation.
 *
 * @package    NextWP
 * @subpackage Admin
 * @since      1.0.0
 * @author     NextWP Team
 * @copyright  2024 NextWP
 * @license    GPL-2.0+
 */

defined('ABSPATH') || exit;

/**
 * Class NextWP_Sites
 *
 * Manages all template-related functionality including:
 * - Template listing and display
 * - Template previews
 * - Installation initiation
 */
final class NextWP_Sites
{

    /**
     * Base URL for the NextWP API
     * @var string
     */
    private $api_base_url;

    /**
     * Current template ID being viewed
     * @var int
     */
    private $template_id = 0;

    /**
     * Nonce action for admin operations
     * @var string
     */
    private $nonce_action = 'nextwp_template_operations';

    /**
     * Constructor.
     * Initializes API base URL and sets up necessary actions.
     */
    public function __construct()
    {
        $this->api_base_url = NextWP_Load::$api_base_url;
    }

    /**
     * Renders the custom admin page for NextWP templates.
     */
    public function render_nextwp_templates_page()
    {
        // Properly escape the plugin URL
        $loader_image_url = esc_url(plugins_url('assets/images/nextwp-loader-2.gif', plugin_dir_path(__FILE__)));

        echo '<div id="nwp-template-loader-main" class="nwp-template-loader">
                        <div class="nwp-loader-text">
                            <img src="' . $loader_image_url . '" alt="NextWP Loader">
                        </div>
                    </div>';
        echo '<div id="nextwp-wizard" class="nextwp-step-wrapper" style="display:none;">';
        $this->render_nextwp_header();
        echo '<div class="nextwp-step-wrapper-content">';
        if (!isset($_GET['id'])) {
            echo '<div class="nextwp-steps-progress-wrapper"><div class="nextwp-steps-progress-bar"></div></div>';
        }
        $this->render_steps();
        echo '</div>';
        echo '</div>';
    }

    private function render_nextwp_header()
    {
        // Verify nonce for template operations
        $template_nonce = wp_create_nonce('nextwp_template_operations');

        // Get and sanitize template data
        $template_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        $template = null;

        if ($template_id > 0) {
            $template = nextwp_single_resources($template_id, true);
        }

        $template_status = is_array($template) && isset($template['status']) ? $template['status'] : '';

        // Sanitize admin URL
        $admin_url = esc_url(admin_url('admin.php?page=nextwp-sites'));

        ?>
        <div class="nextwp-sites-header <?php echo isset($_GET['id']) ? 'nextwp-header-final-step' : ''; ?>">
            <div>
                <button class="nextwp-prev-btn" style="display: none;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.204 10.0008L12.9165 13.7133L11.856 14.7738L7.083 10.0008L11.856 5.2278L12.9165 6.2883L9.204 10.0008Z"
                            fill="white" />
                    </svg>
                    Back
                </button>
            </div>
            <div>
                <a href="<?php echo $admin_url; ?>" style="display:flex;align-items:center;justify-content:center;">
                    <svg width="128" height="16" viewBox="0 0 128 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.302 15.242C14.73 15.242 14.18 15.11 13.476 14.45L4.456 6.354V15H0.32V2.79C0.32 0.831999 1.508 0.0399998 2.806 0.0399998C3.356 0.0399998 3.906 0.171999 4.632 0.831999L13.652 8.928V0.281999H17.81V12.47C17.81 14.428 16.622 15.242 15.302 15.242ZM25.0714 11.216H37.5894L34.6634 15H20.4954V0.281999H37.5454L34.5974 4.11H25.0714V5.87H36.7314L34.1134 9.214H25.0714V11.216ZM54.1478 0.281999H60.0438L55.4018 4.528C53.3558 6.376 52.6738 6.97 52.1238 7.388C52.6518 7.74 53.3118 8.356 55.6878 10.578L60.3958 15H53.9718L48.9558 10.138L43.7858 15H38.1318L42.9278 10.578C45.1718 8.51 45.8098 7.982 46.3598 7.564C45.8098 7.168 45.2378 6.662 42.9278 4.506L38.4178 0.281999H44.7318L49.4178 4.858L54.1478 0.281999ZM62.6414 0.281999H79.3614L76.4134 4.11H71.1554V15H66.6014V4.11H59.6934L62.6414 0.281999ZM97.8536 13.68L95.4996 9.522C94.2236 7.256 93.6296 6.156 93.1236 5.1C92.6616 6.156 92.0676 7.234 90.7916 9.5L88.4376 13.68C87.9096 14.626 87.1176 15.242 85.9296 15.242C84.7856 15.242 83.7736 14.582 83.3776 13.218L79.6376 0.281999H84.1916L85.5776 5.408C86.0396 7.058 86.3476 8.268 86.5676 9.456C87.0516 8.422 87.6236 7.3 88.6356 5.386L90.6156 1.69C91.3856 0.238 92.1336 0.0399998 93.2116 0.0399998C94.3116 0.0399998 95.0816 0.238 95.8516 1.69L97.8096 5.408C98.9096 7.432 99.4156 8.466 99.8776 9.456C100.12 8.29 100.45 7.036 100.89 5.408L102.276 0.281999H106.72L102.914 13.218C102.518 14.582 101.506 15.242 100.362 15.242C99.1956 15.242 98.4036 14.648 97.8536 13.68ZM127.492 5.804C127.492 9.06 124.83 11.238 120.254 11.238H112.554V15H108.022V7.476H120.694C122.102 7.476 122.916 6.794 122.916 5.782C122.916 4.748 122.102 4.11 120.694 4.11H108.022L111.036 0.281999H120.276C124.83 0.281999 127.492 2.526 127.492 5.804Z"
                            fill="url(#paint0_linear_31_1997)" />
                        <defs>
                            <linearGradient id="paint0_linear_31_1997" x1="-1" y1="7.5" x2="129" y2="7.5"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4ABDFB" />
                                <stop offset="1" stop-color="#AA37FC" />
                            </linearGradient>
                        </defs>
                    </svg>
                </a>
            </div>
            <div>
                <button class="nwp-sync-live" id="sync-now-button" style="display: none;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.9028 15.6752C13.5418 16.8545 11.8008 17.5025 10 17.5C5.85775 17.5 2.5 14.1422 2.5 9.99999C2.5 8.39799 3.0025 6.91299 3.8575 5.69499L6.25 9.99999L4 9.99999C3.9999 11.1763 4.34556 12.3266 4.994 13.308C5.64244 14.2895 6.56505 15.0586 7.64712 15.5199C8.72918 15.9812 9.92296 16.1142 11.08 15.9025C12.2371 15.6907 13.3064 15.1436 14.155 14.329L14.9028 15.6752ZM5.09725 4.32474C6.45817 3.14549 8.19924 2.49748 10 2.49999C14.1423 2.49999 17.5 5.85774 17.5 9.99999C17.5 11.602 16.9975 13.087 16.1425 14.305L13.75 9.99999L16 9.99999C16.0001 8.82371 15.6544 7.67335 15.006 6.69193C14.3576 5.71052 13.435 4.94136 12.3529 4.48008C11.2708 4.01881 10.077 3.88577 8.91997 4.0975C7.7629 4.30924 6.69359 4.85642 5.845 5.67099L5.09725 4.32474Z"
                            fill="white" />
                    </svg>
                    Refresh the List
                </button>

                <?php
                if ($template_id > 0 && !isset($_GET['step'])) {
                    if ($template_status === 'success') {
                        echo '<button id="NWP_getNow" class="nextwp-primary-btn" data-nonce="' . esc_attr($template_nonce) . '">Use This Template</button>';
                    } else {
                        echo '<button class="nextwp-secondary-btn">Please Upgrade</button>';
                    }
                    echo '<button class="nextwp-preview-other-btn" id="nwp-preview-other">Preview Other Options</button>';
                }
                ?>
            </div>
        </div>
        <?php
    }


    private function render_steps()
    {
        $current_step = $this->get_current_step();
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this->template_id = intval($_GET['id']);
            $this->render_step_final();
        } else {
            echo '<div class="nextwp-steps-content">';
            $this->render_step_one($current_step);
            $this->render_step_two($current_step);
            $this->render_step_three($current_step);
            echo '</div>';
        }
    }

    private function get_current_step()
    {
        if (isset($_GET['step']) && in_array($_GET['step'], ['1', '2', '3', '4'])) {
            return intval($_GET['step']);
        }
        return 1;
    }

    private function render_step_one($current_step)
    {
        $style = ($current_step === 1) ? '' : 'style="display:none;"';
        ?>
        <div class="nextwp-step current-step nextwp-step-center" id="nextwp-step-1" <?php echo $style ?>
            data-current-step="<?php echo esc_attr($current_step); ?>">
            <h2><?php echo esc_html__('What do you want to build for?', 'nextwp-sites'); ?></h2>
            <p><?php echo esc_html__('Select all the options that apply', 'nextwp-sites'); ?></p>
            <div class="nextwp-options nextwp-steps-radio-options">
                <label>
                    <input type="radio" name="nextwp_site_purpose" value="clients" class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.5 6.2001V3.5001C7.5 3.2614 7.59482 3.03248 7.7636 2.8637C7.93239 2.69492 8.1613 2.6001 8.4 2.6001H15.6C15.8387 2.6001 16.0676 2.69492 16.2364 2.8637C16.4052 3.03248 16.5 3.2614 16.5 3.5001V6.2001H20.1C20.3387 6.2001 20.5676 6.29492 20.7364 6.4637C20.9052 6.63248 21 6.8614 21 7.1001V19.7001C21 19.9388 20.9052 20.1677 20.7364 20.3365C20.5676 20.5053 20.3387 20.6001 20.1 20.6001H3.9C3.66131 20.6001 3.43239 20.5053 3.2636 20.3365C3.09482 20.1677 3 19.9388 3 19.7001V7.1001C3 6.8614 3.09482 6.63248 3.2636 6.4637C3.43239 6.29492 3.66131 6.2001 3.9 6.2001H7.5ZM14.7 8.0001H9.3V18.8001H14.7V8.0001ZM7.5 8.0001H4.8V18.8001H7.5V8.0001ZM16.5 8.0001V18.8001H19.2V8.0001H16.5ZM9.3 4.4001V6.2001H14.7V4.4001H9.3Z"
                                fill="white" />
                        </svg>
                    </span>
                    Clients
                </label>
                <label>
                    <input type="radio" name="nextwp_site_purpose" value="myself" class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.89999 6.1954C3.89999 5.2036 4.70369 4.3999 5.69549 4.3999H18.3045C19.2963 4.3999 20.1 5.2036 20.1 6.1954V18.8044C20.1 19.2806 19.9108 19.7373 19.5741 20.074C19.2374 20.4107 18.7807 20.5999 18.3045 20.5999H5.69549C5.2193 20.5999 4.76261 20.4107 4.42588 20.074C4.08916 19.7373 3.89999 19.2806 3.89999 18.8044V6.1954ZM5.69999 6.1999V18.7999H18.3V6.1999H5.69999ZM8.37479 18.0619C7.82128 17.8221 7.29332 17.5273 6.79889 17.1817C7.36973 16.297 8.15336 15.5697 9.07805 15.0662C10.0027 14.5627 11.0389 14.2993 12.0918 14.2999C14.2518 14.2999 16.1571 15.3862 17.292 17.0422C16.807 17.401 16.2872 17.71 15.7404 17.9647C15.3236 17.3869 14.7754 16.9165 14.141 16.5922C13.5066 16.268 12.8042 16.0992 12.0918 16.0999C10.5483 16.0999 9.18479 16.8775 8.37479 18.0619ZM12 13.3999C11.5863 13.3999 11.1767 13.3184 10.7945 13.1601C10.4124 13.0018 10.0651 12.7698 9.77261 12.4773C9.4801 12.1848 9.24808 11.8375 9.08977 11.4554C8.93147 11.0732 8.84999 10.6636 8.84999 10.2499C8.84999 9.83624 8.93147 9.42663 9.08977 9.04445C9.24808 8.66227 9.4801 8.31502 9.77261 8.02252C10.0651 7.73001 10.4124 7.49798 10.7945 7.33968C11.1767 7.18138 11.5863 7.0999 12 7.0999C12.8354 7.0999 13.6366 7.43178 14.2274 8.02252C14.8181 8.61326 15.15 9.41447 15.15 10.2499C15.15 11.0853 14.8181 11.8865 14.2274 12.4773C13.6366 13.068 12.8354 13.3999 12 13.3999ZM12 11.5999C12.358 11.5999 12.7014 11.4577 12.9546 11.2045C13.2078 10.9513 13.35 10.6079 13.35 10.2499C13.35 9.89186 13.2078 9.54848 12.9546 9.29531C12.7014 9.04213 12.358 8.8999 12 8.8999C11.642 8.8999 11.2986 9.04213 11.0454 9.29531C10.7922 9.54848 10.65 9.89186 10.65 10.2499C10.65 10.6079 10.7922 10.9513 11.0454 11.2045C11.2986 11.4577 11.642 11.5999 12 11.5999Z"
                                fill="white" />
                        </svg>
                    </span>
                    Myself
                </label>
                <label>
                    <input type="radio" name="nextwp_site_purpose" value="company" class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.1 19.7H21.9V21.5H2.1V19.7H3.9V4.4C3.9 4.16131 3.99482 3.93239 4.1636 3.7636C4.33239 3.59482 4.56131 3.5 4.8 3.5H19.2C19.4387 3.5 19.6676 3.59482 19.8364 3.7636C20.0052 3.93239 20.1 4.16131 20.1 4.4V19.7ZM18.3 19.7V5.3H5.7V19.7H18.3ZM8.4 11.6H11.1V13.4H8.4V11.6ZM8.4 8H11.1V9.8H8.4V8ZM8.4 15.2H11.1V17H8.4V15.2ZM12.9 15.2H15.6V17H12.9V15.2ZM12.9 11.6H15.6V13.4H12.9V11.6ZM12.9 8H15.6V9.8H12.9V8Z"
                                fill="white" />
                        </svg>
                    </span>
                    My Company
                </label>
            </div>
        </div>
        <?php
    }

    private function render_step_two($current_step)
    {
        $style = ($current_step === 2) ? '' : 'style="display:none;"';
        ?>
        <div class="nextwp-step nextwp-step-center" id="nextwp-step-2" <?= $style ?>>
            <h2>What type of site are you looking to build today?</h2>
            <div class="nextwp-options  nextwp-steps-radio-options">
                <label>
                    <input type="radio" name="site_type" value="ecommerce" class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.25 11.7499V16.9999C13.25 17.1988 13.171 17.3896 13.0304 17.5303C12.8897 17.6709 12.699 17.7499 12.5 17.7499C12.3011 17.7499 12.1104 17.6709 11.9697 17.5303C11.8291 17.3896 11.75 17.1988 11.75 16.9999V11.7499C11.75 11.551 11.8291 11.3603 11.9697 11.2196C12.1104 11.079 12.3011 10.9999 12.5 10.9999C12.699 10.9999 12.8897 11.079 13.0304 11.2196C13.171 11.3603 13.25 11.551 13.25 11.7499ZM16.7038 11.6749L16.1788 16.9249C16.1684 17.0232 16.1776 17.1226 16.2057 17.2173C16.2339 17.3121 16.2805 17.4003 16.3429 17.477C16.4053 17.5537 16.4822 17.6172 16.5692 17.6641C16.6563 17.7109 16.7517 17.7401 16.85 17.7499C16.8753 17.7513 16.9007 17.7513 16.926 17.7499C17.1118 17.7497 17.2908 17.6805 17.4285 17.5558C17.5662 17.4311 17.6527 17.2598 17.6713 17.0749L18.1963 11.8249C18.2162 11.627 18.1566 11.4293 18.0308 11.2753C17.9049 11.1213 17.723 11.0236 17.525 11.0037C17.3271 10.9838 17.1294 11.0433 16.9754 11.1692C16.8214 11.2951 16.7237 11.477 16.7038 11.6749ZM8.2963 11.6749C8.27641 11.477 8.17871 11.2951 8.02469 11.1692C7.87068 11.0433 7.67297 10.9838 7.47505 11.0037C7.27713 11.0236 7.09522 11.1213 6.96934 11.2753C6.84345 11.4293 6.78391 11.627 6.8038 11.8249L7.3288 17.0749C7.34746 17.2606 7.43464 17.4326 7.57332 17.5574C7.712 17.6822 7.89222 17.7509 8.0788 17.7499C8.10409 17.7513 8.12944 17.7513 8.15474 17.7499C8.25274 17.7401 8.34784 17.711 8.43461 17.6644C8.52138 17.6178 8.59812 17.5546 8.66045 17.4783C8.72278 17.4021 8.76948 17.3143 8.79789 17.22C8.82629 17.1257 8.83584 17.0267 8.82599 16.9287L8.2963 11.6749ZM22.9935 8.84931L21.5807 19.4487C21.5314 19.8084 21.3538 20.1382 21.0805 20.3773C20.8073 20.6164 20.4569 20.7488 20.0938 20.7499H4.9063C4.54322 20.7488 4.19278 20.6164 3.91955 20.3773C3.64632 20.1382 3.46871 19.8084 3.41943 19.4487L2.00661 8.84931C1.99245 8.74337 2.0011 8.63562 2.03197 8.53328C2.06285 8.43095 2.11525 8.3364 2.18564 8.25597C2.25604 8.17554 2.34282 8.11109 2.44016 8.06693C2.53751 8.02277 2.64316 7.99993 2.75005 7.99994H6.90974L11.9375 2.25587C12.0079 2.17607 12.0945 2.11215 12.1915 2.06837C12.2885 2.02459 12.3936 2.00195 12.5 2.00195C12.6065 2.00195 12.7116 2.02459 12.8086 2.06837C12.9056 2.11215 12.9922 2.17607 13.0625 2.25587L18.0904 7.99994H22.25C22.3569 7.99993 22.4626 8.02277 22.5599 8.06693C22.6573 8.11109 22.7441 8.17554 22.8145 8.25597C22.8849 8.3364 22.9372 8.43095 22.9681 8.53328C22.999 8.63562 23.0076 8.74337 22.9935 8.84931ZM8.90286 7.99994H16.0972L12.5 3.889L8.90286 7.99994ZM21.3932 9.49994H3.60693L4.9063 19.2499H20.0938L21.3932 9.49994Z"
                                fill="white" />
                        </svg>
                    </span>
                    E-commerce Store
                </label>
                <label>
                    <input type="radio" name="site_type" value="business" disabled class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_33_2825)">
                                <path
                                    d="M23.75 20H22.25V9.5C22.4489 9.5 22.6397 9.42098 22.7803 9.28033C22.921 9.13968 23 8.94891 23 8.75C23 8.55109 22.921 8.36032 22.7803 8.21967C22.6397 8.07902 22.4489 8 22.25 8H17.75V5C17.9489 5 18.1397 4.92098 18.2803 4.78033C18.421 4.63968 18.5 4.44891 18.5 4.25C18.5 4.05109 18.421 3.86032 18.2803 3.71967C18.1397 3.57902 17.9489 3.5 17.75 3.5H4.25C4.05109 3.5 3.86032 3.57902 3.71967 3.71967C3.57902 3.86032 3.5 4.05109 3.5 4.25C3.5 4.44891 3.57902 4.63968 3.71967 4.78033C3.86032 4.92098 4.05109 5 4.25 5V20H2.75C2.55109 20 2.36032 20.079 2.21967 20.2197C2.07902 20.3603 2 20.5511 2 20.75C2 20.9489 2.07902 21.1397 2.21967 21.2803C2.36032 21.421 2.55109 21.5 2.75 21.5H23.75C23.9489 21.5 24.1397 21.421 24.2803 21.2803C24.421 21.1397 24.5 20.9489 24.5 20.75C24.5 20.5511 24.421 20.3603 24.2803 20.2197C24.1397 20.079 23.9489 20 23.75 20ZM20.75 9.5V20H17.75V9.5H20.75ZM5.75 5H16.25V20H14V15.5C14 15.3011 13.921 15.1103 13.7803 14.9697C13.6397 14.829 13.4489 14.75 13.25 14.75H8.75C8.55109 14.75 8.36032 14.829 8.21967 14.9697C8.07902 15.1103 8 15.3011 8 15.5V20H5.75V5ZM12.5 20H9.5V16.25H12.5V20ZM7.25 8C7.25 7.80109 7.32902 7.61032 7.46967 7.46967C7.61032 7.32902 7.80109 7.25 8 7.25H9.5C9.69891 7.25 9.88968 7.32902 10.0303 7.46967C10.171 7.61032 10.25 7.80109 10.25 8C10.25 8.19891 10.171 8.38968 10.0303 8.53033C9.88968 8.67098 9.69891 8.75 9.5 8.75H8C7.80109 8.75 7.61032 8.67098 7.46967 8.53033C7.32902 8.38968 7.25 8.19891 7.25 8ZM11.75 8C11.75 7.80109 11.829 7.61032 11.9697 7.46967C12.1103 7.32902 12.3011 7.25 12.5 7.25H14C14.1989 7.25 14.3897 7.32902 14.5303 7.46967C14.671 7.61032 14.75 7.80109 14.75 8C14.75 8.19891 14.671 8.38968 14.5303 8.53033C14.3897 8.67098 14.1989 8.75 14 8.75H12.5C12.3011 8.75 12.1103 8.67098 11.9697 8.53033C11.829 8.38968 11.75 8.19891 11.75 8ZM7.25 11.75C7.25 11.5511 7.32902 11.3603 7.46967 11.2197C7.61032 11.079 7.80109 11 8 11H9.5C9.69891 11 9.88968 11.079 10.0303 11.2197C10.171 11.3603 10.25 11.5511 10.25 11.75C10.25 11.9489 10.171 12.1397 10.0303 12.2803C9.88968 12.421 9.69891 12.5 9.5 12.5H8C7.80109 12.5 7.61032 12.421 7.46967 12.2803C7.32902 12.1397 7.25 11.9489 7.25 11.75ZM11.75 11.75C11.75 11.5511 11.829 11.3603 11.9697 11.2197C12.1103 11.079 12.3011 11 12.5 11H14C14.1989 11 14.3897 11.079 14.5303 11.2197C14.671 11.3603 14.75 11.5511 14.75 11.75C14.75 11.9489 14.671 12.1397 14.5303 12.2803C14.3897 12.421 14.1989 12.5 14 12.5H12.5C12.3011 12.5 12.1103 12.421 11.9697 12.2803C11.829 12.1397 11.75 11.9489 11.75 11.75Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_33_2825">
                                    <rect width="24" height="24" fill="white" transform="translate(0.5 0.5)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    Business Site
                    <span class="nextwp-coming-soon">coming soon</span>
                </label>
                <label>
                    <input type="radio" name="site_type" value="portfolio" disabled class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20.75 5.75H17V5C17 4.40326 16.7629 3.83097 16.341 3.40901C15.919 2.98705 15.3467 2.75 14.75 2.75H10.25C9.65326 2.75 9.08097 2.98705 8.65901 3.40901C8.23705 3.83097 8 4.40326 8 5V5.75H4.25C3.85218 5.75 3.47064 5.90804 3.18934 6.18934C2.90804 6.47064 2.75 6.85218 2.75 7.25V19.25C2.75 19.6478 2.90804 20.0294 3.18934 20.3107C3.47064 20.592 3.85218 20.75 4.25 20.75H20.75C21.1478 20.75 21.5294 20.592 21.8107 20.3107C22.092 20.0294 22.25 19.6478 22.25 19.25V7.25C22.25 6.85218 22.092 6.47064 21.8107 6.18934C21.5294 5.90804 21.1478 5.75 20.75 5.75ZM9.5 5C9.5 4.80109 9.57902 4.61032 9.71967 4.46967C9.86032 4.32902 10.0511 4.25 10.25 4.25H14.75C14.9489 4.25 15.1397 4.32902 15.2803 4.46967C15.421 4.61032 15.5 4.80109 15.5 5V5.75H9.5V5ZM20.75 7.25V14H4.25V7.25H20.75ZM20.75 19.25H4.25V15.5H20.75V19.25Z"
                                fill="white" />
                        </svg>
                    </span>
                    Portfolio
                    <span class="nextwp-coming-soon">coming soon</span>
                </label>
                <label>
                    <input type="radio" name="site_type" value="blog" disabled class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.5 6.5C3.5 6.30109 3.57902 6.11032 3.71967 5.96967C3.86032 5.82902 4.05109 5.75 4.25 5.75H20.75C20.9489 5.75 21.1397 5.82902 21.2803 5.96967C21.421 6.11032 21.5 6.30109 21.5 6.5C21.5 6.69891 21.421 6.88968 21.2803 7.03033C21.1397 7.17098 20.9489 7.25 20.75 7.25H4.25C4.05109 7.25 3.86032 7.17098 3.71967 7.03033C3.57902 6.88968 3.5 6.69891 3.5 6.5ZM4.25 11H16.25C16.4489 11 16.6397 10.921 16.7803 10.7803C16.921 10.6397 17 10.4489 17 10.25C17 10.0511 16.921 9.86032 16.7803 9.71967C16.6397 9.57902 16.4489 9.5 16.25 9.5H4.25C4.05109 9.5 3.86032 9.57902 3.71967 9.71967C3.57902 9.86032 3.5 10.0511 3.5 10.25C3.5 10.4489 3.57902 10.6397 3.71967 10.7803C3.86032 10.921 4.05109 11 4.25 11ZM20.75 13.25H4.25C4.05109 13.25 3.86032 13.329 3.71967 13.4697C3.57902 13.6103 3.5 13.8011 3.5 14C3.5 14.1989 3.57902 14.3897 3.71967 14.5303C3.86032 14.671 4.05109 14.75 4.25 14.75H20.75C20.9489 14.75 21.1397 14.671 21.2803 14.5303C21.421 14.3897 21.5 14.1989 21.5 14C21.5 13.8011 21.421 13.6103 21.2803 13.4697C21.1397 13.329 20.9489 13.25 20.75 13.25ZM16.25 17H4.25C4.05109 17 3.86032 17.079 3.71967 17.2197C3.57902 17.3603 3.5 17.5511 3.5 17.75C3.5 17.9489 3.57902 18.1397 3.71967 18.2803C3.86032 18.421 4.05109 18.5 4.25 18.5H16.25C16.4489 18.5 16.6397 18.421 16.7803 18.2803C16.921 18.1397 17 17.9489 17 17.75C17 17.5511 16.921 17.3603 16.7803 17.2197C16.6397 17.079 16.4489 17 16.25 17Z"
                                fill="white" />
                        </svg>
                    </span>
                    Blog
                    <span class="nextwp-coming-soon">coming soon</span>
                </label>
                <label>
                    <input type="radio" name="site_type" value="other" disabled class="required-field">
                    <span class="nextwp-radio-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.625 17.375C13.625 17.5975 13.559 17.815 13.4354 18C13.3118 18.185 13.1361 18.3292 12.9305 18.4144C12.725 18.4995 12.4988 18.5218 12.2805 18.4784C12.0623 18.435 11.8618 18.3278 11.7045 18.1705C11.5472 18.0132 11.44 17.8127 11.3966 17.5945C11.3532 17.3762 11.3755 17.15 11.4606 16.9445C11.5458 16.7389 11.69 16.5632 11.875 16.4396C12.06 16.316 12.2775 16.25 12.5 16.25C12.7984 16.25 13.0845 16.3685 13.2955 16.5795C13.5065 16.7905 13.625 17.0766 13.625 17.375ZM12.5 7.25C10.4319 7.25 8.75 8.76406 8.75 10.625V11C8.75 11.1989 8.82902 11.3897 8.96967 11.5303C9.11033 11.671 9.30109 11.75 9.5 11.75C9.69892 11.75 9.88968 11.671 10.0303 11.5303C10.171 11.3897 10.25 11.1989 10.25 11V10.625C10.25 9.59375 11.2597 8.75 12.5 8.75C13.7403 8.75 14.75 9.59375 14.75 10.625C14.75 11.6562 13.7403 12.5 12.5 12.5C12.3011 12.5 12.1103 12.579 11.9697 12.7197C11.829 12.8603 11.75 13.0511 11.75 13.25V14C11.75 14.1989 11.829 14.3897 11.9697 14.5303C12.1103 14.671 12.3011 14.75 12.5 14.75C12.6989 14.75 12.8897 14.671 13.0303 14.5303C13.171 14.3897 13.25 14.1989 13.25 14V13.9325C14.96 13.6184 16.25 12.2544 16.25 10.625C16.25 8.76406 14.5681 7.25 12.5 7.25ZM22.25 12.5C22.25 14.4284 21.6782 16.3134 20.6068 17.9168C19.5355 19.5202 18.0127 20.7699 16.2312 21.5078C14.4496 22.2458 12.4892 22.4389 10.5979 22.0627C8.70656 21.6865 6.96928 20.7579 5.60571 19.3943C4.24215 18.0307 3.31355 16.2934 2.93735 14.4021C2.56114 12.5108 2.75422 10.5504 3.49218 8.76884C4.23013 6.98726 5.47982 5.46451 7.08319 4.39317C8.68657 3.32183 10.5716 2.75 12.5 2.75C15.085 2.75273 17.5634 3.78084 19.3913 5.60872C21.2192 7.43661 22.2473 9.91498 22.25 12.5ZM20.75 12.5C20.75 10.8683 20.2661 9.27325 19.3596 7.91655C18.4531 6.55984 17.1646 5.50242 15.6571 4.87799C14.1497 4.25357 12.4909 4.09019 10.8905 4.40852C9.29017 4.72685 7.82016 5.51259 6.66637 6.66637C5.51259 7.82015 4.72685 9.29016 4.40853 10.8905C4.0902 12.4908 4.25358 14.1496 4.878 15.6571C5.50242 17.1646 6.55984 18.4531 7.91655 19.3596C9.27326 20.2661 10.8683 20.75 12.5 20.75C14.6873 20.7475 16.7843 19.8775 18.3309 18.3309C19.8775 16.7843 20.7475 14.6873 20.75 12.5Z"
                                fill="white" />
                        </svg>
                    </span>
                    Others
                    <span class="nextwp-coming-soon">coming soon</span>
                </label>
            </div>
        </div>
        <?php
    }

    private function render_step_three($current_step)
    {
        $style = ($current_step === 3) ? '' : 'style="display:none;"';
        $loader_image_url = esc_url(plugins_url('assets/images/nextwp-loader-2.gif', plugin_dir_path(__FILE__)));

        echo '<div class="nextwp-step nextwp-demos-list-step" id="nextwp-step-3" ' . $style . '>';
        echo '<div class="nextwp-templates-loader">
        <div class="nwp-loader-text">
                            <h6>Please Wait, Loading Demos List</h6>
                            <img src="' . $loader_image_url . '" alt="NextWP Loader">
                        </div>
        </div>';
        echo '<div class="nextwp-templates-content" style="display:none;">';
        echo '<div class="nextwp-templates-header">';
        echo '<h2>Choose a template</h2>';
        echo '</div>';
        echo $this->nextwp_get_template_list();
        echo '</div>';
        echo '</div>';
    }

    private function render_step_final()
    {
        $template = nextwp_single_template_data();

        // Validate template data structure
        if (!is_array($template) || empty($template)) {
            return esc_html__('Invalid template data', 'nextwp');
        }

        $template_title = isset($template['title']) ? sanitize_text_field($template['title']) : '';
        $image_url = isset($template['template_image']) ? esc_url_raw($template['template_image']) : '';
        $template_source = isset($template['template_source']) ? esc_url_raw($template['template_source']) : '';
        $theme = isset($template['theme']) && is_array($template['theme']) ? $template['theme'] : [];
        $plugins = isset($template['plugins']) && is_array($template['plugins']) ? $template['plugins'] : [];
        $tags = isset($template['tags']) && is_array($template['tags']) ? $template['tags'] : [];
        $total_plugins = count($template['plugins']);

        $id = isset($_GET['id']) ? absint($_GET['id']) : '';
        $step = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : '';
        $show_template_display = ($id && $step !== '2');
        $show_process_step_2 = ($id && $step === '2');

        // Create nonce for template operations
        $template_nonce = wp_create_nonce('nextwp_template_operations');

        // Properly escape the loader image URL
        $loader_image_url = esc_url(plugins_url('assets/images/nextwp-loader-2.gif', plugin_dir_path(__FILE__)));
        $build_image_url = esc_url(plugins_url('assets/images/nextwp-loader.gif', plugin_dir_path(__FILE__)));

        ?>
        <div class="template-display-container" style="<?php echo $show_template_display ? '' : 'display:none;'; ?>">
            <div class="nextwp-templates-slider-container">
                <div class="nextwp-templates-slider swiper">
                    <div class="swiper-wrapper">
                        <?php
                        echo $this->nextwp_all_templates_data();
                        ?>
                    </div>
                </div>
            </div>
            <div class="nextwp-template-preview">
                <?php if ($template_source): ?>
                    <div id="nwp-template-loader" class="nwp-template-loader">
                        <div class="nwp-loader-text">
                            <h6>Please wait, loading live demo resources...</h6>
                            <img src="<?php echo $loader_image_url; ?>" alt="NextWP Loader">
                        </div>
                    </div>
                    <iframe id="nwp-template-frame" class="nwp-template-frame" src="<?php echo esc_url($template_source); ?>"
                        width="100%" height="600px" frameborder="0"></iframe>
                <?php endif; ?>
            </div>
        </div>
        <div id="nwp_process_step_2" style="<?php echo $show_process_step_2 ? '' : 'display:none;'; ?>"
            data-total-plugin="<?php echo esc_attr($total_plugins); ?>"
            data-total-steps="<?php echo esc_attr($total_plugins + 4); ?>">
            <div class="nextwp-final-step-wrapper">
                <div id="nwp-progress-bar"></div>
                <div class="nextwp-final-step">
                    <h2 class="nwp-import-process-title">Get Started with Your Complete Website Setup.</h2>
                    <p class="nwp-import-process-desc"></p>
                    <div id="nwp-loader-container">
                        <div id="nwp-start-png-image">
                            <div class="nwp-start-image-outer" style="display: flex;">
                                <div class="nwp-start-image">
                                    <img src="<?php echo esc_url($image_url); ?>"
                                        alt="<?php echo esc_attr($template_title); ?>">
                                </div>
                                <?php
                                if ($id > 0) {
                                    $_template = nextwp_single_resources($id, true);
                                }

                                $template_status = is_array($_template) && isset($_template['status']) ? $_template['status'] : '';
                                if ($template_status === 'success') { ?>
                                    <button id="startProcess" class="startProcessBtn"
                                        nextwp-template-id="<?php echo esc_attr($this->template_id); ?>"
                                        data-nonce="<?php echo esc_attr($template_nonce); ?>">Let's Start Process</button>
                                <?php } else { ?>
                                    <button class="startProcessBtn please-upgrade">Please Upgrade</button>
                                <?php } ?>
                            </div>

                        </div>

                        <div class="hide" id="nwp_import_process_status">
                            <h3>Activity Log</h3>
                        </div>
                        <div id="nwp_import_process_loader" style="display:none">
                            <div class="nwp-loading-image">
                                <img src="<?php echo $build_image_url; ?>" alt="Building Process">
                            </div>
                        </div>
                        <div id="nwp_after_import_tweet" class="shoutout-container" style="display:none">
                            <div class="nwp_after-import-demo-image">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($template_title); ?>">
                            </div>
                            <p class="shoutout-text">
                                I built the website in seconds with NextWP.<br>
                                Can't believe how easy that was
                            </p>
                            <a class="visit-website" href="<?php echo esc_url(home_url()); ?>" target="_blank">
                                Visit Your Website
                            </a>
                            <div class="nwp-share-website-container">
                                <p>Share on</p>
                                <div class="nwp-social-icons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(home_url()); ?>"
                                        target="_blank" rel="noopener noreferrer" class="nwp-facebook-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21.75 12.0001C21.7469 14.383 20.8726 16.6826 19.2917 18.4657C17.7109 20.2488 15.5326 21.3922 13.1671 21.6807C13.1145 21.6867 13.0611 21.6814 13.0107 21.6653C12.9602 21.6491 12.9137 21.6224 12.8743 21.587C12.8348 21.5516 12.8034 21.5082 12.782 21.4597C12.7605 21.4112 12.7496 21.3587 12.75 21.3057V14.2501H15C15.1028 14.2503 15.2045 14.2294 15.2989 14.1886C15.3933 14.1479 15.4783 14.0882 15.5487 14.0132C15.619 13.9382 15.6732 13.8495 15.7078 13.7527C15.7424 13.6559 15.7568 13.553 15.75 13.4504C15.7334 13.2576 15.6444 13.0781 15.5009 12.9482C15.3574 12.8183 15.1701 12.7475 14.9765 12.7501H12.75V10.5001C12.75 10.1023 12.908 9.72074 13.1893 9.43944C13.4706 9.15813 13.8521 9.0001 14.25 9.0001H15.75C15.8528 9.00032 15.9545 8.97941 16.0489 8.93865C16.1433 8.89789 16.2283 8.83816 16.2987 8.76316C16.369 8.68817 16.4232 8.59951 16.4578 8.5027C16.4924 8.4059 16.5068 8.303 16.5 8.20041C16.4834 8.00722 16.3941 7.82752 16.2502 7.69755C16.1063 7.56757 15.9185 7.49701 15.7246 7.5001H14.25C13.4543 7.5001 12.6912 7.81617 11.6286 8.37878C11.566 8.94139 11.25 9.70445 11.25 10.5001V12.7501H8.99996C8.89714 12.7499 8.79538 12.7708 8.70099 12.8115C8.60659 12.8523 8.52159 12.912 8.45126 12.987C8.38092 13.062 8.32676 13.1507 8.29213 13.2475C8.2575 13.3443 8.24315 13.4472 8.24996 13.5498C8.26655 13.743 8.35579 13.9227 8.49968 14.0527C8.64357 14.1826 8.8314 14.2532 9.02527 14.2501H11.25V21.3076C11.2503 21.3605 11.2394 21.4129 11.218 21.4613C11.1967 21.5097 11.1653 21.5531 11.126 21.5885C11.0866 21.6239 11.0403 21.6506 10.9899 21.6668C10.9395 21.683 10.8863 21.6884 10.8337 21.6826C8.40498 21.3868 6.1758 20.19 4.5874 18.329C2.99901 16.4681 2.16716 14.0786 2.25652 11.6335C2.44402 6.57104 6.54464 2.45541 11.6109 2.25854C12.9225 2.20773 14.231 2.42199 15.4579 2.8885C16.6848 3.35502 17.8051 4.0642 18.7516 4.97363C19.6981 5.88306 20.4515 6.97405 20.9667 8.18136C21.4819 9.38866 21.7483 10.6875 21.75 12.0001Z"
                                                fill="white" />
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text=I built the website in seconds with NextWP. Can't believe how easy that was!&url=<?php echo urlencode(home_url()); ?>"
                                        target="_blank" rel="noopener noreferrer" class="nwp-twitter-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20.1566 20.6109C20.092 20.7286 19.9971 20.8267 19.8817 20.8951C19.7662 20.9636 19.6346 20.9998 19.5004 21H15.0004C14.8741 21 14.75 20.9681 14.6393 20.9073C14.5287 20.8465 14.4352 20.7587 14.3676 20.6522L10.5716 14.6869L5.05536 20.7544C4.92089 20.8988 4.7349 20.9845 4.53772 20.9927C4.34054 21.0009 4.14805 20.9311 4.002 20.7984C3.85595 20.6657 3.7681 20.4807 3.7575 20.2836C3.74689 20.0866 3.81439 19.8933 3.94536 19.7456L9.7363 13.3706L3.86755 4.15313C3.79527 4.03973 3.75481 3.90898 3.7504 3.77458C3.746 3.64018 3.7778 3.50707 3.8425 3.38918C3.90719 3.27129 4.0024 3.17296 4.11813 3.10449C4.23387 3.03603 4.36589 2.99993 4.50036 3H9.00036C9.12659 3.00004 9.25077 3.03194 9.36139 3.09274C9.472 3.15353 9.56549 3.24127 9.63318 3.34781L13.4291 9.31312L18.9454 3.24562C19.0798 3.10117 19.2658 3.01555 19.463 3.00731C19.6602 2.99907 19.8527 3.06888 19.9987 3.20161C20.1448 3.33435 20.2326 3.51929 20.2432 3.71636C20.2538 3.91343 20.1863 4.10674 20.0554 4.25438L14.2644 10.6247L20.1332 19.8478C20.2051 19.9613 20.2451 20.0919 20.2493 20.2262C20.2534 20.3604 20.2214 20.4933 20.1566 20.6109Z"
                                                fill="white" />
                                        </svg>
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(home_url()); ?>"
                                        target="_blank" rel="noopener noreferrer" class="nwp-linkedin-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20.25 2.25H3.75C3.35218 2.25 2.97064 2.40804 2.68934 2.68934C2.40804 2.97064 2.25 3.35218 2.25 3.75V20.25C2.25 20.6478 2.40804 21.0294 2.68934 21.3107C2.97064 21.592 3.35218 21.75 3.75 21.75H20.25C20.6478 21.75 21.0294 21.592 21.3107 21.3107C21.592 21.0294 21.75 20.6478 21.75 20.25V3.75C21.75 3.35218 21.592 2.97064 21.3107 2.68934C21.0294 2.40804 20.6478 2.25 20.25 2.25ZM9 16.5C9 16.6989 8.92098 16.8897 8.78033 17.0303C8.63968 17.171 8.44891 17.25 8.25 17.25C8.05109 17.25 7.86032 17.171 7.71967 17.0303C7.57902 16.8897 7.5 16.6989 7.5 16.5V10.5C7.5 10.3011 7.57902 10.1103 7.71967 9.96967C7.86032 9.82902 8.05109 9.75 8.25 9.75C8.44891 9.75 8.63968 9.82902 8.78033 9.96967C8.92098 10.1103 9 10.3011 9 10.5V16.5ZM8.25 9C8.0275 9 7.80999 8.93402 7.62498 8.8104C7.43998 8.68679 7.29578 8.51109 7.21064 8.30552C7.12549 8.09995 7.10321 7.87375 7.14662 7.65552C7.19002 7.43729 7.29717 7.23684 7.4545 7.0795C7.61184 6.92217 7.81229 6.81502 8.03052 6.77162C8.24875 6.72821 8.47495 6.75049 8.68052 6.83564C8.88609 6.92078 9.06179 7.06498 9.1854 7.24998C9.30902 7.43499 9.375 7.6525 9.375 7.875C9.375 8.17337 9.25647 8.45952 9.0455 8.6705C8.83452 8.88147 8.54837 9 8.25 9ZM17.25 16.5C17.25 16.6989 17.171 16.8897 17.0303 17.0303C16.8897 17.171 16.6989 17.25 16.5 17.25C16.3011 17.25 16.1103 17.171 15.9697 17.0303C15.829 16.8897 15.75 16.6989 15.75 16.5V13.125C15.75 12.6277 15.5525 12.1508 15.2008 11.7992C14.8492 11.4475 14.3723 11.25 13.875 11.25C13.3777 11.25 12.9008 11.4475 12.5492 11.7992C12.1975 12.1508 12 12.6277 12 13.125V16.5C12 16.6989 11.921 16.8897 11.7803 17.0303C11.6397 17.171 11.4489 17.25 11.25 17.25C11.0511 17.25 10.8603 17.171 10.7197 17.0303C10.579 16.8897 10.5 16.6989 10.5 16.5V10.5C10.5009 10.3163 10.5693 10.1393 10.692 10.0026C10.8148 9.86596 10.9834 9.7791 11.166 9.75852C11.3485 9.73794 11.5323 9.78508 11.6824 9.891C11.8325 9.99691 11.9385 10.1542 11.9803 10.3331C12.4877 9.98894 13.0792 9.78947 13.6914 9.75611C14.3036 9.72276 14.9133 9.85679 15.455 10.1438C15.9968 10.4308 16.4501 10.86 16.7664 11.3852C17.0826 11.9105 17.2498 12.5119 17.25 13.125V16.5Z"
                                                fill="white" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <!-- Fireworks, Confetti, and Balloons Canvas -->
                            <canvas id="fireworksCanvas" style="display:none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }


    private function nextwp_get_template_list()
    {
        $templates = $this->fetch_templates();

        if (empty($templates)) {
            return '<div class="nwp-no-templates-found"><p>Unable to fetch templates. Please try again later.</p></div>';
        } else {
            return $this->render_templates($templates);
        }
    }

    /**
     * Fetch templates from the API.
     *
     * @return array|null
     */
    private function fetch_templates()
    {

        return get_and_save_all_templates();
    }

    /**
     * Render templates with categories and filters.
     *
     * @param array $templates
     * @return string
     */
    private function render_templates($templates)
    {
        ob_start();
        ?>
            <div class="template-grid">
                <?php
                foreach ($templates as $template) {
                    $this->render_template_card($template);
                }
                ?>
            </div>
            <?php
            return ob_get_clean();
    }

    /**
     * Renders a single template card.
     *
     * @param array $template Template data.
     */
    private function render_template_card($template)
    {

        // Sanitize and validate template data
        $image_url = isset($template['featured_image_url']) ? esc_url_raw($template['featured_image_url']) : '';
        $title = isset($template['title']['rendered']) ? sanitize_text_field($template['title']['rendered']) : __('No Title', 'text-domain');
        $post_id = isset($template['id']) ? absint($template['id']) : 0;
        $taxonomies = isset($template['taxonomies']) ? $template['taxonomies'] : [];
        $is_paid = in_array('Paid', $taxonomies);
        $is_free = in_array('Free', $taxonomies);

        // Check if essential data exists before rendering
        if ($image_url && $title && $post_id) {
        ?>
            <div class="template-card">
                <div class="template-card-thumbnail">
                    <span class="nextwp-template-paid">
                        <?php
                        if ( $is_paid ) {
                            echo "Paid";
                        } elseif ( $is_free ) {
                            echo "Free";
                        }
                            
                        ?>
                    </span>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" data-template-id="<?php echo esc_attr($post_id); ?>" class="template-image" />
                    <div class="template-card-actions">
                        <a class="template-preview-btn" href="<?php echo esc_url(admin_url('admin.php?page=nextwp-sites&id=' . $post_id)); ?>" target="_blank">Preview</a>
                        <a class="template-select-btn" href="<?php echo esc_url(admin_url('admin.php?page=nextwp-sites&id=' . $post_id . '&step=2')); ?>">Select</a>
                    </div>
                </div>
                <div class="template-card-content">
                    <h3 class="template-title">
                        <?php echo esc_html($title); ?>
                    </h3>
                </div>
            </div>
            <?php
        }
    }

    private function nextwp_all_templates_data()
    {
        $templates = $this->fetch_templates();
        ob_start();
        if (!empty($templates) && is_array($templates)) {
            foreach ($templates as $template_item) {
                if ($template_item['id'] === $this->template_id) {
                    continue; // skip current template
                }
                $image_url = isset($template_item['featured_image_url']) ? esc_url_raw($template_item['featured_image_url']) : '';
                $title = isset($template_item['title']['rendered']) ? sanitize_text_field($template_item['title']['rendered']) : __('No Title', 'text-domain');
                $post_id = isset($template_item['id']) ? absint($template_item['id']) : 0;
                ?>
                    <div class="swiper-slide">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=nextwp-sites&id=' . $post_id)); ?>">
                            <div class="template-slide-card">
                                <div class="template-slide-thumbnail">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                                <h4><?php echo esc_html($title); ?></h4>
                            </div>
                        </a>
                    </div>
                <?php }
            return ob_get_clean();
        } else {
            return '<p>Unable to fetch templates. Please try again later.</p>';
        }
    }
}
