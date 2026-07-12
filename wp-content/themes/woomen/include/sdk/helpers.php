<?php
function cwp_get_theme_demo_styles($request = '')
{
	$demos = array(
		'demo-style-1' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-20.png',
			'preview'    => 'https://shoesfootwear.woo.demowp.io/',
			'demo_name'	 => 'Comfooter',
			'demo_id'    => '20427',
			'home_title' => 'Home'
		),
		'demo-style-2' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-15.png',
			'preview'    => 'https://modestfashion.woo.demowp.io/',
			'demo_name'	 => 'Modestaan',
			'demo_id'    => '20429',
			'home_title' => 'Home'
		),
		'demo-style-3' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-7.png',
			'preview'    => 'https://fushion.woo.demowp.io/',
			'demo_name'	 => 'Fushion',
			'demo_id'    => '20366',
			'home_title' => 'Home'
		),
		'demo-style-4' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-18.png',
			'preview'    => 'https://lingerie.woo.demowp.io/',
			'demo_name'	 => 'Veloure',
			'demo_id'    => '20425',
			'home_title' => 'Home'
		),
		'demo-style-5' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-16.png',
			'preview'    => 'http://indianfashion.woo.demowp.io/',
			'demo_name'	 => 'Desilusive',
			'demo_id'    => '20411',
			'home_title' => 'Home'
		),
		'demo-style-6' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-17.png',
			'preview'    => 'https://underwear.woo.demowp.io/',
			'demo_name'	 => 'Comforte',
			'demo_id'    => '20431',
			'home_title' => 'Home'
		),
		'demo-style-26' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-26.jpg',
			'preview'    => 'https://yoga-wear.woo.demowp.io/',
			'demo_name'	 => 'YogaFit',
			'demo_id'    => '20577',
			'home_title' => 'Home'
		),
		'demo-style-27' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-27.jpg',
			'preview'    => 'https://fitness-equipment.woo.demowp.io/',
			'demo_name'	 => 'FitGear',
			'demo_id'    => '20580',
			'home_title' => 'Home'
		),
		'demo-style-28' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-28.jpg',
			'preview'    => 'https://nutrition-supplements.woo.demowp.io/',
			'demo_name'	 => 'NutraSupps',
			'demo_id'    => '20582',
			'home_title' => 'Home'
		),
		'demo-style-29' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-29.jpg',
			'preview'    => 'https://organic-food.woo.demowp.io/',
			'demo_name'	 => 'OrganiFresh',
			'demo_id'    => '20584',
			'home_title' => 'Home'
		),
		'demo-style-30' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-30.jpg',
			'preview'    => 'https://sneaker-shoes.woo.demowp.io/',
			'demo_name'	 => 'Kicksy',
			'demo_id'    => '20586',
			'home_title' => 'Home'
		),
		'demo-style-31' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-31.jpg',
			'preview'    => 'https://men-grooming.woo.demowp.io',
			'demo_name'	 => 'Groomy',
			'demo_id'    => '20588',
			'home_title' => 'Home'
		),
		'demo-style-32' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-32.jpg',
			'preview'    => 'https://furniture-v1.woo.demowp.io',
			'demo_name'	 => 'Roomy',
			'demo_id'    => '20590',
			'home_title' => 'Home'
		),
		'demo-style-33' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-33.jpg',
			'preview'    => 'https://furniture-v2.woo.demowp.io',
			'demo_name'	 => 'Living Space',
			'demo_id'    => '20593',
			'home_title' => 'Home'
		),
		'demo-style-34' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-34.jpg',
			'preview'    => 'https://furniture-v3.woo.demowp.io',
			'demo_name'	 => 'Legacy',
			'demo_id'    => '20596',
			'home_title' => 'Home'
		),
		'demo-style-35' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-35.jpg',
			'preview'    => 'https://furniture-v4.woo.demowp.io',
			'demo_name'	 => 'Eclectik',
			'demo_id'    => '20598',
			'home_title' => 'Home'
		),
		'demo-style-36' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-36.jpg',
			'preview'    => 'https://home-decor.woo.demowp.io',
			'demo_name'	 => 'Auracor',
			'demo_id'    => '20599',
			'home_title' => 'Home'
		),
		'demo-style-37' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-37.jpg',
			'preview'    => 'https://kitchen-dining.woo.demowp.io',
			'demo_name'	 => 'Kitchenary',
			'demo_id'    => '20602',
			'home_title' => 'Home'
		),
		'demo-style-38' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-38.jpg',
			'preview'    => 'https://electronics-v1.woo.demowp.io',
			'demo_name'	 => 'TeknoMart',
			'demo_id'    => '20605',
			'home_title' => 'Home'
		),
		'demo-style-39' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-39.jpg',
			'preview'    => 'https://electronics-v2.woo.demowp.io',
			'demo_name'	 => 'Electra',
			'demo_id'    => '20617',
			'home_title' => 'Home'
		),
		'demo-style-40' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-40.jpg',
			'preview'    => 'https://electronics-v3.woo.demowp.io',
			'demo_name'	 => 'MicroShip',
			'demo_id'    => '20608',
			'home_title' => 'Home'
		),
		'demo-style-41' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-41.jpg',
			'preview'    => 'https://electronics-v4.woo.demowp.io',
			'demo_name'	 => 'Elektron',
			'demo_id'    => '20610',
			'home_title' => 'Home'
		),
		'demo-style-42' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-42.jpg',
			'preview'    => 'https://electronics-v5.woo.demowp.io',
			'demo_name'	 => 'BigBuy',
			'demo_id'    => '20611',
			'home_title' => 'Home'
		),
		'demo-style-43' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-43.jpg',
			'preview'    => 'https://fashion-v1.woo.demowp.io',
			'demo_name'	 => 'Glimmer',
			'demo_id'    => '20620',
			'home_title' => 'Home'
		),
		'demo-style-44' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-44.jpg',
			'preview'    => 'https://fashion-v2.woo.demowp.io',
			'demo_name'	 => 'Embrace',
			'demo_id'    => '20622',
			'home_title' => 'Home'
		),
		'demo-style-45' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-45.jpg',
			'preview'    => 'https://fashion-v3.woo.demowp.io',
			'demo_name'	 => 'Vervux',
			'demo_id'    => '20624',
			'home_title' => 'Home'
		),
		'demo-style-7' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-24.png',
			'preview'    => 'https://watches.woo.demowp.io/',
			'demo_name'	 => 'Timemachine',
			'demo_id'    => '20415',
			'home_title' => 'Home'
		),
		'demo-style-8' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-13.png',
			'preview'    => 'https://activewear.woo.demowp.io/',
			'demo_name'	 => 'Kinettic',
			'demo_id'    => '20376',
			'home_title' => 'Home'
		),
		'demo-style-9' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-14.png',
			'preview'    => 'https://plussize.woo.demowp.io/',
			'demo_name'	 => 'Gracefully',
			'demo_id'    => '20436',
			'home_title' => 'Home'
		),
		'demo-style-10' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-22.png',
			'preview'    => 'https://leather.woo.demowp.io/',
			'demo_name'	 => 'Handmadin',
			'demo_id'    => '20421',
			'home_title' => 'Home'
		),
		'demo-style-11' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-23.png',
			'preview'    => 'https://jewelry.woo.demowp.io/',
			'demo_name'	 => 'Eleglance',
			'demo_id'    => '20417',
			'home_title' => 'Home'
		),
		'demo-style-12' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-21.png',
			'preview'    => 'https://handbags.woo.demowp.io/',
			'demo_name'	 => 'Vessoul',
			'demo_id'    => '20423',
			'home_title' => 'Home'
		),
		'demo-style-13' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-10.png',
			'preview'    => 'https://auralista.woo.demowp.io/',
			'demo_name'	 => 'Auralista',
			'demo_id'    => '20372',
			'home_title' => 'Home'
		),
		'demo-style-14' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-5.png',
			'preview'    => 'https://massmarket.woo.demowp.io/',
			'demo_name'	 => 'Masketier',
			'demo_id'    => '20362',
			'home_title' => 'Home'
		),
		'demo-style-15' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-6.png',
			'preview'    => 'https://retromark.woo.demowp.io/',
			'demo_name'	 => 'Retromark',
			'demo_id'    => '20364',
			'home_title' => 'Home'
		),
		'demo-style-16' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-8.png',
			'preview'    => 'https://ecology.woo.demowp.io/',
			'demo_name'	 => 'Ecology',
			'demo_id'    => '20368',
			'home_title' => 'Home'
		),
		'demo-style-17' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-9.png',
			'preview'    => 'https://bohemian.woo.demowp.io/',
			'demo_name'	 => 'Bohemian',
			'demo_id'    => '20370',
			'home_title' => 'Home'
		),
		'demo-style-18' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-19.png',
			'preview'    => 'https://bridalwear.woo.demowp.io/',
			'demo_name'	 => 'Bridalla',
			'demo_id'    => '20409',
			'home_title' => 'Home'
		),
		'demo-style-19' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-4.png',
			'preview'    => 'https://bridge.woo.demowp.io/',
			'demo_name'	 => 'Bridge',
			'demo_id'    => '20348',
			'home_title' => 'Home'
		),
		'demo-style-20' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-11.png',
			'preview'    => 'https://mensclothing.woo.demowp.io/',
			'demo_name'	 => 'Meniac',
			'demo_id'    => '20374',
			'home_title' => 'Home'
		),
		'demo-style-21' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-12.png',
			'preview'    => 'https://casualwear.woo.demowp.io/',
			'demo_name'	 => 'Casual',
			'demo_id'    => '20407',
			'home_title' => 'Home'
		),
		'demo-style-22' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-25.png',
			'preview'    => 'https://beautycosmetics.woo.demowp.io/',
			'demo_name'	 => 'Glowrious',
			'demo_id'    => '20413',
			'home_title' => 'Home'
		),
		'demo-style-23' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-1.png',
			'preview'    => 'https://hautecouture.woo.demowp.io/',
			'demo_name'	 => 'Couture',
			'demo_id'    => '20353',
			'home_title' => 'Home'
		),
		'demo-style-24' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-2.png',
			'preview'    => 'https://readytowear.woo.demowp.io/',
			'demo_name'	 => 'ReadyWear',
			'demo_id'    => '20419',
			'home_title' => 'Home'
		),
		'demo-style-25' => array(
			'folder'     => 'demo',
			'screenshot' => 'Woomen-desk-demo-3.png',
			'preview'    => 'https://diffusion.woo.demowp.io/',
			'demo_name'	 => 'Diffusion',
			'demo_id'    => '20355',
			'home_title' => 'Home'
		),

	);
	if (! empty($request) && isset($demos[$request])) {
		return $demos[$request];
	}

	return $demos;
}


