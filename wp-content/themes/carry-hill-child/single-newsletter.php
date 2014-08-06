<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');

$pub_date  = do_shortcode('[pods]{@newsletter_date}[/pods]');
$from_date = mysql2date("Y-m-d",do_shortcode('[pods]{@events_from_date}[/pods]'));
$to_date   = mysql2date("Y-m-d",do_shortcode('[pods]{@events_to_date}[/pods]'));


?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="two thirds double-padded">
            <h2>
            Newsletter for <?php echo $pub_date; ?>
            </h2>

         	<h3>Events</h3> from <?php echo $from_date; ?> to <?php echo $to_date; ?>
         	<table>
			<?php echo do_shortcode('[eme_events scope='.$from_date.'--'.$to_date.' template_id=1]');?>
			</table>
			Try again
			<?php //echo do_shortcode('[eme_events scope='{@events_from_date}'--'{@events_to_date}']');?>
			
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('content', get_post_format()); ?>
                <?php // twentythirteen_post_nav(); ?>
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
