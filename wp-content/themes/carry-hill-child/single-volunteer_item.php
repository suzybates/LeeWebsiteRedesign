<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Carry Hill
 */
get_header();
get_template_part('header-bottom');

$pod_id = pods('volunteer_item', get_the_id());
$volunteer_spot  = do_shortcode('[pods]{@volunteer_spot_link}[/pods]');
$description  = do_shortcode('[pods]{@description}[/pods]');
$coordinator  = do_shortcode('[pods]{@volunteer_name}[/pods]');
$coordinator_email  = do_shortcode('[pods]{@coordinator_email}[/pods]');

$news_item = $pod_id->field('related_to_news_item');
//$related_news_item = $news_item->field('post_title');
$end_date   = do_shortcode('[pods]{@end_date}[/pods]');


?>
<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
    <?php get_template_part('partials/page-title'); ?>
   
    <div class="cbp-container">
        <div class="two thirds double-padded">
             <?php //var_dump($news_item); ?>
            <?php /* The loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php //get_template_part('content', get_post_format()); ?>
                <div><a class="cbp_widget_link" href="<?php echo $volunteer_spot; ?>"><?php echo $volunteer_spot; ?></a>
                </div>
                
                <div><?php echo $description ?></div>
                <div>Post until: <?php echo $end_date ?></div>
                <?php comments_template(); ?>
            <?php endwhile; ?>
        </div>
        <div class="one third double-padded">
           	<h4>Questions about this volunteer item? </h4>
           	<div>Contact: <?php echo $coordinator ?></div>
           	<div>Email: <?php echo $coordinator_email ?></div>
           	<?php if (!empty($news_item)): ?>
           		<h4>Related to News Item:</h4>
           		<?php foreach ($news_item as $news) { ?>
           			<?php $news_id = $news["ID"]; ?>
           			<?php //echo get_permalink( $news_id );?>
           			<?php //echo get_the_title( $news_id );?>
	           		<div><a href="<?php echo get_permalink( $news_id );?>"><?php echo get_the_title( $news_id ); ?></div>
           		<?php } ?>
           	<?php endif; ?>
        </div>
    </div>
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>

