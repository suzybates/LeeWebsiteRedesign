<?php if (!is_home()): ?>
    <?php $title_bar_class = 'one whole'; ?>
    <?php $iconEnabled     = ChtUtils::getOption('page_title_use_icon') == 'true' ? true : false; ?>
    <div class="cbp-container">
        <div class="cbp_widget_box one whole double-padded">
            <div class="chp_widget_page_title ">
                <?php if ($iconEnabled): ?>
                    <?php $title_bar_class = 'seven eighths'; ?>
                    <div class="one eighth">
                        <div class="chp-page-title-icon">
                            <?php if (is_category() || is_tax()): ?>
                                <?php if (is_tax('portfolio_category')): ?>
                                    <i class="fa fa-camera-retro fa-4x"></i>
                                <?php else: ?>
                                    <i class="fa fa-archive fa-4x"></i>
                                <?php endif; ?>
                            <?php elseif (is_404()): ?>
                                <i class="fa fa-exclamation fa-4x"></i>
                            <?php else: ?>
                                <?php $iconName = isset($post->ID) ? ChtUtils::getMeta($post->ID, 'page_icon') : false; ?>
                                <?php if ($iconName): ?>
                                    <i class="fa <?php echo $iconName; ?> fa-4x"></i>
                                <?php else: ?>
                                    <i class="fa fa-pencil fa-4x"></i>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="<?php echo $title_bar_class; ?>">
                    <?php if (is_category()): ?>
                        <h1>
                            <?php printf(__('Category Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . single_cat_title('', false) . '</span>'); ?>
                        </h1>
                    <?php elseif (is_tax()): ?>
                        <h1><?php echo single_cat_title('', false); ?></h1>
                    <?php elseif (is_404()): ?>
                        <h1><?php echo ChtUtils::translate('404 Not Found'); ?></h1>
                    <?php elseif (is_search()): ?>
                        <h1><?php echo ChtUtils::translate('Search Results'); ?></h1>
                    <?php else: ?>
                        <h1><?php echo get_the_title(); ?></h1>
                    <?php endif; ?>
                    <hr>
                    <?php if (function_exists('breadcrumb_trail')): ?>
                        <?php
                        breadcrumb_trail(array('show_browse'   => false, 'post_taxonomy' => array(
                                // 'post'  => 'post_tag',
                                'portfolio' => 'portfolio_category',
                        )));
                        ?>
                    <?php endif; ?>                           
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
