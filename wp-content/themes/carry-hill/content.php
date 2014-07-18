<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php $subtitle = function_exists('get_the_subtitle') ? get_the_subtitle() : false; ?>
    <?php if ($subtitle): ?>
        <div class="one whole">
            <div class="entry-subtitle ch-border-title">
                <?php echo $subtitle; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="one whole double-gap-bottom">
        <div class="entry-meta">
            <?php ch_entry_meta(); ?>
            <?php edit_post_link(__('Edit', CHT_APP_TEXT_DOMAIN), '<span class="edit-link">', '</span>'); ?>
        </div><!-- .entry-meta -->
    </div>
    <div class="one whole double-gap-bottom">
        <?php if (has_post_thumbnail() && !post_password_required()) : ?>
            <div class="entry-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if (is_search()) : // Only display Excerpts for Search ?>
        <div class="one whole double-gap-bottom">
            <?php the_excerpt(); ?>
        </div>
    <?php else : ?>
        <div class="one whole double-gap-bottom">
            <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', CHT_APP_TEXT_DOMAIN)); ?>
            <?php wp_link_pages(array('before'      => '<div class="page-links"><span class="page-links-title">' . __('Pages:', CHT_APP_TEXT_DOMAIN) . '</span>', 'after'       => '</div>', 'link_before' => '<span>', 'link_after'  => '</span>')); ?>
        </div>
    <?php endif; ?>

    <div class="one whole double-gap-bottom">
        <?php if (comments_open() && !is_single()) : ?>
            <div class="comments-link">
                <?php comments_popup_link('<span class="leave-reply">' . __('Leave a comment', CHT_APP_TEXT_DOMAIN) . '</span>', __('One comment so far', CHT_APP_TEXT_DOMAIN), __('View all % comments', CHT_APP_TEXT_DOMAIN)); ?>
            </div><!-- .comments-link -->
        <?php endif; // comments_open() ?>

        <?php if (is_single() && get_the_author_meta('description') && is_multi_author()) : ?>
            <?php get_template_part('author-bio'); ?>
        <?php endif; ?>
    </div>

</article><!-- #post -->


