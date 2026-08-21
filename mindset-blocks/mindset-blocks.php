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
function mindset_blocks_register_php_blocks() {
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
                'autoRegister' => true,
                'spacing' => array(
                    'margin' => true
                )
            ),
            'attributes' => array(
                'sorting' => array(
                    'type' => 'string',
                    'enum' => array('ASC', 'DESC'),
                    'default' => 'ASC',
                    'label' => 'Sort A-Z or Z-A'
                )
            )
        )
    );
}
// Hook into 'init' to run this code.
add_action( 'init', 'mindset_blocks_register_php_blocks' );

function mindset_render_service_posts( $attributes ) {
    ob_start();
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
		<?php
		$args = array(
			'post_type' => 'fwd-service',
			'orderby' => 'title',
			'order' => 'ASC',
		);
		$query = new WP_Query( $args );
		echo '<nav class="services-nav"><ul>';
		if ( $query -> have_posts() ) {
			while( $query -> have_posts() ) {
				$query -> the_post();
		?>

			<li><a href = "#<?php the_ID(); ?>">
			<?php
					echo get_the_title();
				echo '</a></li>';
		
			}
			wp_reset_postdata(); 
		}
		echo '</ul></nav>';
        
        ?>
        <?php
		$taxonomy = 'fwd-service-type';
		$terms = get_terms( 
			array(
				'taxonomy' => $taxonomy,
			) 
		);
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				echo '<h2>' .$term->name. '</h2>';
				$args = array(
					'post_type' => 'fwd-service',
					'posts_per_page' => -1,
					'orderby' => 'title',
					'order' => 'ASC',
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $term->slug
						)
					)
				);
				$query = new WP_Query( $args );
				if ( $query -> have_posts() ) {
					while( $query -> have_posts() ) {
						$query -> the_post();
		?>
					<article id = "<?php the_ID(); ?>">
					<?php
							echo '<h3>' . get_the_title() . '</h3>';
							echo the_content();
						echo '</article>';
				
					}
					wp_reset_postdata(); 
				}
			}
		}
        
        ?>
    </div>
    <?php
    return ob_get_clean();
}

?>