<?php
$styles = "--slider-arrow-color: " . $attributes[ "arrowColor" ] . ";";
$styles .= "--slider-active-pagination-color: " . $attributes[ "activePaginationColor" ] . ";";
$styles .= "--slider-inactive-pagination-color: " . $attributes[ "inactivePaginationColor" ];
$swiper_settings = array(
    'pagination' => $attributes['pagination'],
    'navigation' => $attributes['navigation']
);
?>
<div <?php echo get_block_wrapper_attributes(array( "style" => $styles )); ?>>
    <script>
        const swiper_settings = <?php echo json_encode( $swiper_settings ); ?>;
    </script>
    <?php
    $args = array(
        'post_type'      => 'fwd-testimonial',
        'posts_per_page' => -1
    );
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) : ?>
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="swiper-slide">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php if ( $attributes['pagination'] ) : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
        <?php if ( $attributes['navigation'] ) : ?>
            <button class="swiper-button-prev"></button>
            <button class="swiper-button-next"></button>
        <?php endif; ?>
        <?php
        wp_reset_postdata();
    endif;
    ?>
</div>