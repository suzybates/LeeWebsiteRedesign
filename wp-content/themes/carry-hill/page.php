<?php
/**
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');
?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="two thirds double-padded">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <div class="one third double-padded">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
