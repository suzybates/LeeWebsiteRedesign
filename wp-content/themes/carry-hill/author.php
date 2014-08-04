<?php
/**
 * The template for displaying Author archive pages.
 * 
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');
?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <div class="cbp-container">
        <div class="cbp_widget_box one whole double-padded">
            <div class="chp_widget_page_title ">
                <div class="one eighth">
                    <div class="chp-page-title-icon">            
                        <i class="fa fa-user fa-4x"></i>
                    </div>
                </div>
                <div class="seven eighths">
                    <h1><?php echo ChtUtils::translate('Author'); ?></h1>
                    <hr>
                    <?php
                    if (function_exists('breadcrumb_trail'))
                        breadcrumb_trail(array('show_browse' => false));
                    ?>                           
                </div>
            </div>
        </div>
    </div>
    <div class="cbp-container">

        <?php if (have_posts()) : ?>

            <?php
            /* Queue the first post, that way we know
             * what author we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop
             * properly with a call to rewind_posts().
             */
            the_post();
            ?>

            <header class="archive-header">
                <h1 class="archive-title"><?php printf(__('All posts by %s', CHT_APP_TEXT_DOMAIN), '<span class="vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author()) . '" rel="me">' . get_the_author() . '</a></span>'); ?></h1>
            </header><!-- .archive-header -->

            <?php
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();
            ?>

            <?php if (get_the_author_meta('description')) : ?>
                <?php get_template_part('author-bio'); ?>
            <?php endif; ?>

            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="one whole double-pad-right double-pad-bottom">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <hr />
                    <div class="double-gap-bottom"><?php echo ChtUtils::trimmer(get_the_excerpt(), 200); ?></div>
                    <a class="cbp_widget_link" href="<?php the_permalink(); ?>"><?php _e('read more', CHT_APP_TEXT_DOMAIN); ?></a>
                </div>
            <?php endwhile; ?>

        <?php else : ?>
            <?php get_template_part('content', 'none'); ?>
        <?php endif; ?>
        <div class="double-pad-top">
            <?php ChtUtils::pagination(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
