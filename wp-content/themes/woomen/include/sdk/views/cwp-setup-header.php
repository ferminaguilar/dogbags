<?php
$CWP_License_verification = new CWP_License_verification();
$PATH_URL = $CWP_License_verification->PATH_URL;
?>
<!---* You can design your own header for all screens of setup process here *-->
<div class="importer-step-form-lisance-header importer-step-form-inner">
	<img src="<?php echo esc_url($PATH_URL . '/sdk/assets/images/importer/woomen logo.png'); ?>" alt="">
	<div class="importer-step-form-header-text">
		<h3><?php echo esc_html__('All-In-One WooCommerce', 'woomen'); ?><br><span class="importer-text-gradient"><?php echo esc_html__('Store Builder', 'woomen'); ?></span> <?php echo esc_html__('For Mankind', 'woomen'); ?></h3>
	</div>
	<img src="<?php echo esc_attr($PATH_URL); ?>/sdk/assets/images/importer/PMF.png" alt="">
</div>