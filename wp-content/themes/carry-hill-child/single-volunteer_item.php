<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');

$volunteer_spot  = do_shortcode('[pods]{@volunteer_spot_link}[/pods]');
$description  = do_shortcode('[pods]{@short_description}[/pods]');
$newsletter_item = do_shortcode('[pods]{@show_on_newsletter_item}[pods]');
$from_date = mysql2date("Y-m-d",do_shortcode('[pods]{@from_date}[/pods]'));
$to_date   = mysql2date("Y-m-d",do_shortcode('[pods]{@to_date}[/pods]'));


?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="two thirds double-padded">
            
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', get_post_format()); ?>
                <div><?php echo $volunteer_spot ?></div>
                <div><?php echo $newsletter_item ?></div>
                <div><?php echo $description ?></div>
                <?php comments_template(); ?>
            <?php endwhile; ?>
        </div>
        <div class="one third double-padded">
           <?php //get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>

