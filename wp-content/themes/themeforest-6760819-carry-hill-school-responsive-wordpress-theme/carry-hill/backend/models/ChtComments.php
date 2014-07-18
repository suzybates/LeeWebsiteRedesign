<?php
/**
 * @author Doctor Krivinarius <krivinarius@gmail.com>
 */
class ChtComments
{

    public function renderComments($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p><?php _e('Pingback:', CHT_APP_TEXT_DOMAIN); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', CHT_APP_TEXT_DOMAIN), '<span class="edit-link">', '</span>'); ?></p>
                    <?php
                    break;
                default :
                    // Proceed with normal comments.
                    global $post;
                    ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="row comment">

                        <div class="gravatar">

                            <?php echo get_avatar($comment, 115); ?>
                        </div>

                        <div class="body">

                            <header class="comment-meta comment-author vcard">
                                <div class="reply">
                                    <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', CHT_APP_TEXT_DOMAIN), 'depth'      => $depth, 'max_depth'  => $args['max_depth']))); ?>
                                    <i class="fa fa-share"></i>
                                </div><!-- .reply -->
                                <?php
                                printf('<cite class="fn">%1$s %2$s</cite>', get_comment_author_link(),
                                        // If current post author is also comment author, make it known visually.
                                        ( $comment->user_id === $post->post_author ) ? '<span> ' . __('Post author -', CHT_APP_TEXT_DOMAIN) . '</span>' : ' -'
                                );
                                echo '<hr>';
                                printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time(),
                                        /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s', CHT_APP_TEXT_DOMAIN), get_comment_date(), get_comment_time())
                                );
                                ?>

                                
                            </header><!-- .comment-meta -->

                            <?php if ('0' == $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', CHT_APP_TEXT_DOMAIN); ?></p>
                            <?php endif; ?>

                            <section class="comment-content comment">
                                <?php comment_text(); ?>
                                <?php edit_comment_link(__('Edit', CHT_APP_TEXT_DOMAIN), '<p class="edit-link">', '</p>'); ?>
                            </section><!-- .comment-content -->

                        </div>

                    </article><!-- #comment-## -->
                    <?php
                    break;
            endswitch; // end comment_type check
        }

    }
