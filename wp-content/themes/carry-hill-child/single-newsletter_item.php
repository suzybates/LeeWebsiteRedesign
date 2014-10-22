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
$from_date = do_shortcode('[pods]{@start_post_date_on_home_page}[/pods]');
$to_date   = do_shortcode('[pods]{@end_post_date_on_home_page}[/pods]');
$newsletter_item_contact = do_shortcode('[pods]{@newsletter_item_contact}[/pods]');
$contact_email = do_shortcode('[pods]{@contact_email}[/pods]');
$pod_id = pods('newsletter_item', get_the_id());
$volunteer_item = $pod_id->field('related_volunteer_spot');
?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
    <div class="cbp-container">
        <div class="two thirds double-padded">
            
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
            	<h3> On the Newsletter: </h3>
            	<h4> <?php echo $post->post_title ?> </h4>
            	<div><?php echo $newsletter_longer_description ?></div>
	            <?php if ($show_on_home): ?>
            		<h3> On the Home Page:</h3>
	            		<div><?php echo $home_page_short_description  ?></div>   
	            		<div>From <?php echo $from_date  ?> to <?php echo $to_date ?></div>
	            <?php endif ?>	
	            
           	
                <?php get_template_part('content', get_post_format()); ?>
                
            <?php endwhile; ?>
        </div>
        <div class="one third double-padded">
			<h4>Questions about this post? </h4>
			<div>Contact: <?php echo $newsletter_item_contact ?></div>
			<div>Email: <?php echo $contact_email ?></div>
			 <?php if (!empty($volunteer_item)): ?>
				<h4>Related to Volunteer Spot Item(s):</h4>
				<?php foreach ($volunteer_item as $vol) { ?>
					<?php $vol_id = $vol["ID"]; ?>
					<?php $volunteer_spot_link = get_post_meta( $vol_id, 'volunteer_spot_link', true ); ?>
					<div><a href="<?php echo get_permalink( $vol_id );?>"><?php echo get_the_title( $vol_id ); ?></div>
					<div><a href="<?php echo $volunteer_spot_link;?>"><?php echo $volunteer_spot_link; ?></div>
				
				<?php } ?>
			<?php endif; ?>

        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>
