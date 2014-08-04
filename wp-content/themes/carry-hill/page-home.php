<?php

/**
 * @package WordPress
 * @subpackage Carry Hill
 * 
 * Template Name: Home
 */
get_header();
get_template_part('header-bottom-home');
?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
