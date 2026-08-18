<?php
/**
 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
 * based on the registered block metadata. Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function mindset_blocks_mindset_blocks_block_init() {
	wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
}
add_action( 'init', 'mindset_blocks_mindset_blocks_block_init' );

function mindset_register_custom_fields() {
	register_post_meta(
		'page',
		'company_email',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
		)
	);
	register_post_meta(
		'page',
		'company_address',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
		)
	);
}
add_action( 'init', 'mindset_register_custom_fields' );

// Wrapper function for all PHP-only blocks
function mindset_register_php_blocks() {
    // Register our first PHP-only block, similar to block.json.
    // First parameter: Name the block.
    // Second parameter: Define array of arguments.
    register_block_type(
        'mindset-blocks/service-posts',
        array(
            'title'           => "Display Services",
            'icon'            =>"book",
            'category'        =>"text",
            'description'     => "Outputs all the services with a navigation to each service.",
            'keywords'        => "service",
            'render_callback' => 'mindset_render_service_posts',
            'supports'        => array(
                'autoRegister' => true
            )
        )
    );
}
// Hook into 'init' to run this code.
add_action( 'init', 'mindset_blocks_register_php_blocks' );

?>