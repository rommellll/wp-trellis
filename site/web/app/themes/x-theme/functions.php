<?php
if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

function x_theme_enqueue_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    /* Include Owl Carousel Main CSS */
    wp_enqueue_style( 'owl_carousel_css', get_stylesheet_directory_uri().'/css/owl/owl.carousel.min.css' );
    /* Include Owl Carousel Default Theme */
	wp_enqueue_style( 'owl_carousel_theme_css', get_stylesheet_directory_uri().'/css/owl/owl.theme.default.min.css' );
	wp_enqueue_style( 'cross_sell_slider_css', get_stylesheet_directory_uri().'/css/vendor/cross-sell-slider.css' );
	wp_enqueue_style( 'filter_style', get_stylesheet_directory_uri().'/css/components/filter.css' );

    /* Include Owl Carousel JS */
	wp_enqueue_script('owl_carousel_js', get_stylesheet_directory_uri().'/js/owl/owl.carousel.min.js', array('jquery'), '1.0.0', TRUE);
    /* Include Custom JS */
	wp_add_inline_script('jquery-core', 'window.$ = jQuery;');
	wp_enqueue_script('x_theme_script', get_stylesheet_directory_uri().'/script.js', array('jquery'), '1.0.0', TRUE);
}
add_action( 'wp_enqueue_scripts', 'x_theme_enqueue_scripts' );


// function called for crossell shortcode
function XTheme_crosssell_slide() {
	global $product;
	ob_start();
	$productID = $product->get_id();
	$crossSellIds = $product->get_cross_sell_ids();
	$crosssellProductIds   =   get_post_meta( $productID, '_crosssell_ids' );
	$crosssellProductIds    =   $crosssellProductIds[0];
	
	$crossSellHead = __('Andere kleuren','x_theme');
	if ( $crosssellProductIds ) {
		echo '<h5>' . $crossSellHead . '</h5>';
	}
	echo '<ul style="list-style: none;" class="owl-carousel owl-theme cross-sell-slides">';
	foreach($crosssellProductIds as $id):
	$post_object = get_post( $id );

	setup_postdata( $GLOBALS['post'] =& $post_object ); 
	wc_get_template_part( 'content', 'product' );
	endforeach;
	echo '</ul>';

     $output = ob_get_clean();
     return $output;
}
add_shortcode('crosssell-slide', 'Xtheme_crosssell_slide');

// Alter WooCommerce View Cart Text
function multi_change_translate_text( $translated ) {
    $text = array(
        'Filter By Search' => 'Filteren op zoeken',
		'Filter By Price' => 'Filteren op prijs',
		'Filter By Status' => 'Filteren op status',
		'Filter By Rating' => 'Filter op beoordeling',
		'Search Products...' => 'Zoek producten...',
		'Add to Wishlist' => 'Toevoegen aan verlanglijst'
    );
    $translated = str_ireplace( array_keys( $text ), $text, $translated );
    return $translated;
}
add_filter( 'gettext', 'multi_change_translate_text', 20 );