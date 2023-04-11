<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Update theme
 *
 * @since     1.0
 * @package   TM_TwentyFramework
 */
if ( ! class_exists( 'InsightCore_Updater' ) ) {

	class InsightCore_Updater {

		public function __construct() {
			add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_for_update' ] );

			add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_plugins_for_update' ], 9999 );
		}

		/**
		 * The filter that checks if there are updates to the theme
		 * using the WP License Manager API.
		 *
		 * @param mixed $transient The transient used for WordPress theme / plugin updates.
		 *
		 * @return mixed The transient with our (possible) additions.
		 */
		public function check_for_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$update = InsightCore::instance()->get_theme_update_info( 'get_theme_update' );

			if ( ! empty( $update ) && ! empty( $update['success'] ) && ! empty( $update['version'] ) && ! empty( $update['package'] ) ) {
				if ( version_compare( INSIGHT_CORE_THEME_VERSION, $update['version'], '<' ) ) {
					$response = array(
						'url'         => '',
						'new_version' => $update['version'],
						'package'     => $update['package'],
					);

					$transient->response[ INSIGHT_CORE_THEME_SLUG ] = $response;
				}
			}

			return $transient;
		}

		/**
		 * The filter that checks if there are updates to the plugins
		 * using the WP License Manager API.
		 *
		 * Fixed Elementor Pro can't auto update with TGM Plugin Activation
		 *
		 * @param mixed $transient The transient used for WordPress theme / plugin updates.
		 *
		 * @return mixed The transient with our (possible) additions.
		 */
		public function check_plugins_for_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$plugins = TGM_Plugin_Activation::$instance->plugins;
			$plugins = apply_filters( 'insight_core_tgm_plugins', $plugins );

			if ( ! empty( $plugins ) ) {
				$installed_plugins = get_plugins();

				foreach ( $plugins as $plugin ) {
					if ( ! empty( $plugin['slug'] ) && 'elementor-pro' === $plugin['slug'] && ! empty( $plugin['file_path'] ) ) {

						if ( ! empty( $installed_plugins ) && ! empty( $installed_plugins[ $plugin['file_path'] ] ) ) {
							$installed_version = $installed_plugins[ $plugin['file_path'] ]['Version'];

							if ( version_compare( $installed_version, $plugin['version'], '<' ) ) {
								$transient->response[ $plugin['file_path'] ]->new_version = $plugin['version'];
								$transient->response[ $plugin['file_path'] ]->package     = $plugin['source'];
							}
						}
					}
				}
			}

			return $transient;
		}
	}

	new InsightCore_Updater();
}
