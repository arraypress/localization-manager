# WordPress Localization Manager Library

The WordPress Localization Manager Library simplifies the process of managing localizations within a WordPress
environment. It provides utility functions that leverage the LocalizationManager class to handle the registration and
retrieval of localized text for various WordPress plugins.

## Features

- Easy registration of localizations for plugins
- Integration with WordPress translation functions
- Retrieval of localized text with support for plural forms and lowercase options
- Addition of new localized text to existing plugins
- Checking for the existence of localized text
- Error handling with optional callback function
- Escaping functions for secure output

## Requirements

- PHP 7.4 or higher
- WordPress 6.6.2 or higher

## Installation

Install the library using Composer:

```bash
composer require arraypress/localization-manager
```

## Usage

### Registering Plugin Localizations

When registering localizations, use WordPress translation functions to ensure the text is translatable:

```php
// Register localizations for this plugin
register_localized_text( __FILE__, [
    'hello_world' => [
        'singular' => __( 'Hello, World!', 'localization-demo' ),
        'plural'   => __( 'Hello, Worlds!', 'localization-demo' )
    ],
    'goodbye'     => __( 'Goodbye!', 'localization-demo' )
] );
```

### Getting Localized Text

```php
$plugin_file = __FILE__; // Your plugin's main file
$text = get_localized_text( 'hello_world', $plugin_file );
$plural_text = get_localized_text( 'hello_world', $plugin_file, true );
$lowercase_text = get_localized_text( 'hello_world', $plugin_file, false, true );
```

### Adding New Localized Text

When adding new text, also use WordPress translation functions:

```php
$plugin_file = __FILE__; // Your plugin's main file
add_localized_text( 'welcome', __( 'Welcome to our plugin!', 'textdomain' ), $plugin_file );
```

### Checking if Localized Text Exists

```php
if ( localized_text_exists( 'welcome', __FILE__ ) ) {
    // Text exists, do something
}
```

### Echoing Localized Text

```php
echo_localized_text( 'hello_world', $plugin_file );
```

### Escaping Localized Text

```php
$escaped_text = esc_localized_text( 'hello_world', $plugin_file );
echo_esc_localized_text( 'hello_world', $plugin_file );
```

## Error Handling

All functions accept an optional error callback function. If provided, this function will be called with any exceptions
that occur during execution.

```php
$error_callback = function( $exception ) {
    // Handle or log the exception
};

register_localized_text( $plugin_file, $localizations, $error_callback );
```

## Full Example

Here's a complete example demonstrating the usage of the Localization Manager within a WordPress plugin:

```php
<?php
/**
 * Plugin Name: Localization Demo Plugin
 * Plugin URI: https://example.com/localization-demo-plugin
 * Description: Demonstrates the functionality of the LocalizationManager
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: localization-demo
 * Domain Path: /languages
 */

// Initialize the plugin
function localization_demo_init() {

	// Register localizations for this plugin
	register_localized_text( __FILE__, [
		'hello_world' => [
			'singular' => __( 'Hello, World!', 'localization-demo' ),
			'plural'   => __( 'Hello, Worlds!', 'localization-demo' )
		],
		'goodbye'     => __( 'Goodbye!', 'localization-demo' )
	] );

	// Add additional text
	add_localized_text( 'welcome', __( 'Welcome to our plugin!', 'localization-demo' ), __FILE__ );

	// Demonstrate usage of the localization functions
	add_action( 'admin_notices', 'localization_demo_admin_notices' );
}

add_action( 'plugins_loaded', 'localization_demo_init' );

// Display admin notices to showcase the localization functionality
function localization_demo_admin_notices() {
	?>
    <div class="notice notice-info">
        <p><?php echo_esc_localized_text( 'hello_world', __FILE__ ); ?></p>
        <p><?php echo_esc_localized_text( 'hello_world', __FILE__, true ); ?></p>
        <p><?php echo_esc_localized_text( 'goodbye', __FILE__ ); ?></p>
        <p><?php echo_esc_localized_text( 'welcome', __FILE__ ); ?></p>
        <p>
			<?php
			if ( localized_text_exists( 'nonexistent', __FILE__ ) ) {
				echo 'This should not appear.';
			} else {
				echo 'The "nonexistent" key does not exist.';
			}
			?>
        </p>
    </div>
	<?php
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL2+ License.