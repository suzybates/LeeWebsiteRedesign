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
        <div class="one whole double-padded">
            <?php if (have_posts()) : ?>
                <?php woocommerce_content(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
