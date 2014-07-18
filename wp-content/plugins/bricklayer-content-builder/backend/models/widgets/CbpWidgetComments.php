<?php
if (!class_exists('CbpWidgetComments')):

    class CbpWidgetComments extends CbpWidget
    {

        public function __construct()
        {
            parent::__construct(
                    /* Base ID */'cbp_widget_comments',
                    /* Name */ 'Comments', array('description' => 'This is a Comments brick.', 'icon'        => 'fa fa-comments fa-3x'));
        }

        public function registerFormElements($elements)
        {
            return parent::registerFormElements($elements);
        }

        public function form($instance)
        {
            parent::form($instance);
        }

        public function widget($atts, $content)
        {

            if (post_password_required())
                return;

            extract(shortcode_atts(array(
                        'type'               => 'cbp_widget_comments',
                        'css_class'          => '',
                        'custom_css_classes' => '',
                        'padding'            => '',
                            ), $atts));

            $css_class          = !empty($css_class) ? ' ' . $css_class : '';
            $custom_css_classes = !empty($custom_css_classes) ? ' ' . $custom_css_classes : '';
            $padding            = CbpWidgets::getCssClass($padding);


            global $post;
            $comments = get_comments(array('post_id' => $post->ID));
            ?>
            <div id="comments" class="<?php echo CbpWidgets::getDefaultWidgetCssClass(); ?> comments-area triple-gap-top <?php echo $type; ?> <?php echo $padding; ?><?php echo $custom_css_classes; ?><?php echo $css_class; ?> <?php echo $padding; ?>">
                <?php if (count($comments)) : ?>
                    <h2 class="comments-title">
                        <?php
                        printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'twentythirteen'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
                        ?>
                    </h2>

                    <ul class="comment-list">
                        <?php
                        wp_list_comments(array(
                            'style'       => 'ul',
                            'short_ping'  => true,
                            'avatar_size' => 74,
                            'callback'    => array($this, 'cbpComment')
                                ), $comments);
                        ?>
                    </ul><!-- .comment-list -->

                    <?php
                    // Are there comments to navigate through?
                    if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                        ?>
                        <nav class="navigation comment-navigation" role="navigation">
                            <h1 class="screen-reader-text section-heading"><?php _e('Comment navigation', 'twentythirteen'); ?></h1>
                            <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'twentythirteen')); ?></div>
                            <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'twentythirteen')); ?></div>
                        </nav><!-- .comment-navigation -->
                    <?php endif; // Check for comment navigation    ?>

                    <?php if (!comments_open() && get_comments_number()) : ?>
                        <p class="no-comments"><?php _e('Comments are closed.', 'twentythirteen'); ?></p>
                    <?php endif; ?>

                <?php endif; // have_comments()   ?>

                <?php comment_form(); ?>

            </div><!-- #comments -->
            <?php
        }

        public function cbpComment($comment, $args, $depth)
        {
            $comments = new CbpComments();
            $comments->renderComments($comment, $args, $depth);
        }
    }

    

    

    
    

    




endif;
