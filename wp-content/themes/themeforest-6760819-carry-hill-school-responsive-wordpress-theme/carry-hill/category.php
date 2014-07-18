<?php
/**
 * The template for displaying Category pages.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
?>
<?php get_header(); ?>
<?php get_template_part('header-bottom'); ?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="double-padded">
            <?php if (have_posts()): ?>
                <?php if (category_description()): // Show an optional category description ?>
                    <div class="archive-meta"><?php echo category_description(); ?></div>
                <?php endif; ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php //get_template_part('content', get_post_format()); ?>
                    <div class="one whole double-pad-right double-pad-bottom">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <hr />
                        <div class="double-gap-bottom"><?php the_excerpt(); ?></div>
                        <a class="cbp_widget_link" href="<?php the_permalink(); ?>"><?php _e('read more', CHT_APP_TEXT_DOMAIN); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <?php get_template_part('content', 'none'); ?>
            <?php endif; ?>
        </div>
        <div class="double-pad-top">
            <?php ChtUtils::pagination(); ?>
        </div>
    </div>
</div>


<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
