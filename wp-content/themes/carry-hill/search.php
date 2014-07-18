<?php
/**
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
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="one whole double-pad-right double-pad-bottom">
                        <h3><?php the_title(); ?></h3>
                        <div class="double-gap-bottom"><?php echo ChtUtils::trimmer(get_the_excerpt(), 200); ?></div>
                        <a class="cbp_widget_link" href="<?php the_permalink(); ?>"><?php _e('read more', CHT_APP_TEXT_DOMAIN); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <h3><?php _e('Nothing Found', CHT_APP_TEXT_DOMAIN); ?></h3>
                <div class="one whole double-pad-right double-pad-bottom">
                    <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', CHT_APP_TEXT_DOMAIN); ?></p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="double-pad-top">
            <?php ChtUtils::pagination(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>

<?php get_footer(); ?>