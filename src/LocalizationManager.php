<?php
/**
 * A robust class designed to simplify the management of localized text in WordPress plugins.
 *
 * This class streamlines the process of handling localized content for different plugins,
 * providing a centralized and efficient way to register, store, and retrieve localized text.
 * It offers a structured approach to managing translations and text variations, reducing
 * boilerplate code and ensuring consistency across multilingual WordPress projects.
 *
 * Features:
 * - Easy registration of localized content for individual plugins.
 * - Support for both singular and plural forms of text.
 * - Bulk addition of localized content.
 * - Retrieval of localized text with options for pluralization and case conversion.
 * - Checking for the existence of specific localized elements.
 * - Retrieval of all localized content for a given plugin.
 * - Unique identification of plugins to prevent conflicts.
 * - Filter hook for customizing retrieved localized content.
 *
 * This class is particularly useful for plugin developers who need to manage
 * multiple text strings across different languages and want a centralized,
 * efficient way to handle localization beyond WordPress's built-in functions.
 *
 * @package         arraypress/localization-manager
 * @copyright       Copyright (c) 2024, ArrayPress Limited
 * @license         GPL2+
 * @version         1.0.0
 * @author          David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\LocalizationManager;

use InvalidArgumentException;
use function is_array;
use function strtolower;
use function md5;
use function trim;

/**
 * Check if the class `LocalizationManager` is defined, and if not, define it.
 */
if ( ! class_exists( 'LocalizationManager' ) ):

	/**
	 * Localization Manager
	 *
	 * A static class for managing localized text and content across different WordPress plugins.
	 */
	class LocalizationManager {

		/**
		 * Stored localized elements for different plugins.
		 *
		 * @var array
		 */
		private static array $localized_content = [];

		/**
		 * Register a plugin for localization management.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 * @param array  $content     Optional array of localized content to add immediately
		 */
		public static function register( string $plugin_file, array $content = [] ) {
			$plugin_id = self::get_plugin_id( $plugin_file );
			if ( ! isset( self::$localized_content[ $plugin_id ] ) ) {
				self::$localized_content[ $plugin_id ] = [];
			}
			if ( ! empty( $content ) ) {
				self::add_bulk( $plugin_file, $content );
			}
		}

		/**
		 * Add a localized element to a registered plugin.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 * @param string $key         The key for the localized element
		 * @param mixed  $content     The content (string or array with 'singular' and 'plural')
		 *
		 * @throws InvalidArgumentException If the plugin is not registered
		 */
		public static function add( string $plugin_file, string $key, $content ) {
			$plugin_id = self::get_plugin_id( $plugin_file );
			if ( ! isset( self::$localized_content[ $plugin_id ] ) ) {
				throw new InvalidArgumentException( "Plugin not registered. Call register() first." );
			}
			self::$localized_content[ $plugin_id ][ $key ] = $content;
		}

		/**
		 * Add multiple localized elements to a registered plugin.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 * @param array  $content     Array of localized content
		 *
		 * @throws InvalidArgumentException If the plugin is not registered
		 */
		public static function add_bulk( string $plugin_file, array $content ) {
			$plugin_id = self::get_plugin_id( $plugin_file );
			if ( ! isset( self::$localized_content[ $plugin_id ] ) ) {
				throw new InvalidArgumentException( "Plugin not registered. Call register() first." );
			}
			foreach ( $content as $key => $value ) {
				self::$localized_content[ $plugin_id ][ $key ] = $value;
			}
		}

		/**
		 * Get a localized element for a plugin.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 * @param string $key         The key for the localized element
		 * @param bool   $plural      Whether to get the plural form
		 * @param bool   $lowercase   Whether to return the content in lowercase
		 *
		 * @return string|null        The localized content or null if not found
		 */
		public static function get( string $plugin_file, string $key, bool $plural = false, bool $lowercase = false ): ?string {
			$plugin_id = self::get_plugin_id( $plugin_file );

			if ( ! isset( self::$localized_content[ $plugin_id ][ $key ] ) ) {
				return null;
			}

			$content = self::$localized_content[ $plugin_id ][ $key ];

			if ( is_array( $content ) ) {
				$content = $plural ? ( $content['plural'] ?? $content['singular'] ) : $content['singular'];
			}

			if ( $lowercase ) {
				$content = strtolower( $content );
			}

			/**
			 * Filters the retrieved localized content.
			 *
			 * @param string $content     The localized content.
			 * @param string $plugin_file The __FILE__ of the plugin.
			 * @param string $key         The key for the localized element.
			 * @param bool   $plural      Whether the plural form was requested.
			 * @param bool   $lowercase   Whether lowercase was requested.
			 */
			return apply_filters( 'localization_manager_get', $content, $plugin_file, $key, $plural, $lowercase );
		}

		/**
		 * Check if a localized element exists for a plugin.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 * @param string $key         The key for the localized element
		 *
		 * @return bool               True if the localized element exists, false otherwise
		 */
		public static function has( string $plugin_file, string $key ): bool {
			$plugin_id = self::get_plugin_id( $plugin_file );

			return isset( self::$localized_content[ $plugin_id ][ $key ] );
		}

		/**
		 * Get all localized elements for a plugin.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 *
		 * @return array|null         All localized elements for the plugin or null if not found
		 */
		public static function get_all( string $plugin_file ): ?array {
			$plugin_id = self::get_plugin_id( $plugin_file );

			return self::$localized_content[ $plugin_id ] ?? null;
		}

		/**
		 * Generate a unique ID for a plugin based on its file path.
		 *
		 * @param string $plugin_file The __FILE__ of the plugin
		 *
		 * @return string             A unique ID for the plugin
		 */
		private static function get_plugin_id( string $plugin_file ): string {
			return md5( trim( $plugin_file ) );
		}

	}
endif;