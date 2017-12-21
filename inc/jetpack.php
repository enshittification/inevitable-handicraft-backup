<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Handicraft
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function handicraft_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'handicraft_infinite_scroll_render',
		'footer'    => 'page',
		'footer_widgets' => array( 'sidebar-1' ),
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for Content Options.
	add_theme_support( 'jetpack-content-options', array(
		'blog-display'   => 'content',
		'author-bio'     => false,
		'avatar-default' => false,
		'post-details'   => array(
			'stylesheet' => 'handicraft-style',
			'date'       => '.posted-on',
			'categories' => '.cat-links',
			'tags'       => '.tags-links',
			'author'     => '.byline',
			'comment'    => '.comments-link',
			'featured-images' => array(
				'archive'     => true,
				'post'        => true,
				'page'        => true,
			),
		),
		'featured-images'      => array(
			'archive'          => true,
			'archive-default'  => false,
			'post'             => true,
			'page'             => true,
			'fallback'         => true,
			'fallback-default' => false,
		),
	) );

	// Add theme support for Social Menus
	add_theme_support( 'jetpack-social-menu', 'svg' );
}
add_action( 'after_setup_theme', 'handicraft_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function handicraft_infinite_scroll_render() {

	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
		handicraft_woocommerce_product_columns_wrapper();
		woocommerce_product_loop_start();
	}

	while ( have_posts() ) {
		the_post();

		if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) :
			wc_get_template_part( 'content', 'product' );
		else :
			get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}

	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
		woocommerce_product_loop_end();
		handicraft_woocommerce_product_columns_wrapper_close();
	}
}

/**
 * Return early if Author Bio is not available.
 */
function handicraft_author_bio() {
	if ( ! function_exists( 'jetpack_author_bio' ) ) {
		get_template_part( 'template-parts/content', 'author' );
	} else {
		jetpack_author_bio();
	}
}

/**
 * Author Bio Avatar Size.
 */
function handicraft_author_bio_avatar_size() {
	return 90; // in px
}
add_filter( 'jetpack_author_bio_avatar_size', 'handicraft_author_bio_avatar_size' );

/**
 * Return early if Social Menu is not available.
 */
function handicraft_social_menu() {
	if ( ! function_exists( 'jetpack_social_menu' ) ) {
		return;
	} else {
		jetpack_social_menu();
	}
}

/**
* Custom function to get the URL of a post thumbnail;
* If Jetpack is not available, fall back to wp_get_attachment_image_src()
*/
function handicraft_get_attachment_image_src( $post_id, $post_thumbnail_id, $size ) {
	if ( function_exists( 'jetpack_featured_images_fallback_get_image_src' ) ) {
		return jetpack_featured_images_fallback_get_image_src( $post_id, $post_thumbnail_id, $size );
	} else {
		$attachment = wp_get_attachment_image_src( $post_thumbnail_id, $size ); // Attachment array
		$url = $attachment[0]; // Attachment URL
		return $url;
	}
}
