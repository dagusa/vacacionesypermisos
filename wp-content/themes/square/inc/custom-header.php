<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * @package Square
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses square_header_style()
 * @uses square_admin_header_image()
 */
function square_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'square_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 300,
		'height'                 => 60,
		'flex-height'            => true,
		'flex-width'            => true,
		'wp-head-callback'       => 'square_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'square_custom_header_setup' );

if ( ! function_exists( 'square_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see square_custom_header_setup().
 */
function square_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value.
	if ( HEADER_TEXTCOLOR === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.sq-site-title a,
		.sq-site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that.
		else :
	?>
		.sq-site-title a,
		.sq-site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // square_header_style