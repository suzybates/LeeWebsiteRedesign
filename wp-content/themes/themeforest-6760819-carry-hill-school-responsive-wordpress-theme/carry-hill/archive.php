<?php
/**
 * The template for displaying Archive pages.
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
                        <i class="fa fa-archive fa-4x"></i>
                    </div>
                </div>
                <div class="seven eighths">
                    <h1>
                        <?php
                        if (is_day()) :
                            printf(__('Daily Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date() . '</span>');
                        elseif (is_month()) :
                            printf(__('Monthly Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', CHT_APP_TEXT_DOMAIN)) . '</span>');
                        elseif (is_year()) :
                            printf(__('Yearly Archives: %s', CHT_APP_TEXT_DOMAIN), '<span>' . get_the_date(_x('Y', 'yearly archives date format', CHT_APP_TEXT_DOMAIN)) . '</span>');
                        else :
                            _e('Archives', CHT_APP_TEXT_DOMAIN);
                        endif;
                        ?>
                    </h1>
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
        <div class="double-padded">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="one whole double-pad-right double-pad-bottom">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <hr />
                        <div class="double-gap-bottom"><?php the_excerpt(); ?></div>
                        <a class="cbp_widget_link" href="<?php the_permalink(); ?>"><?php _e('read more', CHT_APP_TEXT_DOMAIN); ?></a>
                    </div>

                <?php endwhile; ?>
            <?php else : ?>
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