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
            <?php if (have_posts()): ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div <?php echo post_class('one whole double-pad-right double-pad-bottom') ?>>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <hr />
                        <div class="double-gap-bottom"><?php echo ChtUtils::trimmer(get_the_excerpt(), 200); ?></div>
                        <a class="cbp_widget_link" href="<?php the_permalink(); ?>"><?php _e('read more', CHT_APP_TEXT_DOMAIN); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <?php get_template_part('content', 'none'); ?>
            <?php endif; ?>
            <div class="double-pad-top">
                <?php ChtUtils::pagination(); ?>
            </div>
        </div>
        <div class="one third double-padded">
            <?php dynamic_sidebar('ch-regular-sidebar'); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
