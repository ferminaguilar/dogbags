<?php
class VP_Library
{
	public function __construct()
	{
		$this->hooks();
		$this->register_templates_source();
	}

	public function hooks()
	{
		add_action('elementor/editor/after_enqueue_scripts', array($this, 'enqueue'));
		add_action('elementor/editor/footer', array($this, 'render'));
		add_action('elementor/frontend/before_enqueue_styles', array($this, 'inline_styles'));
	}

	public function inline_styles()
	{
?>
		<style>
			/* Beautiful Template Library Button */
			.vp-library-modal-btn {
				margin-left: 8px;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				vertical-align: top;
				font-size: 0 !important;
				border-radius: 6px;
				padding: 8px 12px;
				box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
				transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
				border: none;
				cursor: pointer;
				position: relative;
				overflow: hidden;
			}

			.vp-library-modal-btn::before {
				content: '';
				position: absolute;
				top: 0;
				left: -100%;
				width: 100%;
				height: 100%;
				background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
				transition: left 0.5s;
			}

			.vp-library-modal-btn:hover::before {
				left: 100%;
			}

			.vp-library-modal-btn:hover {
				transform: translateY(-2px);
				box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
			}

			.vp-library-modal-btn:active {
				transform: translateY(0);
			}

			.vp-library-modal-btn:after {
				content: '';
				width: 18px;
				height: 18px;
				background-image: url('<?php echo esc_url( plugin_dir_url(__FILE__) . 'assets/img/studio-icon.png' ); ?>');
				background-position: center;
				background-size: contain;
				background-repeat: no-repeat;
				display: inline-block;
				position: relative;
				z-index: 1;
				filter: brightness(0) invert(1);
			}

			/* Full-width modal – best UX/UI */
			#vp-library-modal.elementor-templates-modal {
				animation: vpModalFadeIn 0.25s ease-out;
			}
			#vp-library-modal .dialog-widget-content {
				width: 96vw !important;
				max-width: none !important;
				height: 90vh !important;
				max-height: 90vh !important;
				border-radius: 12px;
				box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
				overflow: hidden;
				display: flex;
				flex-direction: column;
			}
			#vp-library-modal .elementor-templates-modal__header {
				flex-shrink: 0;
				padding: 16px 24px;
				border-bottom: 1px solid #e0e0e0;
				background: #fff;
			}
			#vp-library-modal .elementor-templates-modal__header__logo__title {
				font-size: 18px;
				font-weight: 600;
				letter-spacing: 0.02em;
			}
			#vp-library-modal .elementor-templates-modal__body {
				flex: 1;
				overflow: hidden;
				display: flex;
				flex-direction: column;
				min-height: 0;
			}
			#vp-library-modal .elementor-templates-modal__content {
				flex: 1;
				overflow-y: auto;
				overflow-x: hidden;
				padding: 20px 24px 32px;
			}
			#vp-library-modal #elementor-template-library-toolbar {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 12px 20px;
				margin-bottom: 20px;
				padding-bottom: 16px;
				border-bottom: 1px solid #eee;
			}
			#vp-library-modal #elementor-template-library-filter-text-wrapper {
				margin-left: auto;
				position: relative;
			}
			#vp-library-modal #elementor-template-library-filter-text-wrapper .eicon-search {
				position: absolute;
				left: 12px;
				top: 50%;
				transform: translateY(-50%);
				color: #6d7882;
				pointer-events: none;
			}
			#vp-library-modal #elementor-template-library-filter-text {
				padding: 8px 12px 8px 36px;
				border-radius: 8px;
				border: 1px solid #ddd;
				min-width: 200px;
			}
			#vp-library-modal #elementor-template-library-templates-container {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
				gap: 20px;
			}
			#vp-library-modal .elementor-component-tab {
				padding: 8px 16px;
				border-radius: 8px;
				font-weight: 500;
				transition: background 0.2s, color 0.2s;
			}
			#vp-library-modal .elementor-component-tab.elementor-active {
				background: #667eea;
				color: #fff;
			}

			@keyframes vpModalFadeIn {
				from { opacity: 0; transform: scale(0.98); }
				to { opacity: 1; transform: scale(1); }
			}

			/* Template Card Animation */
			#vp-library-modal .elementor-template-library-template {
				animation: vpCardFadeIn 0.35s ease-out backwards;
			}
			#vp-library-modal .elementor-template-library-template:nth-child(1) { animation-delay: 0.03s; }
			#vp-library-modal .elementor-template-library-template:nth-child(2) { animation-delay: 0.06s; }
			#vp-library-modal .elementor-template-library-template:nth-child(3) { animation-delay: 0.09s; }
			#vp-library-modal .elementor-template-library-template:nth-child(4) { animation-delay: 0.12s; }
			#vp-library-modal .elementor-template-library-template:nth-child(5) { animation-delay: 0.15s; }
			#vp-library-modal .elementor-template-library-template:nth-child(6) { animation-delay: 0.18s; }

			@keyframes vpCardFadeIn {
				from { opacity: 0; transform: translateY(12px); }
				to { opacity: 1; transform: translateY(0); }
			}
		</style>
	<?php
	}

	public function register_templates_source()
	{
		Elementor\Plugin::instance()->templates_manager->register_source('VP_Template_Library_Source');
	}

	public function enqueue()
	{
		$api_base_url = trailingslashit(VALUE_PACK_LIBRARY_SYNCER_API_URL);
		$handle = 'vp-library-blocks';
		wp_enqueue_script($handle, plugin_dir_url(__FILE__) . 'assets/js/blocks-templates.js', array('jquery'), '1.0.0', true);

		$license_key = trim((string) get_option('valuepack-addons_key', ''));
		$license_status = strtolower(trim((string) get_option('valuepack-addons-status', '')));
		$license_valid = ($license_key !== '' && $license_status === 'valid');

		wp_localize_script($handle, 'vp_library_syncer', [
			'api_url'          => $api_base_url,
			'license_valid'    => $license_valid,
			'product_category' => (string) get_option('valuepack-addons_product', ''),
		]);
		wp_enqueue_style('vp-block-style', plugin_dir_url(__FILE__) . 'assets/css/blocks-styles.css', [], false);
	}

	public function render()
	{
	?>
		<script type="text/html" id="tmpl-elementor-vp-library-modal-header">
			<div class="elementor-templates-modal__header">
				<div class="elementor-templates-modal__header__logo-area">
					<div class="elementor-templates-modal__header__logo">
						<span class="elementor-templates-modal__header__logo__title">
							Template Library
						</span>
					</div>
				</div>

				<div class="elementor-templates-modal__header__menu-area">
					<div id="elementor-vp-library-header-menu">
					</div>
				</div>

				<div class="elementor-templates-modal__header__items-area">
					<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">
						<i class="eicon-close" aria-hidden="true" title="<?php echo esc_html__('Close', 'vp-library-syncer'); ?>"></i>

						<span class="elementor-screen-only">
							<?php echo esc_html__('Close', 'vp-library-syncer'); ?>
						</span>
					</div>
				</div>
			</div>
		</script>
		<script type="text/html" id="tmpl-elementor-vp-library-modal-element-type-order">
			<div id="elementor-template-library-element-type-filter">
				<select id="elementor-template-library-filter-element-type" class="elementor-template-library-filter-select" data-elementor-filter="element-type" data-placeholder="All Element Types">
					<option value="all"><?php echo esc_html__('All Element Types', 'vp-library-syncer'); ?></option>
					<# data.elementTypes.forEach(function(item, i) { #>
						<option value="{{{item.slug}}}">{{{item.title}}}</option>
						<# }); #>
				</select>
			</div>
		</script>
		<script type="text/html" id="tmpl-elementor-vp-library-modal-order">
			<div id="elementor-template-library-filter">
				<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select" data-elementor-filter="subtype" data-placeholder="All Categories">
					<option value="all"><?php echo esc_html__('All Categories', 'vp-library-syncer'); ?></option>
					<# data.tags.forEach(function(item, i) { #>
						<option value="{{{item.slug}}}">{{{item.title}}}</option>
						<# }); #>
				</select>
			</div>
		</script>



		<script type="text/template" id="tmpl-elementor-vp-library-header-menu">
			<# data.types.forEach(function(item, i) { #>
				<div id="vp-tab-{{{item.slug}}}" class="elementor-component-tab elementor-template-library-menu-item {{{ item.slug === 'section' ? 'elementor-active' : '' }}}" data-tab="{{{ item.slug }}}">{{{ item.title }}}</div>
			<# }); #>
		</script>

		<script type="text/html" id="tmpl-elementor-vp-library-modal">
			<div id="elementor-template-library-templates" data-template-source="remote">
				<div id="elementor-template-library-toolbar">
					<div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar"></div>

					<div id="elementor-template-library-filter-text-wrapper">
						<label for="elementor-template-library-filter-text" class="elementor-screen-only"><?php echo esc_html__('Search Templates:', 'vp-library-syncer'); ?></label>
						<input id="elementor-template-library-filter-text" placeholder="<?php echo esc_attr__('Search', 'vp-library-syncer'); ?>">
						<i class="eicon-search"></i>
					</div>
				</div>

				<div id="elementor-template-library-templates-container"></div>

				<div class="vp-load-more-wrap" style="display:none; text-align:center; padding:16px 0;">
					<button type="button" class="elementor-button vp-load-more-btn">
						<span class="vp-load-more-text">Load more</span>
					</button>
				</div>

				<div id="elementor-template-library-footer-banner">
					<img class="elementor-nerd-box-icon" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/elementor/assets/images/information.svg">
					<div class="elementor-excerpt">Stay tuned! More awesome templates coming real soon.</div>
				</div>
			</div>

			<div class="elementor-loader-wrapper" style="display: none">
				<div class="elementor-loader">
					<div class="elementor-loader-boxes">
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
					</div>
				</div>
				<div class="elementor-loading-title"><?php echo esc_html__('Loading', 'vp-library-syncer'); ?></div>
			</div>
		</script>

		<script type="text/html" id="tmpl-elementor-vp-library-modal-item">
			<# data.elements.forEach(function(item, i) { #> 
				<div class="elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-{{{item.type}}}" data-slug="{{{item.slug}}}" data-tag="{{{item.category}}}" data-element-type="{{{item.element_type}}}" data-type="{{{item.type}}}" data-name="{{{item.title}}}">
					<div class="elementor-template-library-template-body">
						<span class="template-tag {{ item.template_type }}">{{{ item.template_type }}}</span>
						<img src="{{{item.image}}}">

						<a class="elementor-template-library-template-preview" href="{{{item.link}}}" target="_blank">
							<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
						</a>
					</div>

					<div class="elementor-template-library-template-footer">
						<# if (item.template_type === 'Paid' && (!vp_library_syncer || !vp_library_syncer.license_valid)) { #>
							<a class="elementor-template-library-template-action elementor-button elementor-buy-now" href="/checkout/">
								<i class="eicon-cart" aria-hidden="true"></i>
								<span class="elementor-button-title">Buy Now</span>
							</a>
						<# } else { #>
							<a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button" data-id="{{{item.id}}}">
								<i class="eicon-file-download" aria-hidden="true"></i>
								<span class="elementor-button-title">Insert</span>
							</a>
						<# } #>
						 <div class="vp-elementor-template-library-template-name">{{{item.title}}}</div>
					</div>
				</div>
				<# }); #>
		</script>
<?php
	}
}

// Add a new template for the preview modal
?>