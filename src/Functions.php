<?php
/**
 * Functions file for managing localizations within a WordPress environment.
 *
 * @package     ArrayPress/LocalizationManager
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @since       1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

use ArrayPress\LocalizationManager\LocalizationManager;

if ( ! function_exists( 'register_localized_text' ) ) {
	/**
	 * Register localizations for a plugin.
	 *
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param array         $localizations  Array of localizations to add
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return bool True on success, false on failure
	 */
	function register_localized_text( string $plugin_file, array $localizations, ?callable $error_callback = null ): bool {
		try {
			LocalizationManager::register( $plugin_file, $localizations );

			return true;
		} catch ( Exception $e ) {
			if ( is_callable( $error_callback ) ) {
				call_user_func( $error_callback, $e );
			}

			return false;
		}
	}
}

if ( ! function_exists( 'get_localized_text' ) ) {
	/**
	 * Get localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $plural         Whether to get the plural form
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return string|null The localized text or null if not found
	 */
	function get_localized_text( string $key, string $plugin_file, bool $plural = false, bool $lowercase = false, ?callable $error_callback = null ): ?string {
		try {
			$text = LocalizationManager::get( $plugin_file, $key, $plural, $lowercase );

			return $text !== null ? ( $lowercase ? strtolower( $text ) : $text ) : null;
		} catch ( Exception $e ) {
			if ( is_callable( $error_callback ) ) {
				call_user_func( $error_callback, $e );
			}

			return null;
		}
	}
}

if ( ! function_exists( 'get_singular_localized_text' ) ) {
	/**
	 * Get singular localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return string|null The singular localized text or null if not found
	 */
	function get_singular_localized_text( string $key, string $plugin_file, bool $lowercase = false, ?callable $error_callback = null ): ?string {
		return get_localized_text( $key, $plugin_file, false, $lowercase, $error_callback );
	}
}

if ( ! function_exists( 'get_plural_localized_text' ) ) {
	/**
	 * Get plural localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return string|null The plural localized text or null if not found
	 */
	function get_plural_localized_text( string $key, string $plugin_file, bool $lowercase = false, ?callable $error_callback = null ): ?string {
		return get_localized_text( $key, $plugin_file, true, $lowercase, $error_callback );
	}
}

if ( ! function_exists( 'add_localized_text' ) ) {
	/**
	 * Add localized text to a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param mixed         $text           The text (string or array with 'singular' and 'plural')
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return bool True on success, false on failure
	 */
	function add_localized_text( string $key, $text, string $plugin_file, ?callable $error_callback = null ): bool {
		try {
			LocalizationManager::add( $plugin_file, $key, $text );

			return true;
		} catch ( Exception $e ) {
			if ( is_callable( $error_callback ) ) {
				call_user_func( $error_callback, $e );
			}

			return false;
		}
	}
}

if ( ! function_exists( 'localized_text_exists' ) ) {
	/**
	 * Check if localized text exists for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return bool True if the localized text exists, false otherwise
	 */
	function localized_text_exists( string $key, string $plugin_file, ?callable $error_callback = null ): bool {
		try {
			return LocalizationManager::has( $plugin_file, $key );
		} catch ( Exception $e ) {
			if ( is_callable( $error_callback ) ) {
				call_user_func( $error_callback, $e );
			}

			return false;
		}
	}
}

if ( ! function_exists( 'echo_localized_text' ) ) {
	/**
	 * Echo localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $plural         Whether to get the plural form
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 */
	function echo_localized_text( string $key, string $plugin_file, bool $plural = false, bool $lowercase = false, ?callable $error_callback = null ): void {
		echo get_localized_text( $key, $plugin_file, $plural, $lowercase, $error_callback ) ?? '';
	}
}

if ( ! function_exists( 'esc_localized_text' ) ) {
	/**
	 * Get escaped localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $plural         Whether to get the plural form
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 *
	 * @return string The escaped localized text or an empty string if not found
	 */
	function esc_localized_text( string $key, string $plugin_file, bool $plural = false, bool $lowercase = false, ?callable $error_callback = null ): string {
		return esc_html( get_localized_text( $key, $plugin_file, $plural, $lowercase, $error_callback ) ?? '' );
	}
}

if ( ! function_exists( 'echo_esc_localized_text' ) ) {
	/**
	 * Echo escaped localized text for a plugin.
	 *
	 * @param string        $key            The key for the localized text
	 * @param string        $plugin_file    The **FILE** of the plugin
	 * @param bool          $plural         Whether to get the plural form
	 * @param bool          $lowercase      Whether to return the text in lowercase
	 * @param callable|null $error_callback Callback function for error handling
	 */
	function echo_esc_localized_text( string $key, string $plugin_file, bool $plural = false, bool $lowercase = false, ?callable $error_callback = null ): void {
		echo esc_localized_text( $key, $plugin_file, $plural, $lowercase, $error_callback );
	}
}