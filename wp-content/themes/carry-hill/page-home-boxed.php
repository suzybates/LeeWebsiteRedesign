<?php
/**
 * @package WordPress
 * @subpackage Carry Hill
 * 
 * Template Name: Home Boxed
 */
get_header();
?>
<div class="ch-main-wrap">
    <?php get_template_part('header-bottom-home'); ?>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php get_template_part('footer-top'); ?>
</div>
<?php get_footer(); ?>