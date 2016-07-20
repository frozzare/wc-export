# WooCommerce Export [![Build Status](https://travis-ci.org/frozzare/wc-export.svg?branch=master)](https://travis-ci.org/frozzare/wc-export)

Export various data from WooCommerce.

## Installation

```sh
composer require frozzare/wc-export
```

## Documentation

* [Custom export](#custom-export)
* [Custom writer](#custom-writer)

### Custom export

Example of a custom export type that will export the `billing_email` field from a order. With `query_args` method you can modify the `WP_Query` arguments.

```php
<?php

use Frozzare\WooCommerce\Export\Exports\Export;

class Custom_Emails extends Export {

	/**
	 * Get fields that should be exported.
	 *
	 * @return array
	 */
	public function get_fields() {
		return [
			'billing_email' => __( 'Email', 'woocommerce' )
		];
	}

	/**
	 * Modify WP Query args.
	 *
	 * @param  array  $args
	 *
	 * @return array
	 */
	public function query_args( array $args ) {
		return $args;
	}
}
```

Then you have to add the custom export to the exporter list with `wc_export_classes` filter.

```php
<?php

/**
 * Add export classes.
 *
 * @param  array $exports
 *
 * @return array
 */
add_filter( 'wc_export_classes', function ( array $exports ) {
	return array_merge( $exports, [
		'Custom emails' => '\\Custom_Emails'
	] );
} );
```

### Custom writer

Example of a custom export writer that will export the given data argument to `render` method as JSON.

```php
<?php

use Frozzare\WooCommerce\Export\Writers\Writer;

class Custom_JSON extends Writer {

	/**
	 * Get the file extension.
	 *
	 * @var string
	 */
	protected function get_extension() {
		return 'json';
	}

	/**
	 * Render JSON file.
	 *
	 * @param array $data
	 */
	public function render( array $data ) {
		if ( $this->is_http_post() ) {
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $this->get_filename() );
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );
		}

		foreach ( $data as $index => $row ) {
			if ( ! is_array( $row ) ) {
				unset( $data[$index] );
			}
		}

		echo json_encode( $data, JSON_UNESCAPED_UNICODE );

		$this->is_http_post() && exit;
	}
}
```

Then you have to add the custom writer to the writers list with `wc_export_writers` filter.

```php
<?php

/**
 * Add export writer.
 *
 * @param  array $writers
 *
 * @return array
 */
add_filter( 'wc_export_writers', function ( array $writers ) {
	return array_merge( $writers, [
		'Custom JSON' => '\\Custom_JSON'
	] );
} );
```

## License

MIT © [Fredrik Forsmo](https://github.com/frozzare)
