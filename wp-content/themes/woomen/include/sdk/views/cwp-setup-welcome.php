<?php
$CWP_License_verification = new CWP_License_verification();
$PATH_URL                 = $CWP_License_verification->PATH_URL;
?>
<!---* You can design your own welcome screen of setup process here *-->
<div class="importer-tab-started-main">
    <header class="importer-step-form-lisance-header">
        <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/woomen logo.png'); ?>" alt="">
        <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/PMF.png'); ?>" alt="">
    </header>
    <div class="importer-tab-started-inner">
        <div class="importer-tab-started-inner-img">
            <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/welcome-main-img.png'); ?>" alt="">
        </div>
        <h2 class="welcome-text"><?php echo esc_html__('All-In-One WooCommerce', 'woomen'); ?><br><span class="importer-text-gradient"><?php echo esc_html__('Store Builder', 'woomen'); ?></span> <?php echo esc_html__('For Mankind', 'woomen'); ?></h2>
        <h6 class="welcome-des"><?php echo esc_html__('Total Customizability and High Conversions Unlocked!', 'woomen'); ?></h6>
        <div class="importer-features">
            <div class="importer-started-feature">
                <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/verify-icon.png'); ?>" alt="">
                <p><?php echo esc_html__('No Coding Required', 'woomen'); ?></p>
            </div>
            <div class="importer-started-feature">
                <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/verify-icon.png'); ?>" alt="">
                <p><?php echo esc_html__('No Paid Plugins Required', 'woomen'); ?></p>
            </div>
        </div>
        <?php if (!get_option('woomen_import_completed_once')) { ?>
            <div class="next-step-import importer-get-started" id="next-step-import"><?php echo esc_html__('Get Started Now', 'woomen'); ?>
                <span>
                    <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.6144 0.444336H0.892229C0.709893 0.444336 0.535024 0.516769 0.406093 0.6457C0.277162 0.774631 0.204729 0.9495 0.204729 1.13184C0.204729 1.31417 0.277162 1.48904 0.406093 1.61797C0.535024 1.7469 0.709893 1.81934 0.892229 1.81934H12.2662L-1.84058 15.9268C-1.90878 15.9896 -1.96358 16.0655 -2.00169 16.15C-2.0398 16.2345 -2.06043 16.3258 -2.06235 16.4184C-2.06427 16.5111 -2.04744 16.6032 -2.01286 16.6892C-1.97828 16.7752 -1.92667 16.8533 -1.86114 16.9188C-1.7956 16.9844 -1.71749 17.036 -1.6315 17.0705C-1.54551 17.1051 -1.45342 17.122 -1.36076 17.12C-1.2681 17.1181 -1.17678 17.0975 -1.0923 17.0594C-1.00782 17.0213 -0.931911 16.9665 -0.869146 16.8983L13.2384 2.79077V14.1655C13.2384 14.3478 13.3108 14.5227 13.4397 14.6516C13.5686 14.7805 13.7435 14.853 13.9259 14.853C14.1082 14.853 14.2831 14.7805 14.412 14.6516C14.5409 14.5227 14.6134 14.3478 14.6134 14.1655V1.44396C14.613 1.17907 14.5077 0.925131 14.3204 0.737763C14.1332 0.550396 13.8793 0.444881 13.6144 0.444336Z" fill="#000" />
                    </svg>
                </span>
            </div>
            <div class="shopify-inspired-container">
                <img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/shopify-icon.png'); ?>" alt="">
                <p>
                    <?php echo esc_html__('Checkout Experience', 'woomen'); ?>
                    <br>
                    <b><?php echo esc_html__('Inspired by Shopify', 'woomen'); ?></b>
                </p>
            </div>
        <?php } else { ?>
            <ul class="importer-footer-links">
                <li>
                    <a href="https://woo.demowp.io/#prebuilt-demos" target="_blank">
                        <?php echo esc_html__('PREBUILT Stores', 'woomen'); ?>
                    </a>
                </li>
                <li>
                    <a href="https://woo.demowp.io/#features" target="_blank">
                        <?php echo esc_html__('FEATURES', 'woomen'); ?>
                    </a>
                </li>
                <li>
                    <a href="https://woo.demowp.io/#elements" target="_blank">
                        <?php echo esc_html__('ELEMENTS', 'woomen'); ?>
                    </a>
                </li>
                <li>
                    <a href="https://docs.zemowp.com/" target="_blank">
                        <?php echo esc_html__('Documentation', 'woomen'); ?>
                    </a>
                </li>
            </ul>
        <?php } ?>
    </div>
</div>