// helper function to get download link for add-on from route site.
function cwp_get_item_download_link($license)
{
	$api_params = array(
		'edd_action' => 'get_version',
		'license'    => ! empty($license->key) ? $license->key : '',
		'item_id'    => isset($license->download_id) ? $license->download_id : false,
		'url'        => home_url()
	);
	
	$api_url = 'https://vpaddons.com/';
	
	if( $license->download_id == 20324 ){
		$api_url = 'https://nextwp.io/index.php';
	}
	
	// call to route url for getting down-loadable link against each add-on
	$request = wp_remote_post($api_url, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

	if (! is_wp_error($request)) {
		$request = json_decode(wp_remote_retrieve_body($request), true);
	}
	if (isset($request['download_link'])) {
		return $request['download_link'];
	} else {
		return false;
	}
}

//helper function to download and install plugin from respective link
function cwp_plugin_activate($download, $slug, $order_id = null, $base = null)
{
    $plugDir = WP_PLUGIN_DIR . '/' . $slug;
    if (!file_exists($plugDir)) {
        // Construct zip filename
        $plugin_zip = WP_PLUGIN_DIR . '/' . $slug . (empty($order_id) ? '' : '-' . $order_id) . '.zip';
        // Download plugin zip to plugin folder
        $response = wp_remote_get($download, array(
            'stream'   => true,
            'timeout'  => 90,
            'filename' => $plugin_zip
        ));
        if (!is_wp_error($response) && is_file($plugin_zip)) {
            // Unzip using WordPress native unzip_file()
            if (unzip_file($plugin_zip, WP_PLUGIN_DIR)) {
                wp_cache_flush();
                // Set base plugin file if not provided
                if (empty($base)) {
                    $exceptions = ['valuepack-addons'];
                    if (!in_array($slug, $exceptions)) {
                        $base = str_replace(['-addon', '-pro'], '', $slug);
                        if ($slug == 'cubewp-framework') {
                            $base = 'cube';
                        }
                    } else {
                        $base = $slug;
                    }
                }
                // Activate the plugin
                activate_plugin($plugDir . '/' . $base . '.php');
                // Delete the zip file using native PHP
                @unlink($plugin_zip);
            }
        }
    }
}

// Load upgrader only if not already loaded
if ( ! class_exists( 'Plugin_Upgrader' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

if ( ! class_exists( 'WP_Upgrader_Skin' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skins.php';
}

if ( ! function_exists( 'plugins_api' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
}

if ( ! function_exists( 'activate_plugin' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}


// Custom silent upgrader skin
if (!class_exists('CWP_Silent_Upgrader_Skin')) {
    class CWP_Silent_Upgrader_Skin extends WP_Upgrader_Skin {
        public function feedback($string, ...$args) {}
        public function header() {}
        public function footer() {}
        public function error($errors) {}
        public function after() {}
    }
}

function cwp_activate_directory_plugin($slug, $base)
{
    // If plugin not installed yet
    if (!file_exists(WP_PLUGIN_DIR . '/' . $base)) {
        $api = plugins_api('plugin_information', array(
            'slug' => $slug,
            'fields' => array('sections' => false),
        ));

        if (!is_wp_error($api) && !empty($api->download_link)) {
            $skin     = new CWP_Silent_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader($skin);
            $result   = $upgrader->install($api->download_link);

            if (!is_wp_error($result)) {
                // Get the actual plugin file that was installed
                $plugin_file = $upgrader->plugin_info();

                if ($plugin_file) {
                    $activate = activate_plugin($plugin_file);
                    return !is_wp_error($activate);
                } else {
                    return new WP_Error('activation_error', 'Plugin installed but could not detect main file.');
                }
            } else {
                return $result; // Installation error
            }
        } else {
            return new WP_Error('plugin_api_error', 'Could not fetch plugin info from WP repository.');
        }
    } else {
        // Already installed, just activate using provided base
        $activate = activate_plugin($base);
        return !is_wp_error($activate);
    }
}





if (!function_exists('woomen_delete_import_option_callback')) {
	/**
	 * Ajax callback to delete the import option.
	 */
	function woomen_delete_import_option_callback()
	{
		if (isset($_POST['security_nonce']) && wp_verify_nonce($_POST['security_nonce'], 'cubewp-admin-nonce')) {

			$deleted = delete_option('woomen_import_completed_once');

			if ($deleted) {
				wp_send_json_success();
			} else {
				wp_send_json_error();
			}
		} else {
			$response = array('status' => 'error', 'msg' => 'Invalid nonce specified');
			wp_send_json($response);
		}
	}
	add_action('wp_ajax_woomen_delete_import_option', 'woomen_delete_import_option_callback');
}
