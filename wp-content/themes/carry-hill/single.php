<?php
/**
 * The Template for displaying all single posts.
 *
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
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', get_post_format()); ?>
                <?php // twentythirteen_post_nav(); ?>
                <?php comments_template(); ?>
            <?php endwhile; ?>
        </div>
        <div class="one third double-padded">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
