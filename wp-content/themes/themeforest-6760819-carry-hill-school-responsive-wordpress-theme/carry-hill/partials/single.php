<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
?>
<?php if (!((int) CbpUtils::getSettingAttribute('row', 'wrap'))): ?>
    <div class="cbp-row <?php echo CbpUtils::getSettingAttribute('row', 'css_class'); ?> <?php echo CbpUtils::getSettingAttribute('row', 'custom_css_classes'); ?>">
        <div class="cbp-container <?php echo CbpUtils::getSettingAttribute('box', 'css_class'); ?> <?php echo CbpUtils::getSettingAttribute('box', 'custom_css_classes'); ?> <?php echo CbpUtils::getSettingAttribute('box', 'padding'); ?>">
        <?php endif; ?>
        <?php while (have_posts()) : the_post(); ?>

            <?php $subtitle = get_the_subtitle(); ?>
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
                </div>
            </div>

            <div class="one whole double-gap-bottom">
                <?php if (has_post_thumbnail() && !post_password_required()) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div>
                <?php endif; ?>

            </div>
            <div class="one whole">
                <?php the_content(); ?>
            </div>

            <?php comments_template(); ?>

        <?php endwhile; ?>
        <?php if (!((int) CbpUtils::getSettingAttribute('row', 'wrap'))): ?>
        </div>
    </div>
<?php endif; ?>