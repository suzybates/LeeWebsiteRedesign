<?php
/**
 * @package WordPress
 * @subpackage Carry Hill
 * 
 * Template Name: Boxed
 */
get_header();
?>
<div class="ch-main-wrap">
    <?php get_template_part('header-bottom'); ?>
    <div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
        <?php get_template_part('partials/page-title'); ?>
        <div class="cbp-container">
            <div class="one whole double-padded">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php get_template_part('footer-top'); ?>
</div>
<?php get_footer(); ?>