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
		$start_date = $pub_date;
		$end_date = date('Y-m-d', strtotime($start_date . ' + 1 month'));
		$startDateFormat = date_format(date_create($start_date), 'Y-m-d') ;
		$endDateFormat = date_format(date_create($end_date), 'Y-m-d');
		$scope = join(array($startDateFormat, '--', $endDateFormat)); 

		
		$pod                = 'newsletter';
		
           
		?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="cbp-row cbp_widget_row ch-content ch-content-top ch-content-bottom ch-content-top-rainbow">
				<?php get_template_part('partials/page-title'); ?>
				<div class="cbp-container">
					<div class="whole double-padded">
						<?php $pod_id = pods($pod, $post->ID); ?>
						<?php $approved_for_publication = $pod_id->field('approved_for_publication'); ?>
						<?php if ($approved_for_publication <> 'Approved'): ?>
							<div> Status: <?php echo $approved_for_publication; ?></div>
						<?php endif; ?>
						<?php get_template_part('content', get_post_format()); ?>
						
						<h3>Events</h3> for <?php echo $startDateFormat; ?> to <?php echo $endDateFormat; ?>
						<table>
						<?php echo do_shortcode('[eme_events scope='.$scope.' template_id=1 notcategory=11]');?>
						</table>
					
						
					
					
					
						<h3>News and Announcements</h3>
						
						<?php $news_item = $pod_id->field('related_news_items'); ?>
							
						<?php if (!empty($news_item)): ?>
							<?php foreach ($news_item as $news) { ?>
								<?php $news_id = $news["ID"]; ?>
								<?php $news_link = get_permalink($news_id); ?>
								<?php $news_title = get_the_title($news_id); ?>
								
							
								<?php $news_item_id = pods('newsletter_item', $news_id); ?>
								<?php $newsletter_description = $news_item_id->display( 'newsletter_longer_description');  ?>
								<?php $volunteer_item = $news_item_id->field('related_volunteer_spot'); ?>
							
								<h4> <a href="<?php echo $news_link;?>"><?php echo $news_title; ?></a></h4>
								<?php echo $newsletter_description; ?>

								<?php if (!empty($volunteer_item)): ?>
									<?php foreach ($volunteer_item as $vol) { ?>
										<?php $vol_id = $vol["ID"]; ?>
										<?php $volunteer_spot_link = get_post_meta( $vol_id, 'volunteer_spot_link', true ); ?>
										<?php $volunteer_spot_title = get_the_title($vol_id); ?>
										<div>Volunteer: <?php echo $volunteer_spot_title; ?> - <a href="<?php echo $volunteer_spot_link;?>"><?php echo $volunteer_spot_link; ?></div>

									<?php } ?>
								<?php endif; ?>
							<?php } ?>
						<?php endif; ?>


			</div>
		<?php endwhile; ?>
	</div>	
</div>
<?php get_template_part('footer-top'); ?>
<?php get_footer(); ?>