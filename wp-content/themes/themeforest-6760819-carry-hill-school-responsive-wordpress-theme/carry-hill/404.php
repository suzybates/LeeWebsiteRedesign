<?php
/**
 * The template for displaying 404 pages (Not Found).
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
        <div class="double-padded">
            <h1 class="entry-title"><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'twentytwelve'); ?></h1>
            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentytwelve'); ?></p>
            <?php get_search_form(); ?>
        </div>
    </div>

</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
