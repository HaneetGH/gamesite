<?php
namespace ElementorPro\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class API {

	const PRODUCT_NAME = 'Elementor Pro';

	const STORE_URL = 'http://Nowme.in/api/v1/licenses/';
	const RENEW_URL = 'https://Nowme.in/renew/';

	// License Statuses
	const STATUS_VALID = 'valid';
	const STATUS_INVALID = 'invalid';
	const STATUS_EXPIRED = 'expired';
	const STATUS_DEACTIVATED = 'deactivated';
	const STATUS_SITE_INACTIVE = 'site_inactive';
	const STATUS_DISABLED = 'disabled';

	/**
	 * @param array $body_args
	 *
	 * @return \stdClass|\WP_Error
	 */
	private static function remote_post( $body_args = [] ) {
		$body_args = wp_parse_args(
			$body_args,
			[
				'api_version' => ELEMENTOR_PRO_VERSION,
				'item_name' => self::PRODUCT_NAME,
				'site_lang' => get_bloginfo( 'language' ),
				'url' => home_url(),
			]
		);

		$response = wp_remote_post( self::STORE_URL, [
			'sslverify' => false,
			'timeout' => 40,
			'body' => $body_args,
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== (int) $response_code ) {
			return new \WP_Error( $response_code, __( 'HTTP Error', 'elementor-pro' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $data ) || ! is_array( $data ) ) {
			return new \WP_Error( 'no_json', __( 'An error occurred, please try again', 'elementor-pro' ) );
		}

		return $data;
	}

	public static function activate_license( $license_key ) {
		return array('success'=>true, 'license'=>'valid', 'item_name'=>'Elementor Pro', 'license_limit'=>999, 'site_count'=>1, 'expires'=>'2048-06-06 23:59:59', 'activations_left'=>998, 'payment_id'=>'12345',
'customer_name'=>'Nowme.in', 'customer_email'=>'Nowme.in', 'price_id'=>'1');
		$body_args = [
			'edd_action' => 'activate_license',
			'license' => $license_key,
		];

		$license_data = self::remote_post( $body_args );

		return $license_data;
	}

	public static function deactivate_license() {
		return array('success'=>true, 'license'=>'deactivated', 'item_name'=>'Elementor Pro', 'license_limit'=>999, 'site_count'=>1, 'expires'=>'2048-06-06 23:59:59', 'activations_left'=>998, 'payment_id'=>'12345',
'customer_name'=>'Nowme.in', 'customer_email'=>'Nowme.in', 'price_id'=>'1');
		$body_args = [
			'edd_action' => 'deactivate_license',
			'license' => Admin::get_license_key(),
		];

		$license_data = self::remote_post( $body_args );

		return $license_data;
	}

	public static function set_license_data( $license_data, $expiration = null ) {
		if ( null === $expiration ) {
			$expiration = 12 * HOUR_IN_SECONDS;
		}

		set_transient( 'elementor_pro_license_data', $license_data, $expiration );
	}

	public static function get_license_data( $force_request = false ) {
		$license_data = get_transient( 'elementor_pro_license_data' );
		return $license_data;

		if ( false === $license_data || $force_request ) {
			$body_args = [
				'edd_action' => 'check_license',
				'license' => Admin::get_license_key(),
			];

			$license_data = self::remote_post( $body_args );

			if ( is_wp_error( $license_data ) ) {
				$license_data = [
					'license' => 'http_error',
					'payment_id' => '0',
					'license_limit' => '0',
					'site_count' => '0',
					'activations_left' => '0',
				];

				self::set_license_data( $license_data, 30 * MINUTE_IN_SECONDS );
			} else {
				self::set_license_data( $license_data );
			}
		}

		return $license_data;
	}

	public static function get_version() {
		$updater = Admin::get_updater_instance();

		$translations = wp_get_installed_translations( 'plugins' );
		$plugin_translations = [];
		if ( isset( $translations[ $updater->plugin_slug ] ) ) {
			$plugin_translations = $translations[ $updater->plugin_slug ];
		}

		$locales = array_values( get_available_languages() );

		$body_args = [
			'edd_action' => 'get_version',
			'name' => $updater->plugin_name,
			'slug' => $updater->plugin_slug,
			'version' => $updater->plugin_version,
			'license' => Admin::get_license_key(),
			'translations' => wp_json_encode( $plugin_translations ),
			'locales' => wp_json_encode( $locales ),
			'beta' => 'yes' === get_option( 'elementor_beta', 'no' ),
		];

		$license_data = self::remote_post( $body_args );

		return $license_data;
	}

	public static function get_previous_package_url() {
		$url = 'http://Nowme.in/api/v1/pro-downloads/';

		$body_args = [
			'item_name' => self::PRODUCT_NAME,
			'version' => ELEMENTOR_PRO_PREVIOUS_STABLE_VERSION,
			'license' => Admin::get_license_key(),
			'url' => home_url(),
		];

		$response = wp_remote_post( $url, [
			'sslverify' => false,
			'timeout' => 40,
			'body' => $body_args,
		] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 401 === $response_code ) {
			return new \WP_Error( $response_code, $data['message'] );
		}

		if ( 200 !== $response_code ) {
			return new \WP_Error( $response_code, __( 'HTTP Error', 'elementor-pro' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $data ) || ! is_array( $data ) ) {
			return new \WP_Error( 'no_json', __( 'An error occurred, please try again', 'elementor-pro' ) );
		}

		return $data['package_url'];
	}

	public static function get_errors() {
		return [
			'no_activations_left' => sprintf( __( '<strong>You have no more activations left.</strong> <a href="%s" target="_blank">Please upgrade to a more advanced license</a> (you\'ll only need to cover the difference).', 'elementor-pro' ), 'https://Nowme.in/upgrade/' ),
			'expired' => sprintf( __( '<strong>Your License Has Expired.</strong> <a href="%s" target="_blank">Renew your license today</a> to keep getting feature updates, premium support and unlimited access to the template library.', 'elementor-pro' ), 'https://Nowme.in/renew/' ),
			'missing' => __( 'Your license is missing. Please check your key again.', 'elementor-pro' ),
			'revoked' => __( '<strong>Your license key has been cancelled</strong> (most likely due to a refund request). Please consider acquiring a new license.', 'elementor-pro' ),
		];
	}

	public static function get_error_message( $error ) {
		$errors = self::get_errors();

		if ( isset( $errors[ $error ] ) ) {
			$error_msg = $errors[ $error ];
		} else {
			$error_msg = __( 'An error occurred. Please check your internet connection and try again. If the problem persists, contact our support.', 'elementor-pro' ) . ' (' . $error . ')';
		}

		return $error_msg;
	}
}
