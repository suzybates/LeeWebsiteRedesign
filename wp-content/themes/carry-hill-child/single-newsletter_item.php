<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');


$newsletter_longer_description = do_shortcode('[pods]{@newsletter_longer_description}[/pods]');
$show_on_home = do_shortcode('[pods]{@show_on_home_page}[/pods]');
$home_page_short_description = do_shortcode('[pods]{@home_page_short_description}[/pods]');
$from_date = mysql2date("Y-m-d",do_shortcode('[pods]{@start_post_date_on_home_page}[/pods]'));
$to_date   = mysql2date("Y-m-d",do_shortcode('[pods]{@end_post_date_on_home_page}[/pods]'));


?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="two thirds double-padded">
            
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
            	<h2> Newsletter Content </h2>
	            	<div><?php echo $newsletter_longer_description ?></div>
            	<h2> Home Page Announcement Content </h2>
	            	<div>Show on Homepage? <?php echo $show_on_home ?></div>	
	            	<div><?php echo $home_page_short_description  ?></div>   
	            	<div>From <?php echo $from_date  ?> to <?php echo $to_date ?></div>
	            	           	
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
